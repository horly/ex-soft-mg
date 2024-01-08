<?php

namespace App\Http\Controllers;

use App\Services\Fpdf\InvoicePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FpdfController extends Controller
{
    //
    public function invoicePdf($id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $invoice_elements = DB::table('invoice_elements')
                            ->where([
                                'ref_invoice' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->get();
        
        $fpdf = new InvoicePdf($entreprise, $functionalUnit, $invoice);
        
        $fpdf->AddPage();
        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->SetTextColor(51, 153, 255);
        
        $numMaj = iconv('UTF-8', 'windows-1252', ' N° ');
        $numMin = iconv('UTF-8', 'windows-1252', ' n° ');

        //cell(width, height, text, border, inline= 0 or 1, alignement)
        $fpdf->Cell(40, 10, __('invoice.invoice_MAJ') . $numMaj . $invoice->reference_sales_invoice, 0, 1);
        $fpdf->Cell(30, 10, '', 0, 1);

        $fpdf->SetFont('Courier', '', 12);
        $fpdf->SetTextColor(0, 0, 0);

        //Line 1
        $fpdf->Cell(50, 5, 'Date', 0, 0);
        $fpdf->Cell(60, 5, date('Y-m-d', strtotime($invoice->created_at)), 0, 0);
        $fpdf->Cell(82, 5, iconv('UTF-8', 'windows-1252', __('invoice.customer')) . ' : ', 0, 1, 'R');

        //Line 2
        $fpdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', __('invoice.due_date')), 0, 0);
        $fpdf->Cell(60, 5, date('Y-m-d', strtotime($invoice->due_date)), 0, 0);
        $fpdf->Cell(80, 5, iconv('UTF-8', 'windows-1252', __('client.reference')) . $numMin . $customer->reference_cl, 0, 1, 'R');

        //Line 3
        $fpdf->Cell(50, 5, '', 0, 0);
        $fpdf->Cell(60, 5, '', 0, 0);
        $fpdf->Cell(80, 5, iconv('UTF-8', 'windows-1252', $customer->entreprise_name_cl), 0, 1, 'R');

        //Line 4
        $fpdf->Cell(50, 5, '', 0, 0);
        $fpdf->Cell(60, 5, '', 0, 0);
        $fpdf->Cell(80, 5, iconv('UTF-8', 'windows-1252', $customer->address_cl), 0, 1, 'R');

        $fpdf->Cell(30, 10, '', 0, 1);

        $header = [
            "#",
            iconv('UTF-8', 'windows-1252', __('article.description')),
            iconv('UTF-8', 'windows-1252', __('invoice.quantity')),
            iconv('UTF-8', 'windows-1252', __('article.unit_price')) . ' ' . $deviseGest->iso_code,
            iconv('UTF-8', 'windows-1252', __('invoice.total_price')) . ' ' . $deviseGest->iso_code,
        ];

        $data = array();
        $i = 1;

        foreach($invoice_elements as $invoice_element)
        {
            $data = array(
                array(
                    $i++,
                    $invoice_element->description_inv_elmnt,
                    $invoice_element->quantity,
                    number_format($invoice_element->unit_price_inv_elmnt, 2, '.', ' '),
                    number_format($invoice_element->total_price_inv_elmnt, 2, '.', ' ')
                ),
            );
        }

        $fpdf->createTable($header,$data);

        $fpdf->Output();
        exit;
    }
}
