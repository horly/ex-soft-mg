<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDebtorForm;
use App\Models\Debtor;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
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

    public function debtor($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $debtors = DB::table('debtors')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('debtor.debtor', compact('entreprise', 'functionalUnit', 'debtors', 'permission_assign'));
    }

    public function addNewDebtor($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('debtor.add_new_debtor', compact('entreprise', 'functionalUnit', 'permission_assign'));
    }

    public function createDebtor(CreateDebtorForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_creditor = $requestF->input('id_debtor');
        $customerRequest = $requestF->input('customerRequest');
        $customer_type_deb = $requestF->input('customer_type_deb');
        $company_name_deb = $requestF->input('company_name_deb');
        $company_rccm_deb = $requestF->input('company_rccm_deb');
        $company_id_nat_deb = $requestF->input('company_id_nat_deb');
        $company_nif_deb = $requestF->input('company_nif_deb');
        $company_account_number_deb = $requestF->input('company_account_number_deb');
        $company_website_deb = $requestF->input('company_website_deb');
        $full_name_deb = $requestF->input('full_name_deb');
        $grade_deb = $requestF->input('grade_deb');
        $email_deb = $requestF->input('email_deb');
        $phone_number_deb = $requestF->input('phone_number_deb');
        $address_deb = $requestF->input('address_deb');

        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("debtors", $id_fu);
            $ref = $this->generateReferenceNumber->generate("DEB", $refNum);

            if($customer_type_deb == "company")
            {
                $debtor_saved = Debtor::create([
                    'type_deb' => $customer_type_deb,
                    'reference_deb' => $ref,
                    'reference_number' => $refNum,
                    'entreprise_name_deb' => $company_name_deb,
                    'rccm_deb' => $company_rccm_deb,
                    'id_nat_deb' => $company_id_nat_deb,
                    'nif_deb' => $company_nif_deb,
                    'account_num_deb' => $company_account_number_deb,
                    'website_deb' => $company_website_deb,
                    'contact_name_deb' => $full_name_deb,
                    'fonction_contact_deb' => $grade_deb,
                    'phone_number_deb' => $phone_number_deb,
                    'email_adress_deb' => $email_deb,
                    'address_deb' => $address_deb,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }else
            {
                $debtor_saved = Debtor::create([
                    'type_deb' => $customer_type_deb,
                    'reference_deb' => $ref,
                    'reference_number' => $refNum,
                    'contact_name_deb' => $full_name_deb,
                    'fonction_contact_deb' => $grade_deb,
                    'phone_number_deb' => $phone_number_deb,
                    'email_adress_deb' => $email_deb,
                    'address_deb' => $address_deb,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }

            //Notification
            $url = route('app_info_debtor', ['group' => 'debtor', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $debtor_saved->id]);
            $description = "debtor.added_a_new_debtor";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_debtor', ['group' => 'debtor', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('debtor.debtor_added_successfully'));
        }
        else
        {
            if($customer_type_deb == "company")
            {
                DB::table('debtors')
                ->where('id', $id_creditor)
                ->update([
                    'type_deb' => $customer_type_deb,
                    'entreprise_name_deb' => $company_name_deb,
                    'rccm_deb' => $company_rccm_deb,
                    'id_nat_deb' => $company_id_nat_deb,
                    'nif_deb' => $company_nif_deb,
                    'account_num_deb' => $company_account_number_deb,
                    'website_deb' => $company_website_deb,
                    'contact_name_deb' => $full_name_deb,
                    'fonction_contact_deb' => $grade_deb,
                    'phone_number_deb' => $phone_number_deb,
                    'email_adress_deb' => $email_deb,
                    'address_deb' => $address_deb,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }
            else
            {
                DB::table('debtors')
                ->where('id', $id_creditor)
                ->update([
                    'type_deb' => $customer_type_deb,
                    'entreprise_name_deb' => "-",
                    'rccm_deb' => "-",
                    'id_nat_deb' => "-",
                    'nif_deb' => "-",
                    'account_num_deb' => "-",
                    'website_deb' => "-",
                    'contact_name_deb' => $full_name_deb,
                    'fonction_contact_deb' => $grade_deb,
                    'phone_number_deb' => $phone_number_deb,
                    'email_adress_deb' => $email_deb,
                    'address_deb' => $address_deb,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }

            //Notification
            $url = route('app_info_debtor', ['group' => 'debtor', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_creditor]);
            $description = "debtor.updated_a_debtor_from_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_debtor', ['group' => 'debtor', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('debtor.debtor_updated_successfully'));
        }
    }

    public function infoDebtor($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $debtor = DB::table('users')
                    ->join('debtors', 'debtors.id_user', '=', 'users.id')
                    ->where('debtors.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('debtor.info_debtor', compact('entreprise', 'functionalUnit', 'debtor', 'permission_assign'));
    }

    public function deleteDebtor()
    {
        $id_debtor = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('debtors')->where('id', $id_debtor)->delete();

        //Notification
        $url = route('app_debtor', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "debtor.removed_a_debtor_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_debtor', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('debtor.debtor_deleted_successfully'));
    }

    public function updateDebtor($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $debtor = DB::table('debtors')->where('id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('debtor.update_debtor', compact('entreprise', 'functionalUnit', 'debtor', 'permission_assign'));
    }
}
