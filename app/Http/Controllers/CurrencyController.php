<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyForm;
use App\Models\DeviseGestionUF;
use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
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

    public function currency($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $devises = DB::table('devises')->orderBy('iso_code')->get();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();

        $deviseDefault = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where('devises.id', $deviseGest->id_devise)
            ->first();

        $deviseFU = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
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

        return view('currency.currency', compact(
            'entreprise',
            'functionalUnit',
            'devises',
            'deviseGest',
            'deviseDefault',
            'deviseFU',
            'permission_assign')
        );
    }

    public function createCurrency($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $devises = DB::table('devises')->orderBy('iso_code')->get();

        $deviseGest = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
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

        return view('currency.create_currency', compact('entreprise', 'functionalUnit', 'devises', 'deviseGest', 'permission_assign'));
    }

    public function saveCurrency(CurrencyForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_currency_gest = $requestF->input('id_currency_gest');
        $fuRequest = $requestF->input('fuRequest');
        $currency_name_dev = $requestF->input('currency_name_dev');
        $rate_currency_dev = $requestF->input('rate_currency_dev');

        if($fuRequest != "edit")
        {
            $existDevise = DB::table('devise_gestion_ufs')
                            ->where([
                                'id_devise' => $currency_name_dev,
                                'id_fu' => $id_fu,
            ])->first();

            if(!$existDevise)
            {
                $currecy_saved = DeviseGestionUF::create([
                    'taux' => $rate_currency_dev,
                    'id_devise' => $currency_name_dev,
                    'id_fu' => $id_fu
                ]);

                //Notification
                $url = route('app_info_currency', ['group' => 'currency', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $currecy_saved->id]);
                $description = "dashboard.added_a_new_currency_in_the_functional_unit";
                $this->notificationRepo->setNotification($id_entreprise, $description, $url);

                return redirect()->route('app_currency', ['group' => 'currency', 'id' => $id_entreprise, 'id2' => $id_fu])
                            ->with('success', __('dashboard.currency_added_successfully'));
            }
            else
            {
                return redirect()->back()->with('danger', __('dashboard.this_currency_has_already_been_added'));
            }
        }
        else
        {
            DB::table('devise_gestion_ufs')
                    ->where('id', $id_currency_gest)
                    ->update([
                        'taux' => $rate_currency_dev,
                        'id_devise' => $currency_name_dev,
            ]);

            //Notification
            $url = route('app_info_currency', ['group' => 'currency', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_currency_gest]);
            $description = "dashboard.updated_a_currency_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_currency', ['group' => 'currency', 'id' => $id_entreprise, 'id2' => $id_fu])
                    ->with('success', __('dashboard.currency_updated_successfully'));
        }
    }

    public function changeDefaultcurrency()
    {
        $main_currency = $this->request->input('main_currency');
        $id_entreprise = $this->request->input('id_entreprise');
        $id_fu = $this->request->input('id_fu');

        if($main_currency != "")
        {
            DB::table('devise_gestion_ufs')
                    ->where('id_fu', $id_fu)
                    ->update([
                        'default_cur_manage' => 0
            ]);

            DB::table('devise_gestion_ufs')
                    ->where('id_devise', $main_currency)
                    ->update([
                        'default_cur_manage' => 1,
                        'taux' => 1,
            ]);

            //Notification
            $url = route('app_currency', ['group' => 'currency', 'id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "dashboard.changed_the_default_currency";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->back()->with('success', __('dashboard.default_currency_updated_successfully'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function infoCurrency($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();

        $deviseDefault = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where('devises.id', $deviseGest->id_devise)
            ->first();

        $devise = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where('devises.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('currency.info_currency', compact('entreprise', 'functionalUnit', 'devise', 'deviseDefault', 'permission_assign'));
    }

    public function deleteCurrency()
    {
        $id_currency = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('devise_gestion_ufs')
                    ->where([
                        'id_devise' => $id_currency,
                        'id_fu' => $id_fu
        ])->delete();

        //Notification
        $url = route('app_currency', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "dashboard.removed_a_currency_from_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_currency', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('dashboard.currency_deleted_successfully'));
    }

    public function upDatecurrency($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $devises = DB::table('devises')->orderBy('iso_code')->get();

        $deviseSel = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where('devises.id', $id3)->first();

        $deviseGestionUfs = DB::table('devise_gestion_ufs')
            ->where([
                'id_devise' => $id3,
                'id_fu' => $functionalUnit->id
        ])->first();

        $deviseGest = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
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

        return view('currency.updated_currency', compact(
            'entreprise',
            'functionalUnit',
            'deviseSel',
            'devises',
            'deviseGest',
            'deviseGestionUfs',
            'permission_assign')
        );
    }
}
