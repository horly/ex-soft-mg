<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntrepriseForm;
use App\Http\Requests\FunctionalUnitForm;
use App\Models\BankAccount;
use App\Models\BusinessContact;
use App\Models\BusinessEmail;
use App\Models\Entreprise;
use App\Models\FunctionalUnit;
use App\Repository\EntrepriseRepo;
use DateTimeImmutable;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntrepriseController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
    }
    
    public function createEntreprise()
    {
        $countries_gb = DB::table('countries')
                            ->orderBy('name_gb', 'asc')
                            ->get();

        $countries_fr = DB::table('countries')
                        ->orderBy('name_fr', 'asc')
                        ->get();

        return view('entreprise.create-entreprise', [
            'countries_gb' => $countries_gb,
            'countries_fr' => $countries_fr
        ]);
    }

    public function saveEntreprise(CreateEntrepriseForm $requestF)
    {   
        $id_entreprise = $requestF->input('id_entreprise');
        $entrepriseRequest = $requestF->input('entrepriseRequest');
        $name_entreprise = $requestF->input('name_entreprise');
        $slogan_entreprise = $requestF->input('slogan_entreprise');
        $rccm_entreprise = $requestF->input('rccm_entreprise');
        $idnat_entreprise = $requestF->input('idnat_entreprise');
        $nif_entreprise = $requestF->input('nif_entreprise');
        $address_entreprise = $requestF->input('address_entreprise');
        $id_country = $requestF->input('country_entreprise');
        $phone_entreprise = $requestF->input('phone_entreprise');
        $email_entreprise = $requestF->input('email_entreprise');
        $website_entreprise = $requestF->input('website_entreprise');

        $user = Auth::user();

        if($entrepriseRequest != "edit")
        {
            $entreprise = Entreprise::create([
                'name' => $name_entreprise,
                'slogan' => $slogan_entreprise,
                'rccm' => $rccm_entreprise,
                'id_nat' => $idnat_entreprise,
                'nif' => $nif_entreprise,
                'address' => $address_entreprise,
                'id_user' => $user->id,
                'id_country' => $id_country,
                'website' => $website_entreprise,
                'sub_id' => $user->sub_id,
            ]);

            BusinessContact::create([
                'phone_number' => $phone_entreprise,
                'id_entreprise' => $entreprise->id,
            ]); 

            BusinessEmail::create([
                'email' => $email_entreprise,
                'id_entreprise' => $entreprise->id,
            ]);

            return redirect()->route('app_main')->with('success', __('main.company_added_successfully'));
        }
        else
        {
            DB::table('entreprises')
                ->where('id', $id_entreprise)
                ->update([
                    'name' => $name_entreprise,
                    'slogan' => $slogan_entreprise,
                    'rccm' => $rccm_entreprise,
                    'id_nat' => $idnat_entreprise,
                    'nif' => $nif_entreprise,
                    'address' => $address_entreprise,
                    'id_country' => $id_country,
                    'website' => $website_entreprise,
                    'updated_at' => new \DateTimeImmutable,
                ]);
            
            return redirect()->route('app_entreprise', ['id' => $id_entreprise])->with('success', __('entreprise.company_updated_successfully'));
        }
    }

    public function deleteEntreprise()
    {
        $id_entreprise = $this->request->input('id_element');

        DB::table('entreprises')
                    ->where('id', $id_entreprise)
                    ->delete();

        return redirect()->route('app_main')->with('success', __('entreprise.company_deleted_successfully'));
    }

    public function entreprise($id)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnits = DB::table('functional_units')->where('id_entreprise', $entreprise->id)->get();

        return view('entreprise.entreprise', compact('entreprise', 'functionalUnits'));
    }

    public function createFunctionalUnit($id)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first(); 

        return view('entreprise.create-functional-unit', compact('entreprise'));
    }

    public function saveFunctionalUnit(FunctionalUnitForm $requestionF)
    {
        $name = $requestionF->input('unit_name');
        $address = $requestionF->input('unit_address');
        $id_entreprise = $requestionF->input('id_entreprise');

        FunctionalUnit::create([
            'name' => $name,
            'address' => $address, 
            'id_entreprise' => $id_entreprise,
        ]);

        return redirect()->route('app_entreprise', ['id' => $id_entreprise])->with('success', __('entreprise.functional_unit_saved_successfully'));
    }

    public function updateEntreprise($id)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();

        $countries_gb = DB::table('countries')
                        ->orderBy('name_gb', 'asc')
                        ->get();

        $countries_fr = DB::table('countries')
                        ->orderBy('name_fr', 'asc')
                        ->get();

        $entrepriseContry = DB::table('countries')
                        ->where('id', $entreprise->id_country)
                        ->first();

        $phoneNumbers = DB::table('business_contacts')
                        ->where('id_entreprise', $entreprise->id)
                        ->get();

        $emailAdresss = DB::table('business_emails')
                        ->where('id_entreprise', $entreprise->id)
                        ->get();

        $bankAccounts = DB::table('bank_accounts')
                        ->where('id_entreprise', $entreprise->id)
                        ->get();
        
        $devises = DB::table('devises')
                        ->orderBy('iso_code')
                        ->get();

        return view('entreprise.update-entreprise', compact(
                            'entreprise', 
                            'countries_gb', 
                            'countries_fr',
                            'entrepriseContry',
                            'phoneNumbers',
                            'emailAdresss',
                            'bankAccounts',
                            'devises',
        ));
    }

    public function addNewPhoneNumber()
    {
        $phone = $this->request->input('new_phone_number');
        $id_entreprise = $this->request->input('id_entreprise');
        $modalRequest = $this->request->input('modalRequest');
        $id_phone = $this->request->input('id_phone');

        if($modalRequest != "edit")
        {
            BusinessContact::create([
                'phone_number' => $phone,
                'id_entreprise' => $id_entreprise
            ]);

            return redirect()->back()->with('success', __('entreprise.phone_number_added_successfully'));
        }else{
            DB::table('business_contacts')
                    ->where('id', $id_phone)
                    ->update([
                        'phone_number' => $phone,
                        'updated_at' => new \DateTimeImmutable,
                    ]);

            return redirect()->back()->with('success', __('entreprise.phone_number_updated_successfully'));
        }
    }

    public function deletePhoneNumberEntr()
    {
        $id_phone = $this->request->input('id_element');

        DB::table('business_contacts')
                    ->where('id', $id_phone)
                    ->delete();

        return redirect()->back()->with('success', __('entreprise.phone_number_deleted_successfully'));
    }

    public function addNewEmail()
    {
        $email = $this->request->input('new_email_address');
        $id_entreprise = $this->request->input('id_entreprise');
        $modalRequest = $this->request->input('modalRequest');
        $id_email = $this->request->input('id_email');

        if($modalRequest != "edit")
        {
            BusinessEmail::create([
                'email' => $email,
                'id_entreprise' => $id_entreprise
            ]);
    
            return redirect()->back()->with('success', __('entreprise.email_address_added_successfully'));
        }
        else
        {
            DB::table('business_emails')
                    ->where('id', $id_email)
                    ->update([
                        'email' => $email,
                        'updated_at' => new \DateTimeImmutable,
                    ]);

            return redirect()->back()->with('success', __('entreprise.email_updated_successfully'));
        }
    }

    public function deleteEmailAddress()
    {
        $id_email = $this->request->input('id_element');

        DB::table('business_emails')
                    ->where('id', $id_email)
                    ->delete();

        return redirect()->back()->with('success', __('entreprise.email_address_deleted_successfully'));
    }

    public function addNewBankAccount()
    {
        $bank_name = $this->request->input('bank_name');
        $account_title = $this->request->input('account_title');
        $account_number = $this->request->input('account_number');
        $account_currency_save = $this->request->input('account_currency_save');
        $account_currency_update = $this->request->input('account_currency_update');
        $id_entreprise = $this->request->input('id_entreprise');
        $modalRequest = $this->request->input('modalRequest');
        $id_bank = $this->request->input('id_bank');

        if($modalRequest != "edit")
        {
            BankAccount::create([
                'bank_name' => $bank_name,
                'account_number' => $account_number,
                'account_title' => $account_title,
                'id_entreprise' => $id_entreprise,
                'id_devise' => $account_currency_save,
            ]);
    
            return redirect()->back()->with('success', __('entreprise.bank_account_added_successfully'));
        }
        else
        {
            DB::table('bank_accounts')
                    ->where('id', $id_bank)
                    ->update([
                        'bank_name' => $bank_name,
                        'account_number' => $account_number,
                        'account_title' => $account_title,
                        'id_entreprise' => $id_entreprise,
                        'id_devise' => $account_currency_update,
                        'updated_at' => new \DateTimeImmutable,
                    ]);

            return redirect()->back()->with('success', __('entreprise.bank_account_updated_successfully'));
        }
    }

    public function deleteBankAccount()
    {
        $id_bank = $this->request->input('id_element');

        DB::table('bank_accounts')
                    ->where('id', $id_bank)
                    ->delete();

        return redirect()->back()->with('success', __('entreprise.bank_account_deleted_successfully'));
    }

    public function getAlldevise()
    {
        $devises = DB::table('devises')
                    ->orderBy('iso_code')
                    ->get();

        return response()->json([
            'code' => 200,
            'devises' => $devises,
            'status' => 'success'
        ]);
    }
}
