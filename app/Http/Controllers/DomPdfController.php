<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DomPdfController extends Controller
{
    //
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function invoicePdf($id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();
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

        $logo = $entreprise->url_logo_base64;

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $payment_terms_assign = DB::table('payment_terms_assigns')
            ->where('ref_invoice', $ref_invoice)->first();

        $payment_terms_proforma = null;
        if($payment_terms_assign)
        {
            $payment_terms_proforma = DB::table('payment_terms_proformas')
                ->where('id', $payment_terms_assign->id_payment_terms)->first();
        }

        $notes = DB::table('note_documents')->where('reference_doc', $ref_invoice)->get();

        $pdf = Pdf::loadView('pdf.invoice_pdf', compact(
            'entreprise',
            'functionalUnit',
            'invoice',
            'customer',
            'deviseGest',
            'invoice_elements',
            'logo',
            'tot_excl_tax',
            'contact',
            'payment_terms_proforma',
            'payment_terms_assign',
            'notes'
        ));
        return $pdf->stream('invoice.pdf');
    }

    public function deliveryNotePdf($id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();
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

        $logo = $entreprise->url_logo_base64;

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $type_doc = "delivery_note";

        $notes = DB::table('note_documents')
                ->where([
                    'reference_doc' => $ref_invoice,
                    'type_doc' => $type_doc
                ])->get();


        $pdf = Pdf::loadView('pdf.delivery_note_pdf', compact(
            'entreprise',
            'functionalUnit',
            'invoice',
            'customer',
            'deviseGest',
            'invoice_elements',
            'logo',
            'tot_excl_tax',
            'contact',
            'notes'
        ));
        return $pdf->stream('delivery_note.pdf');
    }
}
