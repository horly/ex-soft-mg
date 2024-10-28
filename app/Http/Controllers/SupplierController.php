<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSupplierForm;
use App\Models\Supplier;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
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

    public function supplier($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $suppliers = DB::table('suppliers')
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

        return view('supplier.supplier', compact('entreprise', 'functionalUnit', 'suppliers', 'permission_assign'));
    }

    public function addNewSupplier($group, $id, $id2)
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

        return view('supplier.add_new_supplier', compact('entreprise', 'functionalUnit', 'permission_assign'));
    }

    public function createSupplier(CreateSupplierForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_supplier = $requestF->input('id_supplier');
        $customerRequest = $requestF->input('customerRequest');
        $customer_type_sup = $requestF->input('customer_type_sup');
        $company_name_sup = $requestF->input('company_name_sup');
        $company_rccm_sup = $requestF->input('company_rccm_sup');
        $company_id_nat_sup = $requestF->input('company_id_nat_sup');
        $company_nif_sup = $requestF->input('company_nif_sup');
        $company_account_number_sup = $requestF->input('company_account_number_sup');
        $company_website_sup = $requestF->input('company_website_sup');
        $full_name_sup = $requestF->input('full_name_sup');
        $grade_sup = $requestF->input('grade_sup');
        $email_sup = $requestF->input('email_sup');
        $phone_number_sup = $requestF->input('phone_number_sup');
        $address_sup = $requestF->input('address_sup');

        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("suppliers", $id_fu);
            $ref = $this->generateReferenceNumber->generate("SU", $refNum);

            if($customer_type_sup == "company")
            {
                $supplier_saved = Supplier::create([
                    'type_sup' => $customer_type_sup,
                    'reference_sup' => $ref,
                    'reference_number' => $refNum,
                    'entreprise_name_sup' => $company_name_sup,
                    'rccm_sup' => $company_rccm_sup,
                    'id_nat_sup' => $company_id_nat_sup,
                    'nif_sup' => $company_nif_sup,
                    'account_num_sup' => $company_account_number_sup,
                    'website_sup' => $company_website_sup,
                    'contact_name_sup' => $full_name_sup,
                    'fonction_contact_sup' => $grade_sup,
                    'phone_number_sup' => $phone_number_sup,
                    'email_adress_sup' => $email_sup,
                    'address_sup' => $address_sup,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }else
            {
                $supplier_saved = Supplier::create([
                    'type_sup' => $customer_type_sup,
                    'reference_sup' => $ref,
                    'reference_number' => $refNum,
                    'contact_name_sup' => $full_name_sup,
                    'fonction_contact_sup' => $grade_sup,
                    'phone_number_sup' => $phone_number_sup,
                    'email_adress_sup' => $email_sup,
                    'address_sup' => $address_sup,
                    'id_fu' => $id_fu,
                    'id_user' => Auth::user()->id,
                ]);
            }

            //Notification
            $url = route('app_info_supplier', ['group' => 'supplier', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $supplier_saved->id]);
            $description = "supplier.added_a_new_supplier";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_supplier', ['group' => 'supplier', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('supplier.supplier_added_successfully'));
        }
        else
        {
            if($customer_type_sup == "company")
            {
                DB::table('suppliers')
                ->where('id', $id_supplier)
                ->update([
                    'type_sup' => $customer_type_sup,
                    'entreprise_name_sup' => $company_name_sup,
                    'rccm_sup' => $company_rccm_sup,
                    'id_nat_sup' => $company_id_nat_sup,
                    'nif_sup' => $company_nif_sup,
                    'account_num_sup' => $company_account_number_sup,
                    'website_sup' => $company_website_sup,
                    'contact_name_sup' => $full_name_sup,
                    'fonction_contact_sup' => $grade_sup,
                    'phone_number_sup' => $phone_number_sup,
                    'email_adress_sup' => $email_sup,
                    'address_sup' => $address_sup,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }
            else
            {
                DB::table('suppliers')
                ->where('id', $id_supplier)
                ->update([
                    'type_sup' => $customer_type_sup,
                    'entreprise_name_sup' => "-",
                    'rccm_sup' => "-",
                    'id_nat_sup' => "-",
                    'nif_sup' => "-",
                    'account_num_sup' => "-",
                    'website_sup' => "-",
                    'contact_name_sup' => $full_name_sup,
                    'fonction_contact_sup' => $grade_sup,
                    'phone_number_sup' => $phone_number_sup,
                    'email_adress_sup' => $email_sup,
                    'address_sup' => $address_sup,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            }

            //Notification
            $url = route('app_info_supplier', ['group' => 'supplier', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_supplier]);
            $description = "supplier.updated_a_supplier_from_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_supplier', ['group' => 'supplier', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('supplier.supplier_updated_successfully'));
        }
    }

    public function infoSupplier($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $supplier = DB::table('users')
                    ->join('suppliers', 'suppliers.id_user', '=', 'users.id')
                    ->where('suppliers.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('supplier.info_supplier', compact('entreprise', 'functionalUnit', 'supplier', 'permission_assign'));
    }

    public function deleteSupplier()
    {
        $id_supplier = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('suppliers')->where('id', $id_supplier)->delete();

        //Notification
        $url = route('app_supplier', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "supplier.removed_a_supplier_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_supplier', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('supplier.supplier_deleted_successfully'));
    }

    public function updateSupplier($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $supplier = DB::table('suppliers')->where('id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $edit_delete_contents->id
            ])->first();

        return view('supplier.update_supplier', compact('entreprise', 'functionalUnit', 'supplier', 'permission_assign'));
    }
}
