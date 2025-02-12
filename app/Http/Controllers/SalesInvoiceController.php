<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNoteForm;
use App\Http\Requests\SaveSaleInvoiceForm;
use App\Models\Decaissement;
use App\Models\Encaissement;
use App\Models\Entrance;
use App\Models\Entreprise;
use App\Models\InvoiceElement;
use App\Models\InvoiceMargin;
use App\Models\NoteDocument;
use App\Models\PaymentTermsAssign;
use App\Models\SalesInvoice;
use App\Models\SerialNumberInvoice;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use App\Services\Email\Email;
use App\Services\Reference\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesInvoiceController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;
    protected $generateReferenceNumber;
    protected $email;

    function __construct(Request $request,
                            EntrepriseRepo $entrepriseRepo,
                            NotificationRepo $notificationRepo,
                            GenerateRefenceNumber $generateReferenceNumber,
                            Email $email)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
        $this->generateReferenceNumber = $generateReferenceNumber;
        $this->email = $email;
    }

    public function salesInvoice($group, $id, $id2)
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
                        ->where([
                            'sales_invoices.id_fu' => $functionalUnit->id,
                            'sales_invoices.is_simple_invoice' => 1,
                        ])
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('invoice_sales.sales_invoice', compact('entreprise', 'functionalUnit', 'deviseGest', 'invoices', 'permission_assign'));
    }

    public function setUpInvoice()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');
        $is_proforma = $this->request->input('is_proforma');
        $is_client_specific_invoice = $this->request->input('is_client_specific_invoice');
        $is_delivery_note = $this->request->input('is_delivery_note');
        $id_client = $this->request->input('id_client');

        if(Session::has('id_client') ||
            Session::has('entreprise_client') ||
            Session::has('id_contact') ||
            Session::has('fullname_contact') ||

            Session::has('date_sales_invoice') ||
            Session::has('due_date_sales_invoice')
        )
        {
            Session::forget('id_client');
            Session::forget('entreprise_client');
            Session::forget('id_contact');
            Session::forget('fullname_contact');
            Session::forget('date_sales_invoice');
            Session::forget('due_date_sales_invoice');
            Session::forget('invoice_concern_sales');
        }

        /**
         * si la création de la facture a été declenché
         * à partir de la section info client
         */
        if($is_client_specific_invoice != 0 && $id_client != 0)
        {
            $client = DB::table('clients')->where('id', $id_client)->first();
            $contact = DB::table('customer_contacts')->where('id_client', $id_client)->first();

            $this->request->session()->put('id_client', $client->id);
            $client->type == 'particular'
                ? $this->request->session()->put('entreprise_client', $contact->fullname_cl)
                : $this->request->session()->put('entreprise_client', $client->entreprise_name_cl);

            $this->request->session()->put('id_contact', $contact->id);
            $this->request->session()->put('fullname_contact', $contact->fullname_cl);
        }

        if($is_proforma == 1)
        {
            $invoice_margin_not_saved = DB::table('invoice_margins')
            ->where([
                'invoice_saved' => 0,
                'is_proforma' => 1,
                'is_simple_invoice_inv' => 0,
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
                    'is_proforma' => $is_proforma,
                    'is_simple_invoice_inv' => 0,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_functionalUnit,
                ]);

                return redirect()->route('app_add_new_proforma', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $refInvoice,
                ]);
            }
            else
            {
                return redirect()->route('app_add_new_proforma', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $invoice_margin_not_saved->ref_invoice,
                ]);
            }
        }
        else if($is_delivery_note == 1)
        {
            $invoice_margin_not_saved = DB::table('invoice_margins')
            ->where([
                'invoice_saved' => 0,
                'is_delivery_note_marge' => 1,
                'is_simple_invoice_inv' => 0,
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
                    'is_delivery_note_marge' => $is_delivery_note,
                    'is_simple_invoice_inv' => 0,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_functionalUnit,
                ]);

                return redirect()->route('app_add_new_delivery_note', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $refInvoice,
                ]);
            }
            else
            {
                return redirect()->route('app_add_new_delivery_note', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $invoice_margin_not_saved->ref_invoice,
                ]);
            }

        }
        else
        {
            $invoice_margin_not_saved = DB::table('invoice_margins')
            ->where([
                'invoice_saved' => 0,
                'is_proforma' => 0,
                'is_delivery_note_marge' => 0,
                'is_simple_invoice_inv' => 1,
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
                    'is_proforma' => $is_proforma,
                    'is_delivery_note_marge' => 0,
                    'is_simple_invoice_inv' => 1,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_functionalUnit,
                ]);

                return redirect()->route('app_add_new_sales_invoice', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $refInvoice,
                ]);
            }
            else
            {
                return redirect()->route('app_add_new_sales_invoice', [
                    'group' => 'sale',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_invoice' => $invoice_margin_not_saved->ref_invoice,
                ]);
            }
        }

    }

    public function addNewSalesInvoice($group, $id, $id2, $ref_invoice)
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
                            'id_fu' => $id2,
                        ])->first();

        $invoice_elements = DB::table('invoice_elements')
                            ->where([
                                'ref_invoice' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->get();

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();

        /**
         * On génère une référence pour chaque user
         * par exemple
         * IK/001/2024
         */
        $reference = new Reference(Auth::user()->name);
        $reference_personalized = $reference->getInvoiceReference();

        if($invoice)
        {
            $client = DB::table('clients')->where('id', $invoice->id_client)->first();
            $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

            $this->request->session()->put('id_client', $client->id);
            $client->type == 'particular'
                ? $this->request->session()->put('entreprise_client', $contact->fullname_cl)
                : $this->request->session()->put('entreprise_client', $client->entreprise_name_cl);

            $this->request->session()->put('id_contact', $contact->id);
            $this->request->session()->put('fullname_contact', $contact->fullname_cl);
            $this->request->session()->put('invoice_concern_sales', $invoice->concern_invoice);

            $date = $invoice->created_at;
            $due_date = $invoice->due_date;

            $this->request->session()->put('date_sales_invoice', date('Y-m-d', strtotime($date)));
            $this->request->session()->put('due_date_sales_invoice', date('Y-m-d', strtotime($due_date)));

            $reference_personalized = $invoice->reference_personalized;
        }

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        $payment_terms_proforma = null;
        $payment_terms_assign = null;

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
            'permission_assign',
            'reference_personalized',
            'invoice',
            'payment_terms_proforma',
            'payment_terms_assign',
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

        $article_sales_invoice = $this->request->input('article_sales_invoice');
        $article_qty_invoice = $this->request->input('article_qty_invoice');
        $article_margin_invoice = $this->request->input('article_margin_invoice');
        $article_purchase_price_invoice = $this->request->input('article_purchase_price_invoice');
        $article_sale_price_invoice = $this->request->input('article_sale_price_invoice');
        $total_price_inv_elmnt = $this->request->input('article_total_price_invoice');
        $is_an_article = $this->request->input('is_an_article');

        $id_customer_session_art = $this->request->input('id_customer_session_art');
        $id_contact_session_art = $this->request->input('id_contact_session_art');
        $concerne_session = $this->request->input('concerne_session');
        $article_reference_invoice = $this->request->input('article_reference_invoice');

        //dd($article_reference_invoice);

        if($id_customer_session_art != 0 && $id_contact_session_art != 0)
        {
            $client = DB::table('clients')->where('id', $id_customer_session_art)->first();
            $contact = DB::table('customer_contacts')->where('id', $id_contact_session_art)->first();

            $this->request->session()->put('id_client', $client->id);

            $client->type == "particular"
                ? $this->request->session()->put('entreprise_client', $contact->fullname_cl)
                : $this->request->session()->put('entreprise_client', $client->entreprise_name_cl);

            $this->request->session()->put('id_contact', $contact->id);
            $this->request->session()->put('fullname_contact', $contact->fullname_cl);
        }

        if($concerne_session != "")
        {
            $this->request->session()->put('invoice_concern_sales', $concerne_session);
        }

        //dd($this->request->all());

        //dd($modalRequest);

        if($modalRequest != "edit")
        {
            //si c'est un article
            if($is_an_article == 1)
            {
                $article_exist = DB::table('invoice_elements')
                    ->where([
                        'ref_article' => $article_sales_invoice,
                        'ref_invoice' => $ref_invoice,
                    ])->first();

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
                        'margin' => $article_margin_invoice,
                        'custom_reference' => $article_reference_invoice
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

                    /* foreach($get_invoice_exist as $invoice_elemnt)
                    {
                        $pur_price = $invoice_elemnt->purshase_price_inv_elmnt;
                        $quantity_get = $invoice_elemnt->quantity;

                        $value = ($pur_price * $article_margin_invoice) / 100;
                        $final_price = $pur_price + $value;

                        $total_price = $final_price * $quantity_get;

                        DB::table('invoice_elements')->where('id', $invoice_elemnt->id)
                        ->update([
                            'unit_price_inv_elmnt' => $final_price,
                            'total_price_inv_elmnt' => $total_price,
                        ]);
                    }
                        */
                }
                else
                {
                    return redirect()->back()->with('danger', __('invoice.this_item_has_already_been_inserted'));
                }
            }
            //si c'est un service
            else
            {
                $service_exist = DB::table('invoice_elements')
                                    ->where([
                                        'ref_service' => $article_sales_invoice,
                                        'ref_invoice' => $ref_invoice,
                                    ])->first();

                if(!$service_exist)
                {
                    $invoice_el_select = InvoiceElement::create([
                        'ref_invoice' => $ref_invoice,
                        'ref_service' => $article_sales_invoice,
                        'description_inv_elmnt' => $descrption_saved_art,
                        'quantity' => $article_qty_invoice,
                        'purshase_price_inv_elmnt' => $article_sale_price_invoice,
                        'unit_price_inv_elmnt' => $article_sale_price_invoice,
                        'total_price_inv_elmnt' => $total_price_inv_elmnt,
                        'id_marge' => $id_invoice_margin,
                        'is_an_article' => 0,
                        'id_user' => Auth::user()->id,
                        'id_fu' => $id_fu,
                        'custom_reference' => $article_reference_invoice
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

                //dd($id_invoice_element);

                DB::table('invoice_elements')->where('id', $id_invoice_element)
                ->update([
                    'ref_article' => $article_sales_invoice,
                    'quantity' => $article_qty_invoice,
                    'purshase_price_inv_elmnt' => $article_purchase_price_invoice,
                    'unit_price_inv_elmnt' => $article_sale_price_invoice,
                    'total_price_inv_elmnt' => $total_price_inv_elmnt,
                    'id_marge' => $id_invoice_margin,
                    'margin' => $article_margin_invoice,
                    'custom_reference' => $article_reference_invoice,
                ]);

                return redirect()->back();
            }
            else
            {
                DB::table('invoice_elements')->where('id', $id_invoice_element)
                ->update([
                    'ref_article' => $article_sales_invoice,
                    'quantity' => $article_qty_invoice,
                    'purshase_price_inv_elmnt' => $article_sale_price_invoice,
                    'unit_price_inv_elmnt' => $article_sale_price_invoice,
                    'total_price_inv_elmnt' => $total_price_inv_elmnt,
                    'id_marge' => $id_invoice_margin,
                    'custom_reference' => $article_reference_invoice
                ]);

                return redirect()->back();
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

    public function saveSaleInvoice(SaveSaleInvoiceForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_invoice = $requestF->input('id_invoice');
        $ref_invoice = $requestF->input('ref_invoice');
        $customerRequest = $requestF->input('customerRequest');
        $client_sales_invoice = $requestF->input('client_sales_invoice');
        $discount_choise = $requestF->input('discount_choise');
        $discount_set = $requestF->input('discount_set');
        $discount_value = $requestF->input('discount_value');
        $vat_apply_change = $requestF->input('vat-apply-change');
        $tot_excl_tax = $requestF->input('tot_excl_tax');
        $discount_apply_input = $requestF->input('discount_apply_input');
        $vat_apply_input = $requestF->input('vat_apply_input');
        $tot_incl_tax_input = $requestF->input('tot_incl_tax_input');
        $amount_received = $requestF->input('amount_received');
        $date_sales_invoice = $requestF->input('date_sales_invoice');
        $due_date_sales_invoice = $requestF->input('due_date_sales_invoice');
        $is_proforma = $requestF->input('is_proforma');
        $client_contact_sales_invoice = $requestF->input('client_contact_sales_invoice');
        $is_delivery_note_marge = $requestF->input('is_delivery_note_marge');
        $is_simple_invoice_inv = $requestF->input('is_simple_invoice_inv');

        $invoice_concern_sales = $requestF->input('invoice_concern_sales');
        //$ref_personalized = $requestF->input('invoice_concern_sales');
        $validity_of_the_offer = $requestF->input('validity_of_the_offer');
        $payment_terms = $requestF->input('payment_terms'); //to_order or after_delivery
        $payment_terms_percentage = $requestF->input('payment_terms_percentage');
        $after_delivery_days = $requestF->input('after_delivery_days');


        if($is_proforma == 1)
        {
            $payment_terms_proformas = DB::table('payment_terms_proformas')->where('description', $payment_terms)->first();
            PaymentTermsAssign::create([
                'ref_invoice' => $ref_invoice,
                'id_payment_terms' => $payment_terms_proformas->id,
                'purcentage' => $payment_terms_percentage,
                'day_number' => $after_delivery_days,
            ]);
        }
        else
        {
            $validity_of_the_offer = 15;
        }


        $reference_personalized = "";

        if($is_simple_invoice_inv == 1)
        {
            $reference = new Reference(Auth::user()->name);
            $reference_personalized = $reference->getInvoiceReference();
        }
        if($is_proforma == 1)
        {
            $reference = new Reference(Auth::user()->name);
            $reference_personalized = $reference->getProformaReference();
        }
        if($is_delivery_note_marge == 1)
        {
            $reference = new Reference(Auth::user()->name);
            $reference_personalized = $reference->getDeliveryReference();
        }


        $time = date('H:i:s');

        $choise_dist = 0;
        $discount_choise == "yes" ? $choise_dist = 1 : $choise_dist = 0;

        $date_invoice = date('Y-m-d H:i:s',strtotime($date_sales_invoice.' '.$time));
        $due_date = date('Y-m-d H:i:s',strtotime($due_date_sales_invoice.' '.$time));

        //dd($requestF->all());

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();

        $invoice_elmnt = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->first();

        //variable de session
        $this->request->session()->put('date_sales_invoice', $date_sales_invoice);
        $this->request->session()->put('due_date_sales_invoice', $due_date_sales_invoice);
        $this->request->session()->put('invoice_concern_sales', $invoice_concern_sales);

        if($invoice_elmnt) //si au moins un element est inséré dans la facture
        {
            if(!$invoice)
            {
                SalesInvoice::create([
                    'reference_sales_invoice' => $ref_invoice,
                    'reference_number' => 0,
                    'concern_invoice' => $invoice_concern_sales,
                    'discount_choice' => $choise_dist,
                    'discount_type' => $discount_set,
                    'discount_value' => $discount_value,
                    'sub_total' => $tot_excl_tax,
                    'total' => $tot_incl_tax_input,
                    'vat_amount' => $vat_apply_input,
                    'amount_received' => $amount_received,
                    'vat' => $vat_apply_change,
                    'discount_apply_amount' => $discount_apply_input,
                    'id_client' => $client_sales_invoice,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_fu,
                    'id_contact' => $client_contact_sales_invoice,
                    'created_at' => $date_invoice,
                    'due_date' => $date_invoice,
                    'is_proforma_inv' => $is_proforma,
                    'is_delivery_note' => $is_delivery_note_marge,
                    'is_simple_invoice' => $is_simple_invoice_inv,
                    'reference_personalized' => $reference_personalized,
                    'validity_of_the_offer_day' => $validity_of_the_offer
                ]);

                DB::table('invoice_margins')
                    ->where('ref_invoice', $ref_invoice)
                    ->update([
                        'invoice_saved' => 1,
                        'updated_at' => new \DateTimeImmutable,
                ]);

                if($is_proforma == 1 && $is_delivery_note_marge == 0)
                {
                    //Notification
                    $url = route('app_info_proforma', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.added_a_new_proforma_invoice_in_the_functional_unit";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_proforma', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.proforma_invoice_added_successfully'));
                }
                else if($is_delivery_note_marge == 1 && $is_proforma == 0)
                {
                    //Notification
                    $url = route('app_info_delivery_note', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.added_a_delivery_note";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_delivery_note', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.delivery_note_added_successfully'));
                }
                else
                {
                    //Notification
                    $url = route('app_info_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.added_an_invoice_in_the_functional_unit";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.the_invoice_was_added_successfully'));
                }
            }
            else
            {
                DB::table('sales_invoices')
                    ->where('reference_sales_invoice', $ref_invoice)
                    ->update([
                        'discount_choice' => $choise_dist,
                        'concern_invoice' => $invoice_concern_sales,
                        'discount_type' => $discount_set,
                        'discount_value' => $discount_value,
                        'sub_total' => $tot_excl_tax,
                        'total' => $tot_incl_tax_input,
                        'vat_amount' => $vat_apply_input,
                        'amount_received' => $amount_received,
                        'vat' => $vat_apply_change,
                        'discount_apply_amount' => $discount_apply_input,
                        'id_client' => $client_sales_invoice,
                        'id_fu' => $id_fu,
                        'id_contact' => $client_contact_sales_invoice,
                        'created_at' => $date_invoice,
                        'due_date' => $date_invoice,
                        'updated_at' => new \DateTimeImmutable,
                        'is_proforma_inv' => $is_proforma,
                        'is_delivery_note' => $is_delivery_note_marge,
                        'is_simple_invoice' => $is_simple_invoice_inv,
                        'validity_of_the_offer_day' => $validity_of_the_offer
                ]);

                if($is_proforma == 1 && $is_delivery_note_marge == 0)
                {
                    //Notification
                    $url = route('app_info_proforma', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.modified_a_proforma_invoice_in_the_functional_unit";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_proforma', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.the_proforma_invoice_has_been_successfully_modified'));


                }
                else if($is_delivery_note_marge == 1 && $is_proforma == 0)
                {
                    //Notification
                    $url = route('app_info_delivery_note', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.modified_a_delivery_note";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_delivery_note', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.delivery_note_modified_successfully'));
                }
                else
                {
                    //Notification
                    $url = route('app_info_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
                    $description = "invoice.modified_an_invoice_in_the_functional_unit";
                    $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                    return redirect()->route('app_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu ])
                        ->with('success', __('invoice.the_invoice_was_successfully_modified'));
                }
            }
        }
        else
        {
            return redirect()->back()
                ->with('danger', __('invoice.please_include_at_least_one_item_in_the_invoice'))
                ->withInput();
        }
    }

    public function infoSalesInvoice($group, $id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();

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

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $paymentReceived = DB::table('encaissements')
                            ->where([
                                'reference_enc' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->sum('amount');


        $remainingBalance = $invoice->total - $paymentReceived;


        $encaissements = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                        ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where([
                            'encaissements.reference_enc' => $ref_invoice,
                            'encaissements.id_fu' => $id2,
                        ])->get();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                            ->where([
                                'payment_methods.id_fu' => $functionalUnit->id,
                                'devises.iso_code' => $deviseGest->iso_code,
                            ])->get();

        //dd($paymentMethods);

        $purchases = null;

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        $type_doc = "invoice";

        $notes = DB::table('note_documents')->where('reference_doc', $ref_invoice)->get();

        return view('invoice_sales.info_sales_invoice', compact(
            'entreprise',
            'functionalUnit',
            'invoice',
            'ref_invoice',
            'customer',
            'country',
            'invoice_elements',
            'deviseGest',
            'tot_excl_tax',
            'encaissements',
            'paymentReceived',
            'remainingBalance',
            'paymentMethods',
            'contact',
            'purchases',
            'permission_assign',
            'type_doc',
            'notes'
        ));
    }

    public function checkRecordsAmountInvoice()
    {
        $ref_invoice = $this->request->input('ref_invoice');
        $amount = $this->request->input('amount');
        $id_fu = $this->request->input('id_fu');
        $type_record = $this->request->input('type_record');

        $remainingBalance = 0;
        $result = "";

        if($type_record == "cash_in") //encaissement
        {
            $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();

            $paymentReceived = DB::table('encaissements')
                                ->where([
                                    'reference_enc' => $ref_invoice,
                                    'id_fu' => $id_fu,
                                ])->sum('amount');

            $remainingBalance = $invoice->total - $paymentReceived;

            if($amount <= $remainingBalance)
            {
                $result = "success";
            }
            else
            {
                $result = "danger";
            }

        }
        else //décaissement
        {
            $purchase = DB::table('purchases')->where('reference_purch', $ref_invoice)->first();

            $paymentReceived = DB::table('decaissements')
                                ->where([
                                    'reference_dec' => $ref_invoice,
                                    'id_fu' => $id_fu,
                                ])->sum('amount');

            $remainingBalance = $purchase->amount - $paymentReceived;

            if($amount <= $remainingBalance)
            {
                $result = "success";
            }
            else
            {
                $result = "danger";
            }
        }

        return response()->json([
            'code' => 200,
            'amount' => $amount,
            'remainingBalance' => $remainingBalance,
            'result' => $result,
        ]);
    }

    public function saveRecordPayment()
    {
        $ref_invoice = $this->request->input('ref_invoice');
        $amount = $this->request->input('amount_invoice_record');
        $payment_method = $this->request->input('payment_methods_invoice_record');
        $id_fu = $this->request->input('id_fu');
        $id_entreprise = $this->request->input('id_entreprise');
        $type_record = $this->request->input('type_record');

        if($type_record == "cash_in")
        {
            Encaissement::create([
                'description' => 'invoice.collection_of_the_invoice',
                'reference_enc' => $ref_invoice,
                'is_invoice' => 1,
                'amount' => $amount,
                'id_pay_meth' => $payment_method,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            //Notification
            $url = route('app_info_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_invoice' => $ref_invoice]);
            $description = "invoice.recorded_an_invoice_payment";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);
        }
        else
        {
            Decaissement::create([
                'description' => 'expenses.supplier_payment_expense',
                'reference_dec' => $ref_invoice,
                'is_purchase' => 1,
                'amount' => $amount,
                'id_pay_meth' => $payment_method,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            //Notification
            $url = route('app_update_purchase', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_purchase' => $ref_invoice]);
            $description = "expenses.recorded_a_supplier_payment";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);
        }


        return redirect()->back()
            ->with('success', __('invoice.payment_registered_successfully'));
    }

    public function deleteSalesInvoice()
    {
        $ref_invoice = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $is_proforma = $invoice->is_proforma_inv;
        $is_delivery_note = $invoice->is_delivery_note;
        $is_simple_invoice = $invoice->is_simple_invoice;

        DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->delete();

        if($is_proforma == 1)
        {
            //Notification
            $url = route('app_proforma', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "invoice.deleted_a_proforma_invoice";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_proforma', [
                'group' => 'sale',
                'id' => $id_entreprise,
                'id2' => $id_fu ])->with('success', __('invoice.proforma_invoice_successfully_deleted'));
        }
        else if($is_delivery_note == 1)
        {
            //Notification
            $url = route('app_delivery_note', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "invoice.deleted_a_delivery_note";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_delivery_note', [
                'group' => 'sale',
                'id' => $id_entreprise,
                'id2' => $id_fu ])->with('success', __('invoice.delivery_note_successfully_deleted'));
        }
        else
        {
            //Notification
            $url = route('app_sales_invoice', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "invoice.deleted_an_invoice_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_sales_invoice', [
                'group' => 'sale',
                'id' => $id_entreprise,
                'id2' => $id_fu ])->with('success', __('invoice.invoice_deleted_successfully'));
        }

    }

    public function proforma($group, $id, $id2)
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
                        ->where([
                            'sales_invoices.id_fu' => $functionalUnit->id,
                            'sales_invoices.is_proforma_inv' => 1,
                        ])
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('invoice_sales.proforma.proforma', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'invoices',
            'permission_assign',
        ));
    }

    public function addNewProforma($group, $id, $id2, $ref_invoice)
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
                            'is_proforma' => 1,
                            'id_fu' => $id2,
                        ])->first();

        $invoice_elements = DB::table('invoice_elements')
                            ->where([
                                'ref_invoice' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->get();

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();

        /**
         * On génère une référence pour chaque user
         * par exemple
         * IK/001/2024
         */
        $reference = new Reference(Auth::user()->name);
        $reference_personalized = $reference->getInvoiceReference();

        if($invoice)
        {
            $client = DB::table('clients')->where('id', $invoice->id_client)->first();
            $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

            $this->request->session()->put('id_client', $client->id);
            $client->type == 'particular'
                ? $this->request->session()->put('entreprise_client', $contact->fullname_cl)
                : $this->request->session()->put('entreprise_client', $client->entreprise_name_cl);

            $this->request->session()->put('id_contact', $contact->id);
            $this->request->session()->put('fullname_contact', $contact->fullname_cl);
            $this->request->session()->put('invoice_concern_sales', $invoice->concern_invoice);

            $date = $invoice->created_at;
            $due_date = $invoice->due_date;

            $this->request->session()->put('date_sales_invoice', date('Y-m-d', strtotime($date)));
            $this->request->session()->put('due_date_sales_invoice', date('Y-m-d', strtotime($due_date)));

            $reference_personalized = $invoice->reference_personalized;
        }

        $payment_terms_assign = DB::table('payment_terms_assigns')
            ->where('ref_invoice', $ref_invoice)->first();

        $payment_terms_proforma = null;
        if($payment_terms_assign)
        {
            $payment_terms_proforma = DB::table('payment_terms_proformas')
                ->where('id', $payment_terms_assign->id_payment_terms)->first();
        }

        return view('invoice_sales.proforma.add_new_proforma', compact(
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
            'permission_assign',
            'reference_personalized',
            'invoice',
            'payment_terms_proforma',
            'payment_terms_assign',
        ));
    }

    public function infoProforma($group, $id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();

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

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $paymentReceived = DB::table('encaissements')
                            ->where([
                                'reference_enc' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->sum('amount');


        $remainingBalance = $invoice->total - $paymentReceived;


        $encaissements = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                        ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where([
                            'encaissements.reference_enc' => $ref_invoice,
                            'encaissements.id_user' => Auth::user()->id,
                            'encaissements.id_fu' => $id2,
                        ])->get();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                            ->where([
                                'payment_methods.id_fu' => $functionalUnit->id,
                                'devises.iso_code' => $deviseGest->iso_code,
                            ])->get();

        //dd($paymentMethods);

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        $payment_terms_assign = DB::table('payment_terms_assigns')
                ->where('ref_invoice', $ref_invoice)->first();

        $payment_terms_proforma = null;
        if($payment_terms_assign)
        {
            $payment_terms_proforma = DB::table('payment_terms_proformas')
                ->where('id', $payment_terms_assign->id_payment_terms)->first();
        }

        $type_doc = "proforma";

        $notes = DB::table('note_documents')->where('reference_doc', $ref_invoice)->get();

        return view('invoice_sales.proforma.info_proforma', compact(
            'entreprise',
            'functionalUnit',
            'invoice',
            'ref_invoice',
            'customer',
            'country',
            'invoice_elements',
            'deviseGest',
            'tot_excl_tax',
            'encaissements',
            'paymentReceived',
            'remainingBalance',
            'paymentMethods',
            'contact',
            'permission_assign',
            'payment_terms_assign',
            'payment_terms_proforma',
            'type_doc',
            'notes'
        ));
    }

    public function transformInvoiceSimple()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');
        $ref_invoice = $this->request->input('ref_invoice');

        DB::table('sales_invoices')
            ->where('reference_sales_invoice', $ref_invoice)
            ->update([
                'is_proforma_inv' => 0,
                'is_simple_invoice' => 1,
                'updated_at' => new \DateTimeImmutable
        ]);

        DB::table('invoice_margins')
            ->where('ref_invoice', $ref_invoice)
            ->update([
                'is_proforma' => 0,
                'is_simple_invoice_inv' => 1,
                'updated_at' => new \DateTimeImmutable
        ]);

        return redirect()->route('app_info_sales_invoice', [
            'group' => 'sale',
            'id' => $id_entreprise,
            'id2' => $id_fu,
            'ref_invoice' => $ref_invoice
        ]);
    }

    public function getContactClientinvoice()
    {
        $id_client = $this->request->input('id_client');

        $contacts = DB::table('customer_contacts')->where('id_client', $id_client)->get();
        $contact_first = DB::table('customer_contacts')->where('id_client', $id_client)->first();

        return response()->json([
            'code' => 200,
            'status' => "success",
            'contacts' => $contacts,
            'contact_first' => $contact_first,
        ]);
    }

    public function deliveryNote($group, $id, $id2)
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
                        ->where([
                            'sales_invoices.id_fu' => $functionalUnit->id,
                            'sales_invoices.is_delivery_note' => 1,
                        ])
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('delivery_note.delivery_note', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'invoices',
            'permission_assign'
        ));
    }

    public function addNewDeliveryNote($group, $id, $id2, $ref_invoice)
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
                            //'is_delivery_note_marge' => 1,
                            'id_fu' => $id2,
                        ])->first();

        $invoice_elements = DB::table('invoice_elements')
                        ->where([
                            'ref_invoice' => $ref_invoice,
                            'id_fu' => $id2,
                        ])->get();

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $reference = new Reference(Auth::user()->name);
        $reference_personalized = $reference->getInvoiceReference();

        if($invoice)
        {
            $client = DB::table('clients')->where('id', $invoice->id_client)->first();
            $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

            $this->request->session()->put('id_client', $client->id);
            $client->type == 'particular'
                ? $this->request->session()->put('entreprise_client', $contact->fullname_cl)
                : $this->request->session()->put('entreprise_client', $client->entreprise_name_cl);

            $this->request->session()->put('id_contact', $contact->id);
            $this->request->session()->put('fullname_contact', $contact->fullname_cl);

            $date = $invoice->created_at;
            $due_date = $invoice->due_date;

            $this->request->session()->put('date_sales_invoice', date('Y-m-d', strtotime($date)));
            $this->request->session()->put('due_date_sales_invoice', date('Y-m-d', strtotime($due_date)));

            $reference_personalized = $invoice->reference_personalized;
        }

        //dd($invoice_margin);

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        /**
         * On génère une référence pour chaque user
         * par exemple
         * IK/001/2024
         */
        $reference = new Reference(Auth::user()->name);
        $reference_personalized = $reference->getDeliveryReference();

        $payment_terms_proforma = null;
        $payment_terms_assign = null;

        return view('delivery_note.add_new_delivery_note', compact(
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
            'permission_assign',
            'reference_personalized',
            'payment_terms_proforma',
            'payment_terms_assign',
        ));
    }

    public function addSerialNumberinvoice()
    {
        $id_invoice_element = $this->request->input('id_invoice_element_sn');
        $description = $this->request->input('serial_number_invoice');
        $modalRequest = $this->request->input('modalRequest');
        $id_serial_number_invoice = $this->request->input('id_serial_number_invoice');

        //dd($this->request->all());

        if($modalRequest != "edit")
        {
            SerialNumberInvoice::create([
                'description' => $description,
                'id_invoice_element' => $id_invoice_element,
            ]);

            return redirect()->back();
        }
        else
        {
            DB::table('serial_number_invoices')
                ->where('id', $id_serial_number_invoice)
                ->update([
                    'description' => $description,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            return redirect()->back();
        }
    }

    public function deleteSerialNumberInvoice()
    {
        $id_serial_number_invoice = $this->request->input('id_element');

        DB::table('serial_number_invoices')->where('id', $id_serial_number_invoice)->delete();

        return redirect()->back();
    }

    public function infoDeliveryNote($group, $id, $id2, $ref_invoice)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
        $customer = DB::table('clients')->where('id', $invoice->id_client)->first();
        $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();

        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();

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

        $tot_excl_tax = DB::table('invoice_elements')->where('ref_invoice', $ref_invoice)->sum('total_price_inv_elmnt');

        $paymentReceived = DB::table('encaissements')
                            ->where([
                                'reference_enc' => $ref_invoice,
                                'id_fu' => $id2,
                            ])->sum('amount');


        $remainingBalance = $invoice->total - $paymentReceived;


        $encaissements = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                        ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where([
                            'encaissements.reference_enc' => $ref_invoice,
                            'encaissements.id_user' => Auth::user()->id,
                            'encaissements.id_fu' => $id2,
                        ])->get();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                            ->where([
                                'payment_methods.id_fu' => $functionalUnit->id,
                                'devises.iso_code' => $deviseGest->iso_code,
                            ])->get();

        //dd($paymentMethods);

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        $type_doc = "delivery_note";

        $notes = DB::table('note_documents')
                ->where([
                    'reference_doc' => $ref_invoice,
                    'type_doc' => $type_doc
                ])->get();

        return view('delivery_note.info_delivery_note', compact(
            'entreprise',
            'functionalUnit',
            'invoice',
            'ref_invoice',
            'customer',
            'country',
            'invoice_elements',
            'deviseGest',
            'tot_excl_tax',
            'encaissements',
            'paymentReceived',
            'remainingBalance',
            'paymentMethods',
            'contact',
            'permission_assign',
            'type_doc',
            'notes'
        ));
    }

    public function generateDeliveryNote()
    {
        $ref_invoice = $this->request->input('ref_invoice');
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');

        DB::table('sales_invoices')
            ->where('reference_sales_invoice', $ref_invoice)
            ->update([
                'is_delivery_note' => 1,
                'updated_at' => new \DateTimeImmutable,
        ]);

        return redirect()->route('app_info_delivery_note', [
            'group' => 'sale',
            'id' => $id_entreprise,
            'id2' => $id_functionalUnit,
            'ref_invoice' => $ref_invoice
        ]);
    }

    public function entrances($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $entrances = DB::table('entrances')->where('id_fu', $functionalUnit->id)->orderBy('id', 'desc')->get();
        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('invoice_sales.entrances', compact(
            'entreprise',
            'functionalUnit',
            'entrances',
            'deviseGest',
            'permission_assign'
        ));
    }

    public function setup_enrance()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');

        $reference_entrance = "ENTR" . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . Auth::user()->id;

        return redirect()->route('app_add_new_entrance', [
            'group' => 'sale',
            'id' => $id_entreprise,
            'id2' => $id_functionalUnit,
            'ref_entrance' => $reference_entrance,
        ]);
    }

    public function add_new_entrance($group, $id, $id2, $ref_entrance)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $deviseGestUfs = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
        ])->get();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                            ->where([
                                'payment_methods.id_fu' => $functionalUnit->id,
                            ])->get();

        $entrance = DB::table('entrances')->where('reference_entr', $ref_entrance)->first();

        $encaissement = DB::table('encaissements')->where('reference_enc', $ref_entrance)->first();

        $paymentMeth = null;

        if($encaissement)
        {
            $paymentMeth = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                    ->where([
                        'payment_methods.id' => $encaissement->id_pay_meth,
                    ])->first();
        }

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('invoice_sales.add_new_entrance', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'deviseGestUfs',
            'paymentMethods',
            'entrance',
            'ref_entrance',
            'encaissement',
            'paymentMeth',
            'permission_assign'
        ));
    }


    public function save_entrance()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');
        $reference_entr = $this->request->input('reference_entrance');
        $customerRequest = $this->request->input('customerRequest');
        $description_entr = $this->request->input('description_entr');
        $currency_exp = $this->request->input('currency_exp');
        $amount_entrance = $this->request->input('amount_entrance');
        $pay_method_entr = $this->request->input('pay_method_entr');
        $date_entrance = $this->request->input('date_entrance');

        if($customerRequest != "edit")
        {
            Entrance::create([
                'description' => $description_entr,
                'reference_entr' => $reference_entr,
                'amount' => $amount_entrance,
                'created_at' => $date_entrance,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            Encaissement::create([
                'description' => 'invoice.collection',
                'reference_enc' => $reference_entr,
                'is_invoice' => 0,
                'amount' => $amount_entrance,
                'id_pay_meth' => $pay_method_entr,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            //Notification
            $url = route('app_entrances', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "invoice.recorded_an_entrance";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_entrances', [
                'id' => $id_entreprise,
                'id2' => $id_fu,
            ])->with('success', __('invoice.entrance_recorded_successfully'));
        }
        else
        {
            DB::table('entrances')
                ->where('reference_entr', $reference_entr)
                ->update([
                    'description' => $description_entr,
                    'amount' => $amount_entrance,
                    'created_at' => $date_entrance,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_fu,
                ]);

            DB::table('encaissements')
                ->where('reference_enc', $reference_entr)
                ->update([
                    'amount' => $amount_entrance,
                    'id_pay_meth' => $pay_method_entr,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_fu,
                ]);

            //Notification
            $url = route('app_entrances', ['group' => 'sale', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "invoice.updated_an_entrance";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_entrances', [
                'id' => $id_entreprise,
                'id2' => $id_fu,
            ])->with('success', __('invoice.entrance_updated_successfully'));
        }
    }

    public function add_note_invoice(AddNoteForm $requestF)
    {
        //dd($requestF->all());

        $id_note = $requestF->input('id_note');
        $type_doc = $requestF->input('type_doc'); //invoice, proforma,...
        $type_note = $requestF->input('type_note'); //list, sentence
        $bold_note = $requestF->input('bold_note'); //on or nothing
        $italic_note = $requestF->input('italic_note');
        $note_content = $requestF->input('note_content');
        $reference_doc = $requestF->input('reference_doc');
        $customerRequest = $requestF->input('customerRequest');

        if($customerRequest != "edit")
        {
            NoteDocument::create([
                'type_doc' => $type_doc,
                'type_note' => $type_note,
                'bold_note' => $bold_note,
                'italic_note' => $italic_note,
                'note_content' => $note_content,
                'reference_doc' => $reference_doc,
            ]);

            return redirect()->back()->with('success', __('invoice.note_added_successfully'));
        }
        else
        {
            DB::table('note_documents')
                ->where('id', $id_note)
                ->update([
                    'type_doc' => $type_doc,
                    'type_note' => $type_note,
                    'bold_note' => $bold_note,
                    'italic_note' => $italic_note,
                    'note_content' => $note_content,
                    'reference_doc' => $reference_doc,
                ]);

            return redirect()->back()->with('success', __('invoice.note_updated_successfully'));
        }
    }

    public function delete_note_invoice()
    {
        $id_note = $this->request->input('id_element');

        //dd($this->request->all());

        DB::table('note_documents')
            ->where('id', $id_note)
            ->delete();

        return redirect()->back()->with('success', __('invoice.note_deleted_successfully'));
    }

    public function signature($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('invoice_sales.signature', compact('entreprise', 'functionalUnit'));
    }

    public function seal($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('invoice_sales.seal', compact('entreprise', 'functionalUnit'));
    }

    public function send_email_invoice()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');
        $ref_invoice = $this->request->input('ref_invoice');
        $from_email = $this->request->input('from-email');
        $to_email = $this->request->input('to-email');
        $concern_email = $this->request->input('concern-email');
        $greeting = $this->request->input('greeting');
        $recipient_name = $this->request->input('recipient_name');
        $message_email = $this->request->input('message-email');

        //dd($this->request->all());

        $env = config('app.server');
        $serverName = $this->request->server('SERVER_NAME');
        $serverPort = $this->request->server('SERVER_PORT');

        $entreprise = Entreprise::where('id', $id_entreprise)->first();

        $url = "";

        if($env == "local")
        {
            //$image_path = $public_folder . '/public/assets/img/logo/entreprise/' . $entreprise->url_logo . '.png';
            $url = "http://" . $serverName . ':' . $serverPort . '/invoice_pdf' . '/' . $id_entreprise . '/' . $id_fu . '/' . $ref_invoice;
        }
        else
        {
            //$image_path = '/assets/img/logo/entreprise/' . $entreprise->url_logo . '.png';
            $url = "https://" . $serverName . '/invoice_pdf' . '/' . $id_entreprise . '/' . $id_fu . '/' . $ref_invoice;
        }

        $this->email->send_email_invoice($from_email, $to_email, $concern_email, $greeting, $recipient_name, $message_email, $url, $entreprise->name);

        return redirect()->back()->with('success', __('invoice.invoice_sent_successfully'));
    }
}
