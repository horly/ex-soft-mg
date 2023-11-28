<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientForm;
use App\Models\Client;
use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo, NotificationRepo $notificationRepo)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
    }

    public function customer($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $clients = DB::table('clients')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();

        return view('client.client', compact('entreprise', 'functionalUnit', 'clients'));
    }

    public function addNewClient($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('client.add_new_client', compact('entreprise', 'functionalUnit'));
    }

    public function createClient(CreateClientForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_customer = $requestF->input('id_customer');
        $customerRequest = $requestF->input('customerRequest');
        $customer_type_cl = $requestF->input('customer_type_cl');
        $company_name_cl = $requestF->input('company_name_cl');
        $company_rccm_cl = $requestF->input('company_rccm_cl');
        $company_id_nat_cl = $requestF->input('company_id_nat_cl');
        $company_nif_cl = $requestF->input('company_nif_cl');
        $company_account_number_cl = $requestF->input('company_account_number_cl');
        $company_website_cl = $requestF->input('company_website_cl');
        $full_name_cl = $requestF->input('full_name_cl');
        $grade_cl = $requestF->input('grade_cl');
        $email_cl = $requestF->input('email_cl');
        $phone_number_cl = $requestF->input('phone_number_cl');
        $address_cl = $requestF->input('address_cl');

        if($customerRequest != "edit")
        {
            if($customer_type_cl == "company")
            {
                Client::create([
                    'type' => $customer_type_cl,
                    'entreprise_name_cl' => $company_name_cl,
                    'rccm_cl' => $company_rccm_cl,
                    'id_nat_cl' => $company_id_nat_cl,
                    'nif_cl' => $company_nif_cl,
                    'account_num_cl' => $company_account_number_cl,
                    'website_cl' => $company_website_cl,
                    'contact_name_cl' => $full_name_cl,
                    'fonction_contact_cl' => $grade_cl,
                    'phone_number_cl' => $phone_number_cl,
                    'email_adress_cl' => $email_cl,
                    'address_cl' => $address_cl,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }else
            {
                Client::create([
                    'type' => $customer_type_cl,
                    'contact_name_cl' => $full_name_cl,
                    'fonction_contact_cl' => $grade_cl,
                    'phone_number_cl' => $phone_number_cl,
                    'email_adress_cl' => $email_cl,
                    'address_cl' => $address_cl,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }

            //Notification
            $url = route('app_customer', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "client.added_a_new_customer";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_customer', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('client.customer_added_successfully'));
        }
        else
        {
            if($customer_type_cl == "company")
            {
                DB::table('clients')
                ->where('id', $id_customer)
                ->update([
                    'type' => $customer_type_cl,
                    'entreprise_name_cl' => $company_name_cl,
                    'rccm_cl' => $company_rccm_cl,
                    'id_nat_cl' => $company_id_nat_cl,
                    'nif_cl' => $company_nif_cl,
                    'account_num_cl' => $company_account_number_cl,
                    'website_cl' => $company_website_cl,
                    'contact_name_cl' => $full_name_cl,
                    'fonction_contact_cl' => $grade_cl,
                    'phone_number_cl' => $phone_number_cl,
                    'email_adress_cl' => $email_cl,
                    'address_cl' => $address_cl,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }
            else
            {
                DB::table('clients')
                ->where('id', $id_customer)
                ->update([
                    'type' => $customer_type_cl,
                    'entreprise_name_cl' => "-",
                    'rccm_cl' => "-",
                    'id_nat_cl' => "-",
                    'nif_cl' => "-",
                    'account_num_cl' => "-",
                    'website_cl' => "-",
                    'contact_name_cl' => $full_name_cl,
                    'fonction_contact_cl' => $grade_cl,
                    'phone_number_cl' => $phone_number_cl,
                    'email_adress_cl' => $email_cl,
                    'address_cl' => $address_cl,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }

            //Notification
            $url = route('app_customer', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "client.updated_a_customer_from_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_customer', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('client.customer_updated_successfully'));
        }
    }

    public function infoCustomer($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $client = DB::table('users')
                    ->join('clients', 'clients.id_user', '=', 'users.id')
                    ->where('clients.id', $id3)->first();

        return view('client.info_client', compact('entreprise', 'functionalUnit', 'client'));
    }

    public function deleteClient()
    {
        $id_client = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('clients')->where('id', $id_client)->delete();

        //Notification
        $url = route('app_customer', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "client.removed_a_customer_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_customer', [
            'id' => $id_entreprise, 
            'id2' => $id_fu ])->with('success', __('client.customer_deleted_successfully'));
    }

    public function updateCustomer($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $client = DB::table('clients')->where('id', $id3)->first();

        return view('client.update_client', compact('entreprise', 'functionalUnit', 'client'));
    }
}
