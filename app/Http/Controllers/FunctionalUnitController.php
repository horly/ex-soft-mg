<?php

namespace App\Http\Controllers;

use App\Http\Requests\FunctionalUnitForm;
use App\Models\DeviseGestionUF;
use App\Models\FunctionalUnit;
use App\Models\FunctionalunitEmail;
use App\Models\FunctionalUnitPhone;
use App\Models\ManageFU;
use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FunctionalUnitController extends Controller
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

    public function saveFunctionalUnit(FunctionalUnitForm $requestionF)
    {
        $user = Auth::user();

        $name = $requestionF->input('unit_name');
        $currency_fu = $requestionF->input('currency_fu');
        $address = $requestionF->input('unit_address');
        $unit_phone = $requestionF->input('unit_phone');
        $unit_email = $requestionF->input('unit_email');
        $id_entreprise = $requestionF->input('id_entreprise'); 
        $id_fu = $requestionF->input('id_fu'); 
        $fuRequest = $requestionF->input('fuRequest');

        if($fuRequest != "edit")
        {
            $functUnit = FunctionalUnit::create([
                'name' => $name,
                'address' => $address, 
                'id_entreprise' => $id_entreprise,
                'sub_id' => $user->id,
            ]);

            FunctionalUnitPhone::create([
                'phone_number' => $unit_phone,
                'id_func_unit' => $functUnit->id
            ]);

            FunctionalunitEmail::create([
                'email' => $unit_email,
                'id_func_unit' => $functUnit->id
            ]);

            DeviseGestionUF::create([
                'taux' => 1,
                'default_cur_manage' => 1,
                'id_devise' => $currency_fu,
                'id_fu' =>  $functUnit->id,
            ]);

            //Notification
            $url = route('app_fu_infos', ['id' => $id_entreprise, 'id2' => $functUnit->id]);
            $description = "entreprise.created_a_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_entreprise', ['id' => $id_entreprise])->with('success', __('entreprise.functional_unit_saved_successfully'));
        }
        else
        {
            DB::table('functional_units')
                ->where('id', $id_fu)
                ->update([
                    'name' => $name,
                    'address' => $address, 
                    'updated_at' => new \DateTimeImmutable,
            ]);

            DB::table('devise_gestion_ufs')
                ->where([
                    'id_fu' => $id_fu,
                    'default_cur_manage' => 1])
                ->update([
                    'id_devise' => $currency_fu
            ]);

            //Notification
            $url = route('app_fu_infos', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "entreprise.has_modified_FU";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_fu_infos', ['id' => $id_entreprise, 'id2' => $id_fu])->with('success', __('entreprise.functional_unit_updated_successfully'));
        }  
    }

    public function modules($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        
        return view('functional_unit.modules', compact('entreprise', 'functionalUnit'));
    }

    public function assignFUtoUSer()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');
        $id_user = $this->request->input('id_user');

        ManageFU::create([
            'id_user' => $id_user,
            'id_entreprise' => $id_entreprise,
            'id_fu' => $id_functionalUnit,
        ]);

        //Notification
        $url = route('app_entreprise', ['id' => $id_entreprise]);
        $description = "entreprise.added_you_in_the_fu";
        $this->notificationRepo->setNotificationSpecificUsr($id_entreprise, $description, $url, $id_user);

        return redirect()->back()->with('success', __('entreprise.functional_has_been_successfully_assigned_to_the_user'));
    }

    public function deleteManagementFU()
    {
        $id_entreprise = $this->request->input('id_element1');
        $id_user = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('manage_f_u_s')
                    ->where([
                        'id_user' => $id_user,
                        'id_entreprise' => $id_entreprise,
                        'id_fu' => $id_fu,
                    ])->delete();

        return redirect()->back()->with('success', __('entreprise.functional_unit_assignment_was_successfully_deleted'));
    }

    public function fuInfos($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();

        $deviseDefault = DB::table('devises')->where('id', $deviseGest->id_devise)->first();

        return view('functional_unit.functional_unite-infos', compact('entreprise', 'functionalUnit', 'deviseDefault'));
    }

    public function upDatePageFu($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $phones = DB::table('functional_unit_phones')->where('id_func_unit', $id2)->get();
        $emails = DB::table('functionalunit_emails')->where('id_func_unit', $id2)->get();
        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
        $devises = DB::table('devises')->orderBy('iso_code')->get();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();
        
        $deviseDefault = DB::table('devises')->where('id', $deviseGest->id_devise)->first();

        return view('functional_unit.update-functional-unit', compact(
            'entreprise', 
            'functionalUnit', 
            'phones', 
            'emails', 
            'country', 
            'devises',
            'deviseDefault',
        ));
    }

    public function addNewPhoneNumber()
    {
        $phone = $this->request->input('new_phone_number');
        $id_fu = $this->request->input('id_fu');
        $modalRequest = $this->request->input('modalRequest');
        $id_phone = $this->request->input('id_phone');

        $fu = DB::table('functional_units')->where('id', $id_fu)->first();

        if($modalRequest != "edit")
        {
            FunctionalUnitPhone::create([
                'phone_number' => $phone,
                'id_func_unit' => $id_fu
            ]);

            //Notification
            $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $id_fu]);
            $description = "entreprise.added_a_functional_unit_phone_number";
            $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

            return redirect()->back()->with('success', __('entreprise.phone_number_added_successfully'));
        }else{
            DB::table('functional_unit_phones')
                    ->where('id', $id_phone)
                    ->update([
                        'phone_number' => $phone,
                        'updated_at' => new \DateTimeImmutable,
                    ]);
            
            //Notification
            $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $id_fu]);
            $description = "entreprise.changed_the_functional_unit_phone_number";
            $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

            return redirect()->back()->with('success', __('entreprise.phone_number_updated_successfully'));
        }
    }

    public function deletePhoneNumberEntr()
    {
        $id_phone = $this->request->input('id_element');

        $phone = DB::table('functional_unit_phones')->where('id', $id_phone)->first();
        $fu = DB::table('functional_units')->where('id', $phone->id_func_unit)->first();

        DB::table('functional_unit_phones')
                    ->where('id', $id_phone)
                    ->delete();
        
        //Notification
        $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $fu->id]);
        $description = "entreprise.deteled_the_functional_unit_phone_number";
        $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

        return redirect()->back()->with('success', __('entreprise.phone_number_deleted_successfully'));
    }

    public function addNewEmail()
    {
        $email = $this->request->input('new_email_address');
        $id_fu = $this->request->input('id_fu');
        $modalRequest = $this->request->input('modalRequest');
        $id_email = $this->request->input('id_email');

        $fu = DB::table('functional_units')->where('id', $id_fu)->first();

        if($modalRequest != "edit")
        {
            FunctionalunitEmail::create([
                'email' => $email,
                'id_func_unit' => $id_fu
            ]);

            //Notification
            $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $id_fu]);
            $description = "entreprise.added_a_functional_unit_email_address";
            $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);
    
            return redirect()->back()->with('success', __('entreprise.email_address_added_successfully'));
        }
        else
        {
            DB::table('functionalunit_emails')
                    ->where('id', $id_email)
                    ->update([
                        'email' => $email,
                        'updated_at' => new \DateTimeImmutable,
                    ]);

            //Notification
            $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $id_fu]);
            $description = "entreprise.changed_the_functional_unit_email_address";
            $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

            return redirect()->back()->with('success', __('entreprise.email_updated_successfully'));
        }
    }

    public function deleteEmailAddress()
    {
        $id_email = $this->request->input('id_element');

        $email = DB::table('functionalunit_emails')->where('id', $id_email)->first();
        $fu = DB::table('functional_units')->where('id', $email->id_func_unit)->first();

        DB::table('functionalunit_emails')
                    ->where('id', $id_email)
                    ->delete();
        
        //Notification
        $url = route('app_fu_infos', ['id' => $fu->id_entreprise, 'id2' => $fu->id]);
        $description = "entreprise.deteled_the_functional_unit_email_address";
        $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

        return redirect()->back()->with('success', __('entreprise.email_address_deleted_successfully'));
    }

    public function deleteFunctionalUnit()
    {
        $id_fu = $this->request->input('id_element');
        $fu = DB::table('functional_units')->where('id', $id_fu)->first();

        DB::table('functional_units')
                    ->where('id', $id_fu)
                    ->delete();

        //Notification
        $url = route('app_entreprise', ['id' => $fu->id_entreprise]);
        $description = "entreprise.deleted_a_functional_unit";
        $this->notificationRepo->setNotification($fu->id_entreprise, $description, $url);

        return redirect()->route('app_entreprise', ['id' => $fu->id_entreprise])->with('success', __('entreprise.email_address_deleted_successfully'));
    }
}
