<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryForm;
use App\Http\Requests\CreateServiceForm;
use App\Models\CategoryService;
use App\Models\Service;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
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

    public function categoryService($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_services = DB::table('category_services')
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

        return view('service.category-service', compact('entreprise', 'functionalUnit', 'category_services', 'permission_assign'));
    }

    public function addNewCategoryService($group, $id, $id2)
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

        return view('service.add_new_category-service', compact('entreprise', 'functionalUnit', 'permission_assign'));
    }

    public function createCategoryService(CreateCategoryForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_cat_serv = $requestF->input('id_cat_serv');
        $name_cat = $requestF->input('name_cat');
        $customerRequest = $requestF->input('customerRequest');


        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("category_services", $id_fu);
            $ref = $this->generateReferenceNumber->generate("CAS", $refNum);

            $cat_ser_saved = CategoryService::create([
                'reference_cat_serv' => $ref,
                'reference_number' => $refNum,
                'name_cat_serv' => $name_cat,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_info_service_category', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $cat_ser_saved->id]);
            $description = "service.added_a_new_service_category_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('service.service_category_added_successfully'));
        }
        else
        {
            DB::table('category_services')
                ->where('id', $id_cat_serv)
                ->update([
                    'name_cat_serv' => $name_cat,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_info_service_category', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_cat_serv]);
            $description = "service.updated_a_service_category";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('service.service_category_updated_successfully'));
        }
    }

    public function infoServiceCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_service = DB::table('users')
                    ->join('category_services', 'category_services.id_user', '=', 'users.id')
                    ->where('category_services.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('service.info_category-service', compact('entreprise', 'functionalUnit', 'category_service', 'permission_assign'));
    }

    public function deleteCategoryService()
    {
        $id_cat_serv = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('category_services')->where('id', $id_cat_serv)->delete();

        //Notification
        $url = route('app_category_service', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "service.deleted_a_service_category_in_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_category_service', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('service.service_category_successfully_deleted'));
    }

    public function updateServiceCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_service = DB::table('category_services')->where('id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('service.update_category-service', compact('entreprise', 'functionalUnit', 'category_service', 'permission_assign'));
    }

    /**
     * Service
     */

     public function service($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $services = DB::table('users')
                    ->join('services', 'services.id_user', '=', 'users.id')
                    ->where('services.id_fu', $functionalUnit->id)
                    ->orderByDesc('services.id')
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

        return view('service.service', compact('entreprise', 'functionalUnit', 'services', 'deviseGest', 'permission_assign'));
    }

    public function addNewService($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_services = DB::table('category_services')
                ->where('id_fu', $functionalUnit->id)
                ->orderByDesc('id')
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

        return view('service.add_new_service', compact('entreprise', 'functionalUnit', 'category_services', 'deviseGest', 'permission_assign'));
    }

    public function createService(CreateServiceForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_serv = $requestF->input('id_serv');
        $description_serv = $requestF->input('description_serv');
        $cat_serv = $requestF->input('cat_serv');
        $unit_price_serv = $requestF->input('unit_price_serv');
        $customerRequest = $requestF->input('customerRequest');


        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("services", $id_fu);
            $ref = $this->generateReferenceNumber->generate("SERV", $refNum);

            $service = Service::create([
                'reference_serv' => $ref,
                'reference_number' => $refNum,
                'description_serv' => $description_serv,
                'unit_price' => $unit_price_serv,
                'id_cat' => $cat_serv,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_info_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $service->id]);
            $description = "service.added_a_new_service_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('service.service_added_successfully'));
        }
        else
        {
            DB::table('services')
                ->where('id', $id_serv)
                ->update([
                    'description_serv' => $description_serv,
                    'unit_price' => $unit_price_serv,
                    'id_cat' => $cat_serv,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_info_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_serv]);
            $description = "service.updated_a_service";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_service', ['group' => 'service', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('service.service_updated_successfully'));
        }
    }

    public function infoService($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $service = DB::table('users')
                    ->join('services', 'services.id_user', '=', 'users.id')
                    ->where('services.id', $id3)->first();

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

        return view('service.info_service', compact('entreprise', 'functionalUnit', 'service', 'deviseGest', 'permission_assign'));
    }

    public function deleteService()
    {
        $id_serv = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('services')->where('id', $id_serv)->delete();

        //Notification
        $url = route('app_service', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "service.deleted_a_service_in_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_service', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('service.service_successfully_deleted'));
    }

    public function updateService($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $service = DB::table('users')
                    ->join('services', 'services.id_user', '=', 'users.id')
                    ->where('services.id', $id3)->first();

        $category_services = DB::table('category_services')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();

        $category_serv = DB::table('category_services')
                    ->where('id', $service->id_cat)
                    ->orderByDesc('id')
                    ->first();

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

        return view('service.update_service', compact(
            'entreprise',
            'functionalUnit',
            'service',
            'deviseGest',
            'category_services',
            'category_serv',
            'permission_assign'
        ));
    }
}
