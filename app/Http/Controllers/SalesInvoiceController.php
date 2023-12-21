<?php

namespace App\Http\Controllers;

use App\Models\InvoiceElement;
use App\Models\InvoiceMargin;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;
    protected $generateReferenceNumber;

    function __construct(Request $request, 
                            EntrepriseRepo $entrepriseRepo, 
                            NotificationRepo $notificationRepo, 
                            GenerateRefenceNumber $generateReferenceNumber)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
        $this->generateReferenceNumber = $generateReferenceNumber;
    }

    public function salesInvoice($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $invoices = DB::table('clients')
                        ->join('sales_invoices', 'clients.id', '=', 'sales_invoices.id_client')
                        ->where('sales_invoices.id_fu', $functionalUnit->id)
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        return view('invoice_sales.sales_invoice', compact('entreprise', 'functionalUnit', 'deviseGest', 'invoices'));
    }

    public function setUpInvoice()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');

        $invoice_margin_not_saved = DB::table('invoice_margins')
                        ->where([
                            'invoice_saved' => 0,
                            'id_user' => Auth::user()->id,
                            'id_fu' => $id_functionalUnit,
                        ])->first();
        
        if(!$invoice_margin_not_saved)
        {
            /**
             * Format N° de référence de la facture :  
             * INV année mois jour heure minute seconde id_user
             * INV2023121509093023
             */
            $refInvoice = "INV" . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . Auth::user()->id;
            
            InvoiceMargin::create([
                'ref_invoice' => $refInvoice,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_functionalUnit,
            ]);

            return redirect()->route('app_add_new_sales_invoice', [
                'id' => $id_entreprise, 
                'id2' => $id_functionalUnit,
                'ref_invoice' => $refInvoice,
            ]); 
        }
        else
        {
            return redirect()->route('app_add_new_sales_invoice', [
                'id' => $id_entreprise, 
                'id2' => $id_functionalUnit,
                'ref_invoice' => $invoice_margin_not_saved->ref_invoice,
            ]); 
        }
    }

    public function addNewSalesInvoice($id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $articles = DB::table('articles')->where('id_fu', $id2)->get();
        $services = DB::table('services')->where('id_fu', $id2)->get();
        $clients = DB::table('clients')->where('id_fu', $id2)->get();
        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();

        $invoice_margin = DB::table('invoice_margins')
                        ->where([
                            'ref_invoice' => $ref_invoice,
                            'id_user' => Auth::user()->id,
                            'id_fu' => $id2,
                        ])->first();
        
        $invoice_elements = DB::table('invoice_elements')
                            ->where([
                                'ref_invoice' => $ref_invoice,
                                'id_user' => Auth::user()->id,
                                'id_fu' => $id2,
                            ])->get();
        
        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        return view('invoice_sales.add_new_sales_invoice', compact(
            'entreprise', 
            'functionalUnit', 
            'clients', 
            'deviseGest', 
            'ref_invoice',
            'services',
            'articles',
            'invoice_margin',
            'invoice_elements',
            'country',
            'tot_excl_tax',
        ));
    }

    public function calculateMargin()
    {
        $margin = $this->request->input('margin');
        $purchase_price = $this->request->input('purchase_price');

        $value = ($purchase_price * $margin) / 100;
        $final_price = $purchase_price + $value;

        return response()->json([
            'code' => 200,
            'final_price' => $final_price
        ]);
    }

    public function changeVat()
    {
        $vat_purcentage = $this->request->input('vat_purcentage');
        $tot_excl_tax = $this->request->input('tot_excl_tax');

        $vat_value = ($tot_excl_tax * $vat_purcentage) / 100;
        $vat_value_format_double = number_format($vat_value, 2, '.', ' ');

        $tot_incl_tax = $tot_excl_tax + $vat_value;
        $tot_incl_tax_format_double = number_format($tot_incl_tax, 2, '.', ' ');

        return response()->json([
            'code' => 200,
            'vat_value' => $vat_value,
            'vat_value_format_double' => $vat_value_format_double,
            'tot_incl_tax' => $tot_incl_tax,
            'tot_incl_tax_format_double' => $tot_incl_tax_format_double,
        ]);
    }

    public function changeDiscountCustomer()
    {
        $discount_value = $this->request->input('discount_value');
        $tot_excl_tax = $this->request->input('tot_excl_tax');
        $vat_purcentage = $this->request->input('vat_purcentage');
        $type = $this->request->input('type');
        
        $sub_total = 0;
        $total_val = 0;
        $vat_value = 0;

        if($type == "pourcentage")
        {
            $value = ($tot_excl_tax * $discount_value) / 100;
            $sub_total = $tot_excl_tax - $value;

            $vat_value = ($sub_total * $vat_purcentage) / 100;
            $total_val = $sub_total + $vat_value;

        }
        else
        {
            $sub_total = $tot_excl_tax - $discount_value;
            $vat_value = ($sub_total * $vat_purcentage) / 100;
            
            $total_val = $sub_total + $vat_value;
        }
        
        return response()->json([
            'code' => 200,
            'vat_value' => $vat_value,
            'vat_value_format_double' => number_format($vat_value, 2, '.', ' '),
            'sub_total' => $sub_total,
            'sub_total_format_number' => number_format($sub_total, 2, '.', ' '),
            'total_val' => $total_val,
            'total_val_format_number' => number_format($total_val, 2, '.', ' '),
        ]);
    }

    public function insertInvoiceElemebt()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');
        $id_invoice_element = $this->request->input('id_invoice_element');
        $modalRequest = $this->request->input('modalRequest');
        $ref_invoice = $this->request->input('ref_invoice');
        $descrption_saved_art = $this->request->input('descrption_saved_art');
        $id_invoice_margin = $this->request->input('id_invoice_margin'); 
        $margin_invoice = $this->request->input('article_margin_invoice');

        $article_sales_invoice = $this->request->input('article_sales_invoice');
        $article_qty_invoice = $this->request->input('article_qty_invoice');
        $article_margin_invoice = $this->request->input('article_margin_invoice');
        $article_purchase_price_invoice = $this->request->input('article_purchase_price_invoice');
        $article_sale_price_invoice = $this->request->input('article_sale_price_invoice');
        $total_price_inv_elmnt = $this->request->input('article_total_price_invoice');
        $is_an_article = $this->request->input('is_an_article');

        if($modalRequest != "edit")
        {
            //si c'est un article
            if($is_an_article == 1)
            {
                $article_exist = DB::table('invoice_elements')->where('ref_article', $article_sales_invoice)->first();

                if(!$article_exist)
                {
                    $invoice_el_select = InvoiceElement::create([
                        'ref_invoice' => $ref_invoice,
                        'ref_article' => $article_sales_invoice,
                        'description_inv_elmnt' => $descrption_saved_art,
                        'quantity' => $article_qty_invoice,
                        'purshase_price_inv_elmnt' => $article_purchase_price_invoice,
                        'unit_price_inv_elmnt' => $article_sale_price_invoice,
                        'total_price_inv_elmnt' => $total_price_inv_elmnt,
                        'id_marge' => $id_invoice_margin,
                        'id_user' => Auth::user()->id,
                        'id_fu' => $id_fu,
                    ]);

                    $whereData = [
                        ['ref_invoice', $ref_invoice],
                        ['id_user', Auth::user()->id],
                        ['id_fu', $id_fu],
                        ['id', '<>', $invoice_el_select->id]
                    ];

                    $get_invoice_exist = DB::table('invoice_elements')
                                        ->where($whereData)
                                        ->get();

                    foreach($get_invoice_exist as $invoice_elemnt)
                    {
                        $pur_price = $invoice_elemnt->purshase_price_inv_elmnt;
                        $quantity_get = $invoice_elemnt->quantity;

                        $value = ($pur_price * $margin_invoice) / 100;
                        $final_price = $pur_price + $value;

                        $total_price = $final_price * $quantity_get;

                        DB::table('invoice_elements')->where('id', $invoice_elemnt->id)
                        ->update([
                            'unit_price_inv_elmnt' => $final_price,
                            'total_price_inv_elmnt' => $total_price,
                        ]);
                    }

                    DB::table('invoice_margins')
                        ->where('id', $id_invoice_margin)
                        ->update([
                            'margin' => $article_margin_invoice,
                        ]);
                }
                else
                {
                    return redirect()->back()->with('danger', __('invoice.this_item_has_already_been_inserted'));
                }
            }
            //si c'est un service
            else
            {
                $service_exist = DB::table('invoice_elements')->where('ref_service', $article_sales_invoice)->first();

                if(!$service_exist)
                {
                    $invoice_el_select = InvoiceElement::create([
                        'ref_invoice' => $ref_invoice,
                        'ref_service' => $article_sales_invoice,
                        'description_inv_elmnt' => $descrption_saved_art,
                        'quantity' => $article_qty_invoice,
                        'purshase_price_inv_elmnt' => $total_price_inv_elmnt,
                        'unit_price_inv_elmnt' => $total_price_inv_elmnt,
                        'total_price_inv_elmnt' => $total_price_inv_elmnt,
                        'id_marge' => $id_invoice_margin,
                        'is_an_article' => 0,
                        'id_user' => Auth::user()->id,
                        'id_fu' => $id_fu,
                    ]);
                }
                else
                {
                    return redirect()->back()->with('danger', __('invoice.this_item_has_already_been_inserted'));
                }
            }
            
            return redirect()->back();
        }
        else
        {
            //si c'est un article
            if($is_an_article == 1)
            {
                DB::table('invoice_elements')->where('id', $id_invoice_element)
                ->update([
                    'ref_article' => $article_sales_invoice,
                    'description_inv_elmnt' => $descrption_saved_art,
                    'quantity' => $article_qty_invoice,
                    'purshase_price_inv_elmnt' => $article_purchase_price_invoice,
                    'unit_price_inv_elmnt' => $article_sale_price_invoice,
                    'total_price_inv_elmnt' => $total_price_inv_elmnt,
                    'id_marge' => $id_invoice_margin,
                ]);

                return redirect()->back();
            }
            else
            {

            }
        }
    }

    public function deleteInvoiceElement()
    {
        $id_invoice_element = $this->request->input('id_element');

        DB::table('invoice_elements')
                    ->where('id', $id_invoice_element)
                    ->delete();

        return redirect()->back();
    }
}
