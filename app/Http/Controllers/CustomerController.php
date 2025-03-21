<?php

namespace App\Http\Controllers;

use App\Http\Requests\addNewContactClientForm;
use App\Http\Requests\CreateClientForm;
use App\Models\Client;
use App\Models\CustomerContact;
use App\Models\FunctionalUnit;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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

    public function customer($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $clients = DB::table('clients')
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

        return view('client.client', compact('entreprise', 'functionalUnit', 'clients', 'group', 'permission_assign'));
    }

    public function addNewClient($group, $id, $id2)
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

        return view('client.add_new_client', compact('entreprise', 'functionalUnit', 'permission_assign'));
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
        $department_cl = $requestF->input('department_cl');

        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("clients", $id_fu);
            $ref = $this->generateReferenceNumber->generate("CL", $refNum);

            if($customer_type_cl == "company")
            {
                $client_saved = Client::create([
                    'type' => $customer_type_cl,
                    'reference_cl' => $ref,
                    'reference_number' => $refNum,
                    'entreprise_name_cl' => $company_name_cl,
                    'rccm_cl' => $company_rccm_cl,
                    'id_nat_cl' => $company_id_nat_cl,
                    'nif_cl' => $company_nif_cl,
                    'account_num_cl' => $company_account_number_cl,
                    'website_cl' => $company_website_cl,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }else
            {
                $client_saved = Client::create([
                    'type' => $customer_type_cl,
                    'reference_cl' => $ref,
                    'reference_number' => $refNum,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }

            CustomerContact::create([
                'fullname_cl' => $full_name_cl,
                'fonction_contact_cl' => $grade_cl,
                'phone_number_cl' => $phone_number_cl,
                'email_adress_cl' => $email_cl,
                'address_cl' => $address_cl,
                'departement_cl' => $department_cl,
                'id_client' => $client_saved->id,
            ]);

            //Notification
            $url = route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $client_saved->id]);
            $description = "client.added_a_new_customer";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('client.customer_added_successfully'));
        }
        else
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
                'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_customer]);
            $description = "client.updated_a_customer_from_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('client.customer_updated_successfully'));
        }
    }

    public function infoCustomer($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $client = DB::table('users')
                    ->join('clients', 'clients.id_user', '=', 'users.id')
                    ->where('clients.id', $id3)->first();

        $contacts = DB::table('customer_contacts')->where('id_client', $client->id)->get();
        $invoices = DB::table('clients')
                        ->join('sales_invoices', 'clients.id', '=', 'sales_invoices.id_client')
                        ->where([
                            'sales_invoices.id_fu' => $functionalUnit->id,
                            'sales_invoices.is_proforma_inv' => 0,
                            'sales_invoices.is_delivery_note' => 0,
                            'sales_invoices.id_client' => $client->id
                        ])
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        $invoices_proforma = DB::table('clients')
                        ->join('sales_invoices', 'clients.id', '=', 'sales_invoices.id_client')
                        ->where([
                            'sales_invoices.id_fu' => $functionalUnit->id,
                            'sales_invoices.is_proforma_inv' => 1,
                            'sales_invoices.id_client' => $client->id
                        ])
                        ->orderBy('sales_invoices.id', 'desc')
                        ->get();

        $deviseGest = DB::table('devises')
                        ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                        ->where([
                            'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                            'devise_gestion_ufs.default_cur_manage' => 1,
                ])->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('client.info_client', compact(
            'entreprise',
            'functionalUnit',
            'client',
            'contacts',
            'invoices',
            'invoices_proforma',
            'deviseGest',
            'permission_assign'
        ));
    }

    public function deleteClient()
    {
        $id_client = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('clients')->where('id', $id_client)->delete();

        //Notification
        $url = route('app_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "client.removed_a_customer_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_customer', [
            'group' => 'customer',
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('client.customer_deleted_successfully'));
    }

    public function deleteContactClient()
    {
        $id_contact = $this->request->input('id_element');
        $contact = CustomerContact::where('id', $id_contact)->first();
        $id_client = $contact->id_client;
        $client = Client::where('id', $id_client)->first();
        $id_fu = $client->id_fu;
        $fu = FunctionalUnit::where('id', $id_fu)->first();
        $id_entreprise = $fu->id_entreprise;

        DB::table('customer_contacts')->where('id', $id_contact)->delete();

        //Notification
        $url = route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_client]);
        $description = "client.contact_deleted_successfully";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->back()->with('success', __('client.contact_deleted_successfully'));
    }

    public function updateCustomer($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $client = DB::table('clients')->where('id', $id3)->first();
        $contacts = DB::table('customer_contacts')->where('id_client', $client->id)->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('client.update_client', compact('entreprise', 'functionalUnit', 'client', 'contacts', 'permission_assign'));
    }

    public function addNewContactClient(addNewContactClientForm $requestF)
    {
        $full_name_cl = $requestF->input('full_name_cl');
        $grade_cl = $requestF->input('grade_cl');
        $department_cl = $requestF->input('department_cl');
        $email_cl = $requestF->input('email_cl');
        $phone_number_cl = $requestF->input('phone_number_cl');
        $address_cl = $requestF->input('address_cl');
        $id_client = $requestF->input('id_client');
        $modalRequest = $requestF->input('modalRequest');
        $id_contact = $requestF->input('id_contact');
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');

        //dd($this->request->all());

        if($modalRequest != "edit")
        {
            CustomerContact::create([
                'fullname_cl' => $full_name_cl,
                'fonction_contact_cl' => $grade_cl,
                'phone_number_cl' => $phone_number_cl,
                'email_adress_cl' => $email_cl,
                'address_cl' => $address_cl,
                'departement_cl' => $department_cl,
                'id_client' => $id_client,
            ]);

             //Notification
             $url = route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_client]);
             $description = "client.added_a_customer_contact";
             $this->notificationRepo->setNotification($id_entreprise, $description, $url);

             return redirect()->route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_client])
                ->with('success', __('client.contact_saved_successfully'));
        }
        else
        {
            DB::table('customer_contacts')
                ->where('id', $id_contact)
                ->update([
                    'fullname_cl' => $full_name_cl,
                    'fonction_contact_cl' => $grade_cl,
                    'phone_number_cl' => $phone_number_cl,
                    'email_adress_cl' => $email_cl,
                    'address_cl' => $address_cl,
                    'departement_cl' => $department_cl,
                    'updated_at' => new \DateTimeImmutable
            ]);

            $url = route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_client]);
            $description = "client.updated_a_customer_contact";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_info_customer', ['group' => 'customer', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_client])
                ->with('success', __('client.contact_updated_successfully'));
        }
    }

    public function add_new_contact($group, $id, $id2, $id3, $id4)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $client = Client::where('id', $id3)->first();
        $contact = CustomerContact::where('id', $id4)->first();

        $id_contact = $id4;

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();


        return view('client.add_new_contact', compact(
            'group',
            'entreprise',
            'functionalUnit',
            'client',
            'contact',
            'id_contact',
            'permission_assign'
        ));
    }
}
