<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCreditorForm;
use App\Models\Creditor;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreditorController extends Controller
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

    public function creditor($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $creditors = DB::table('creditors')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();
        
        return view('creditor.creditor', compact('entreprise', 'functionalUnit', 'creditors'));
    }

    public function addNewCreditor($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('creditor.add_new_creditor', compact('entreprise', 'functionalUnit'));
    }

    public function createCreditor(CreateCreditorForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_creditor = $requestF->input('id_creditor');
        $customerRequest = $requestF->input('customerRequest');
        $customer_type_cr = $requestF->input('customer_type_cr');
        $company_name_cr = $requestF->input('company_name_cr');
        $company_rccm_cr = $requestF->input('company_rccm_cr');
        $company_id_nat_cr = $requestF->input('company_id_nat_cr');
        $company_nif_cr = $requestF->input('company_nif_cr');
        $company_account_number_cr = $requestF->input('company_account_number_cr');
        $company_website_cr = $requestF->input('company_website_cr');
        $full_name_cr = $requestF->input('full_name_cr');
        $grade_cr = $requestF->input('grade_cr');
        $email_cr = $requestF->input('email_cr');
        $phone_number_cr = $requestF->input('phone_number_cr');
        $address_cr = $requestF->input('address_cr');

        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("creditors", $id_fu);
            $ref = $this->generateReferenceNumber->generate("CR", $refNum);

            if($customer_type_cr == "company")
            {
                Creditor::create([
                    'type_cr' => $customer_type_cr,
                    'reference_cr' => $ref,
                    'reference_number' => $refNum,
                    'entreprise_name_cr' => $company_name_cr,
                    'rccm_cr' => $company_rccm_cr,
                    'id_nat_cr' => $company_id_nat_cr,
                    'nif_cr' => $company_nif_cr,
                    'account_num_cr' => $company_account_number_cr,
                    'website_cr' => $company_website_cr,
                    'contact_name_cr' => $full_name_cr,
                    'fonction_contact_cr' => $grade_cr,
                    'phone_number_cr' => $phone_number_cr,
                    'email_adress_cr' => $email_cr,
                    'address_cr' => $address_cr,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }else
            {
                Creditor::create([
                    'type_cr' => $customer_type_cr,
                    'reference_cr' => $ref,
                    'reference_number' => $refNum,
                    'contact_name_cr' => $full_name_cr,
                    'fonction_contact_cr' => $grade_cr,
                    'phone_number_cr' => $phone_number_cr,
                    'email_adress_cr' => $email_cr,
                    'address_cr' => $address_cr,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }

            //Notification
            $url = route('app_creditor', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "creditor.added_a_new_creditor";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_creditor', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('creditor.creditor_added_successfully'));
        }
        else
        {
            if($customer_type_cr == "company")
            {
                DB::table('creditors')
                ->where('id', $id_creditor)
                ->update([
                    'type_cr' => $customer_type_cr,
                    'entreprise_name_cr' => $company_name_cr,
                    'rccm_cr' => $company_rccm_cr,
                    'id_nat_cr' => $company_id_nat_cr,
                    'nif_cr' => $company_nif_cr,
                    'account_num_cr' => $company_account_number_cr,
                    'website_cr' => $company_website_cr,
                    'contact_name_cr' => $full_name_cr,
                    'fonction_contact_cr' => $grade_cr,
                    'phone_number_cr' => $phone_number_cr,
                    'email_adress_cr' => $email_cr,
                    'address_cr' => $address_cr,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }
            else
            {
                DB::table('creditors')
                ->where('id', $id_creditor)
                ->update([
                    'type_cr' => $customer_type_cr,
                    'entreprise_name_cr' => "-",
                    'rccm_cr' => "-",
                    'id_nat_cr' => "-",
                    'nif_cr' => "-",
                    'account_num_cr' => "-",
                    'website_cr' => "-",
                    'contact_name_cr' => $full_name_cr,
                    'fonction_contact_cr' => $grade_cr,
                    'phone_number_cr' => $phone_number_cr,
                    'email_adress_cr' => $email_cr,
                    'address_cr' => $address_cr,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }

            //Notification
            $url = route('app_creditor', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "creditor.updated_a_creditor_from_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_creditor', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('creditor.creditor_updated_successfully'));
        }
    }

    public function infoCreditor($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $creditor = DB::table('users')
                    ->join('creditors', 'creditors.id_user', '=', 'users.id')
                    ->where('creditors.id', $id3)->first();

        return view('creditor.info_creditor', compact('entreprise', 'functionalUnit', 'creditor'));
    }

    public function deleteCreditor()
    {
        $id_creditor = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('creditors')->where('id', $id_creditor)->delete();

        //Notification
        $url = route('app_creditor', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "creditor.removed_a_creditor_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_creditor', [
            'id' => $id_entreprise, 
            'id2' => $id_fu ])->with('success', __('creditor.creditor_deleted_successfully'));
    }

    public function updateCreditor($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $creditor = DB::table('creditors')->where('id', $id3)->first();

        return view('creditor.update_creditor', compact('entreprise', 'functionalUnit', 'creditor'));
    }
}
