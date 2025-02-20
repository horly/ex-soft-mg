<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Decaissement;
use Illuminate\Http\Request;
use App\Models\PurchaseMargin;
use App\Repository\EntrepriseRepo;
use Illuminate\Support\Facades\DB;
use App\Repository\NotificationRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SavePurchaseForm;
use Illuminate\Support\Facades\Session;
use App\Repository\GenerateRefenceNumber;
use App\Services\Server\Server;

class ExpensesController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;
    protected $generateReferenceNumber;
    protected $server;

    function __construct(Request $request,
                            EntrepriseRepo $entrepriseRepo,
                            NotificationRepo $notificationRepo,
                            GenerateRefenceNumber $generateReferenceNumber,
                            Server $server)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
        $this->generateReferenceNumber = $generateReferenceNumber;
        $this->server = $server;
    }

    public function purchases($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $purchases = DB::table('purchases')->where('id_fu', $functionalUnit->id)->orderBy('id', 'desc')->get();
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

        return view('expenses.purchase', compact(
            'entreprise',
            'functionalUnit',
            'purchases',
            'deviseGest',
            'permission_assign',
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
                    'group' => 'expense',
                    'id' => $id_entreprise,
                    'id2' => $id_functionalUnit,
                    'ref_purchase' => $refPurchase,
                ]);
            }
            else
            {
                return redirect()->route('app_add_new_purchase', [
                    'group' => 'expense',
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

    public function addNewPurchase($group, $id, $id2, $ref_purchase)
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

        //on essaie de voir si la facture d'achat a déjà était enregistré ou c'est une nouvelle
        $purchases = DB::table('purchases')->where('reference_purch', $ref_purchase)->first();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                            ->where([
                                'payment_methods.id_fu' => $functionalUnit->id,
                                'devises.iso_code' => $deviseGest->iso_code,
                            ])->get();

        $ref_invoice = "";

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('expenses.add_new_purchase', compact(
            'entreprise',
            'functionalUnit',
            'suppliers',
            'ref_purchase',
            'country',
            'purchase_margin',
            'deviseGest',
            'purchases',
            'paymentMethods',
            'ref_invoice',
            'permission_assign'
        ));
    }

    public function uploadPurchasePdf()
    {
        $ref_purchase =  $this->request->input('ref_purchase');
        $file = $ref_purchase . '.' . $this->request->file_purchase->getClientOriginalExtension();

        //$this->request->file_purchase->move(base_path() . '/public_html/assets/img/purchase/', $file);

        $public_path = $this->server->getPublicFolder();

        $path = $public_path . '/assets/img/purchase/';
        $fileExist = $public_path . '/assets/img/purchase/' . $ref_purchase . '.pdf';

        if (file_exists($fileExist)) {
            unlink($fileExist);
            $this->request->file_purchase->move($path, $file);
        } else {
            $this->request->file_purchase->move($path, $file);
        }


        return response()->json([
            'status' => "success",
            'code' => 200
        ]);
    }

    public function savePurchase(SavePurchaseForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_purchase = $requestF->input('id_purchase');
        $reference_purch = $requestF->input('reference_purch');
        $customerRequest = $requestF->input('customerRequest');

        $supplier_purchase = $requestF->input('supplier_purchase');
        $designation_purchase = $requestF->input('designation_purchase');
        $amount_purchase = $requestF->input('amount_purchase');
        $date_purchase = $requestF->input('date_purchase');
        $due_date_purchase = $requestF->input('due_date_purchase');

        if($customerRequest != "edit")
        {
            Purchase::create([
                'reference_purch' => $reference_purch,
                'designation' => $designation_purchase,
                'amount' => $amount_purchase,
                'created_at' => $date_purchase,
                'due_date' => $due_date_purchase,
                'id_supplier' => $supplier_purchase,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            DB::table('purchase_margins')
                    ->where('ref_purchase', $reference_purch)
                    ->update([
                        'purchase_saved' => 1,
                        'updated_at' => new \DateTimeImmutable,
                ]);

                //Notification
                $url = route('app_purchases', ['group' => 'expense', 'id' => $id_entreprise, 'id2' => $id_fu]);
                $description = "expenses.added_a_purchase_invoice";
                $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                return redirect()->route('app_purchases', [
                    'id' => $id_entreprise,
                    'id2' => $id_fu,
                ])->with('success', __('expenses.purchase_invoice_saved_successfully'));
        }
        else
        {
            DB::table('purchases')
                ->where('id', $id_purchase)
                ->update([
                    'designation' => $designation_purchase,
                    'amount' => $amount_purchase,
                    'created_at' => $date_purchase,
                    'due_date' => $due_date_purchase,
                    'id_supplier' => $supplier_purchase,
                    'id_fu' => $id_fu,
                ]);

                //Notification
                $url = route('app_update_purchase', ['group' => 'expense', 'id' => $id_entreprise, 'id2' => $id_fu, 'ref_purchase' => $reference_purch]);
                $description = "expenses.modified_a_purchase_invoice";
                $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                return redirect()->route('app_purchases', [
                    'id' => $id_entreprise,
                    'id2' => $id_fu,
                ])->with('success', __('expenses.purchase_invoice_modified_successfully'));
        }
    }

    public function updatePurchase($group, $id, $id2, $ref_purchase)
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

        $purchases = DB::table('purchases')->where('reference_purch', $ref_purchase)->first();

        $paymentReceived = DB::table('decaissements')
                            ->where([
                                'reference_dec' => $ref_purchase,
                                'id_fu' => $id2,
                            ])->sum('amount');


        $remainingBalance = $purchases->amount - $paymentReceived;

        $decaissements = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                        ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where([
                            'decaissements.reference_dec' => $ref_purchase,
                            'decaissements.id_fu' => $id2,
                        ])->get();

        //dd($decaissements);

        $paymentMethods = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                        ->where([
                            'payment_methods.id_fu' => $functionalUnit->id,
                            'devises.iso_code' => $deviseGest->iso_code,
                        ])->get();

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('expenses.update_purchase', compact(
            'entreprise',
            'functionalUnit',
            'suppliers',
            'ref_purchase',
            'country',
            'purchase_margin',
            'deviseGest',
            'purchases',
            'decaissements',
            'paymentReceived',
            'remainingBalance',
            'paymentMethods',
            'permission_assign'
        ));
    }

    public function deletePurchase()
    {
        $ref_purchase = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        $purchase = DB::table('purchases')->where('reference_purch', $ref_purchase)->first();

        DB::table('purchases')->where('reference_purch', $ref_purchase)->delete();

        $fileDirname = public_path('/assets/img/purchase/' . $ref_purchase . '.pdf');
        //on supprime le ficchier pdf s'il existe
        if(file_exists($fileDirname))
        {
            unlink($fileDirname);
        }

        //Notification
        $url = route('app_purchases', ['group' => 'expense', 'id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "expenses.deleted_a_purchase_invoice";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_purchases', [
            'group' => 'expense',
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('expenses.purchase_invoice_successfully_deleted'));
    }

    public function expenses($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $expenses = DB::table('expenses')->where('id_fu', $functionalUnit->id)->orderBy('id', 'desc')->get();
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

        return view('expenses.expenses', compact(
            'entreprise',
            'functionalUnit',
            'expenses',
            'deviseGest',
            'permission_assign'
        ));
    }

    public function setUpExpense()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');

        $reference_expense = "EXP" . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . Auth::user()->id;

        return redirect()->route('app_add_new_expense', [
            'group' => 'expense',
            'id' => $id_entreprise,
            'id2' => $id_functionalUnit,
            'ref_expense' => $reference_expense,
        ]);
    }

    public function addNewExpense($group, $id, $id2, $ref_expense)
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

        $expense = DB::table('expenses')->where('reference_exp', $ref_expense)->first();

        $decaissement = DB::table('decaissements')->where('reference_dec', $ref_expense)->first();

        $paymentMeth = null;

        if($decaissement)
        {
            $paymentMeth = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                    ->where([
                        'payment_methods.id' => $decaissement->id_pay_meth,
                    ])->first();
        }

        $billing = DB::table('permissions')->where('name', 'billing')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $billing->id
                ])->first();

        return view('expenses.add_new_expense', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'deviseGestUfs',
            'paymentMethods',
            'expense',
            'ref_expense',
            'decaissement',
            'paymentMeth',
            'permission_assign'
        ));
    }

    public function saveExpense()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');
        $reference_exp = $this->request->input('reference_exp');
        $customerRequest = $this->request->input('customerRequest');
        $description_exp = $this->request->input('description_exp');
        $currency_exp = $this->request->input('currency_exp');
        $amount_expense = $this->request->input('amount_expense');
        $pay_method_exp = $this->request->input('pay_method_exp');
        $date_expense = $this->request->input('date_expense');

        if($customerRequest != "edit")
        {
            Expense::create([
                'description' => $description_exp,
                'reference_exp' => $reference_exp,
                'amount' => $amount_expense,
                'created_at' => $date_expense,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            Decaissement::create([
                'description' => 'expenses.expense_payment',
                'reference_dec' => $reference_exp,
                'is_purchase' => 0,
                'amount' => $amount_expense,
                'id_pay_meth' => $pay_method_exp,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            //Notification
            $url = route('app_expenses', ['group' => 'expense', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "expenses.recorded_an_expense";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_expenses', [
                'id' => $id_entreprise,
                'id2' => $id_fu,
            ])->with('success', __('expenses.expense_recorded_successfully'));
        }
        else
        {
            DB::table('expenses')
                ->where('reference_exp', $reference_exp)
                ->update([
                    'description' => $description_exp,
                    'amount' => $amount_expense,
                    'created_at' => $date_expense,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_fu,
                ]);

            DB::table('decaissements')
                ->where('reference_dec', $reference_exp)
                ->update([
                    'amount' => $amount_expense,
                    'id_pay_meth' => $pay_method_exp,
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id_fu,
                ]);

            //Notification
            $url = route('app_expenses', ['group' => 'expense', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "expenses.updated_an_expense";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_expenses', [
                'id' => $id_entreprise,
                'id2' => $id_fu,
            ])->with('success', __('expenses.expense_updated_successfully'));
        }
    }

    public function delete_purchase_file()
    {
        $ref_purchase =  $this->request->input('id_element');

        //$this->request->file_purchase->move(base_path() . '/public_html/assets/img/purchase/', $file);

        $public_path = $this->server->getPublicFolder();

        $path = $public_path . '/assets/img/purchase/';
        $fileExist = $public_path . '/assets/img/purchase/' . $ref_purchase . '.pdf';

        if (file_exists($fileExist)) {
            unlink($fileExist);
            return redirect()->back()->with('success', __('expenses.file_deleted_successfully')); //file deleted successfully!
        } else {
            return redirect()->back()->with('danger', __('expenses.no_files_found_to_delete'));
        }
    }

}
