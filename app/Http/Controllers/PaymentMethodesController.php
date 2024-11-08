<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodForm;
use App\Models\PaymentMethod;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentMethodesController extends Controller
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

    public function paymentMethods($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $paymentMethods = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devises.id' , '=', 'devise_gestion_ufs.id_devise')
                            ->join('payment_methods', 'devise_gestion_ufs.id', '=', 'payment_methods.id_currency')
                            ->where('payment_methods.id_fu', $id2)
                            ->orderBy('payment_methods.id', 'asc')
                            ->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('payment_methods.payment_methods', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'paymentMethods',
            'permission_assign'
        ));
    }

    public function addNewPaymentMethods($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();

        $deviseDefault = DB::table('devises')
            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where('devises.id', $deviseGest->id_devise)
            ->first();

        $deviseGests = DB::table('devises')
            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where([
                'devise_gestion_ufs.id_fu' => $functionalUnit->id,
            ])
            ->orderBy('devise_gestion_ufs.id', 'desc')
            ->get();


        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('payment_methods.add_new_payment_methods', compact(
            'entreprise',
            'functionalUnit',
            'deviseGests',
            'deviseDefault',
            'permission_assign')
        );
    }

    public function createPaymentMethods(PaymentMethodForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_payment_methods = $requestF->input('id_payment_methods');
        $customerRequest = $requestF->input('customerRequest');
        $designation_pay_meth = $requestF->input('designation_pay_meth');
        $instu_name_pay_meth = $requestF->input('instu_name_pay_meth');
        $devise_pay_meth = $requestF->input('devise_pay_meth');
        $account_number_pay_meth = $requestF->input('account_number_pay_meth');
        $bic_swift_pay_meth = $requestF->input('bic_swift_pay_meth');
        $iban_pay_meth = $requestF->input('iban_pay_meth');

        if($customerRequest != "edit")
        {
            $payMeth = PaymentMethod::create([
                'designation' => $designation_pay_meth,
                'institution_name' => $instu_name_pay_meth,
                'iban' => $iban_pay_meth,
                'account_number' => $account_number_pay_meth,
                'bic_swiff' => $bic_swift_pay_meth,
                'id_currency' => $devise_pay_meth,
                'id_user' => Auth::user()->id,
                'id_fu' => $id_fu,
            ]);

            //Notification
            $url = route('app_info_payment_methods', ['group' => 'payment_method', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $payMeth->id]);
            $description = "payment_methods.added_a_payment_method_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_payment_methods', ['group' => 'payment_method', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('payment_methods.payment_method_added_successfully'));
        }
        else
        {
            DB::table('payment_methods')
                ->where('id', $id_payment_methods)
                ->update([
                    'designation' => $designation_pay_meth,
                    'institution_name' => $instu_name_pay_meth,
                    'iban' => $iban_pay_meth,
                    'account_number' => $account_number_pay_meth,
                    'bic_swiff' => $bic_swift_pay_meth,
                    'id_currency' => $devise_pay_meth,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_info_payment_methods', ['group' => 'payment_method', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_payment_methods]);
            $description = "payment_methods.has_updated_a_payment_method_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_payment_methods', ['group' => 'payment_method', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('payment_methods.payment_method_updated_successfully'));
        }
    }

    public function infoPaymentMethods($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $paymentMethod = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devises.id' , '=', 'devise_gestion_ufs.id_devise')
                            ->join('payment_methods', 'devise_gestion_ufs.id', '=', 'payment_methods.id_currency')
                            ->where('payment_methods.id', $id3)
                            ->first();

        $paymentReceived = DB::table('encaissements')
                            ->where([
                                'id_pay_meth' => $paymentMethod->id,
                            ])->sum('amount');

        $paymentMade = DB::table('decaissements')
                            ->where([
                                'id_pay_meth' => $paymentMethod->id,
                            ])->sum('amount');

        $encaissement_exit = DB::table('encaissements')->where('id_pay_meth', $paymentMethod->id)->first();
        //$decaissement_exit = DB::table('encaissements')->where('id_pay_meth', $paymentMethod->id)->first();

        $encaissements = DB::table('encaissements')->where('id_pay_meth', $paymentMethod->id)->orderBy('id', 'desc')->get();
        $decaissements = DB::table('decaissements')->where('id_pay_meth', $paymentMethod->id)->orderBy('id', 'desc')->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('payment_methods.info_payment_methods', compact(
            'entreprise',
            'functionalUnit',
            'deviseGest',
            'paymentMethod',
            'paymentReceived',
            'encaissement_exit',
            'paymentMade',
            'encaissements',
            'decaissements',
            'permission_assign'
            //'decaissement_exit'
        ));
    }

    public function deletePaymentMethods()
    {
        $id_payment_method = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('payment_methods')->where('id', $id_payment_method)->delete();

        //Notification
        $url = route('app_payment_methods', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "payment_methods.has_removed_a_payment_method";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_payment_methods', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('payment_methods.payment_method_deleted_successfully'));
    }

    public function upDatePaymentMethods($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGests = DB::table('devises')
            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where([
                'devise_gestion_ufs.id_fu' => $functionalUnit->id,
            ])
            ->orderBy('devise_gestion_ufs.id', 'desc')
            ->get();

        $paymentMethod = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devises.id' , '=', 'devise_gestion_ufs.id_devise')
                            ->join('payment_methods', 'devise_gestion_ufs.id', '=', 'payment_methods.id_currency')
                            ->where('payment_methods.id', $id3)
                            ->first();

        $devisePaymethod = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devises.id' , '=', 'devise_gestion_ufs.id_devise')
                            ->where('devise_gestion_ufs.id', $paymentMethod->id_currency)
                            ->first();

        $encaissement_exit = DB::table('encaissements')->where('id_pay_meth', $paymentMethod->id)->first();
        //$decaissement_exit = DB::table('encaissements')->where('id_pay_meth', $paymentMethod->id)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('payment_methods.update_payment_methods', compact(
            'entreprise',
            'functionalUnit',
            'deviseGests',
            'paymentMethod',
            'devisePaymethod',
            'encaissement_exit',
            'permission_assign'
        ));
    }
}
