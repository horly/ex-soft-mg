<?php

namespace App\Http\Controllers;

use App\Models\PurchaseMargin;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExpensesController extends Controller
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

    public function purchases($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $purchases = DB::table('purchases')->where('id_fu', $functionalUnit->id)->get();
        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        return view('expenses.purchase', compact(
            'entreprise',
            'functionalUnit',
            'purchases',
            'deviseGest',
        ));
    }

    public function setUpPurchase()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');
        $is_simple_purchase = $this->request->input('is_simple_purchase'); 
        $is_purchase_order = $this->request->input('is_purchase_order'); 
        $is_specific_supplier = $this->request->input('is_specific_supplier');
        $id_supplier = $this->request->input('id_supplier');

        if(Session::has('id_supplier') || 
        Session::has('entreprise_supplier') ||
        
        Session::has('date_purcharse') ||
        Session::has('due_date_purcharse')
        )
        {
            Session::forget('id_supplier');
            Session::forget('entreprise_supplier');
            Session::forget('date_purcharse');
            Session::forget('due_date_purcharse');
        }

        /**
         * si la création de l'achat a été declenché 
         * à partir de la section info supplier
         */
        if($is_specific_supplier != 0) 
        {
            $supplier = DB::table('suppliers')->where('id', $id_supplier)->first();

            $this->request->session()->put('id_supplier', $supplier->id);

            $supplier->type_sup == 'particular' 
                ? $this->request->session()->put('entreprise_supplier', $supplier->contact_name_sup)
                : $this->request->session()->put('entreprise_supplier', $supplier->entreprise_name_sup);  
        }

        if($is_simple_purchase == 1)
        {
            $purchase_margin_not_saved = DB::table('purchase_margins')
            ->where([
                'purchase_saved' => 0,
                'is_simple_purchase' => 1,
                'is_purchase_order' => 0,
                'id_fu' => $id_functionalUnit,
            ])->first();

            if(!$purchase_margin_not_saved)
            {
                /**
                 * Format N° de référence de la facture :  
                 * INV année mois jour heure minute seconde id_user
                 * INV2023121509093023
                 */
                $refPurchase = "PUR" . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . Auth::user()->id;

                PurchaseMargin::create([
                    'ref_purchase' => $refPurchase,
                    'is_simple_purchase' => $is_simple_purchase,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_functionalUnit,
                ]);

                return redirect()->route('app_add_new_purchase', [
                    'id' => $id_entreprise, 
                    'id2' => $id_functionalUnit,
                    'ref_purchase' => $refPurchase,
                ]); 
            }
            else
            {
                return redirect()->route('app_add_new_purchase', [
                    'id' => $id_entreprise, 
                    'id2' => $id_functionalUnit,
                    'ref_purchase' => $purchase_margin_not_saved->ref_purchase,
                ]); 
            }
        }
        else
        {
            $purchase_margin_not_saved = DB::table('purchase_margins')
            ->where([
                'purchase_saved' => 0,
                'is_simple_purchase' => 0,
                'is_purchase_order' => 1,
                'id_fu' => $id_functionalUnit,
            ])->first();

            if(!$purchase_margin_not_saved)
            {
                /**
                 * Format N° de référence de la facture :  
                 * INV année mois jour heure minute seconde id_user
                 * INV2023121509093023
                 */
                $refPurchase = "PUR" . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . Auth::user()->id;

                PurchaseMargin::create([
                    'ref_purchase' => $refPurchase,
                    'is_simple_purchase' => 0,
                    'is_purchase_order' => $is_purchase_order,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_functionalUnit,
                ]);

                return redirect()->route('app_add_new_purchase', [
                    'id' => $id_entreprise, 
                    'id2' => $id_functionalUnit,
                    'ref_purchase' => $refPurchase,
                ]); 
            }
            else
            {
                return redirect()->route('app_add_new_purchase', [
                    'id' => $id_entreprise, 
                    'id2' => $id_functionalUnit,
                    'ref_purchase' => $purchase_margin_not_saved->ref_purchase,
                ]); 
            }
        }
    }

    public function addNewPurchase($id, $id2, $ref_purchase)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $suppliers = DB::table('suppliers')->where('id_fu', $id2)->get();
        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $purchase_margin = DB::table('purchase_margins')
                        ->where([
                            'ref_purchase' => $ref_purchase,
                            'id_fu' => $id2,
                        ])->first();

        $purchase = DB::table('purchases')->where('reference_purch', $ref_purchase)->first();

        if($purchase)
        {
            $supplier = DB::table('suppliers')->where('id', $purchase->id_supplier)->first();

            $this->request->session()->put('id_supplier', $supplier->id);
            $supplier->type_sup == 'particular' 
                ? $this->request->session()->put('entreprise_supplier', $supplier->contact_name_sup)
                : $this->request->session()->put('entreprise_supplier', $supplier->entreprise_name_sup); 
            
            $date = $purchase->created_at;
            $due_date = $purchase->due_date;

            $this->request->session()->put('date_purcharse', date('Y-m-d', strtotime($date)));
            $this->request->session()->put('due_date_purcharse', date('Y-m-d', strtotime($due_date))); 
        } 

        return view('expenses.add_new_purchase', compact(
            'entreprise',
            'functionalUnit',
            'suppliers',
            'ref_purchase',
            'country',
            'purchase_margin',
            'deviseGest'
        ));
    }

    public function uploadPurchasePdf()
    {
        $ref_purchase =  $this->request->input('ref_purchase');
        $file = $ref_purchase . '.' . $this->request->file_purchase->getClientOriginalExtension();

        $this->request->file_purchase->move(public_path('/assets/img/purchase/'), $file);

        return response()->json([
            'status' => "success",
            'code' => 200
        ]);
    }
}
