<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyForm;
use App\Models\DeviseGestionUF;
use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
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

    public function currency($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $devises = DB::table('devises')->orderBy('iso_code')->get();

        $deviseGest = DB::table('devise_gestion_ufs')->where([
            'id_fu' => $functionalUnit->id,
            'default_cur_manage' => 1,
        ])->first();
        
        $deviseDefault = DB::table('devises')->where('id', $deviseGest->id_devise)->first();
        $deviseFU = DB::table('devise_gestion_ufs')
            ->join('devises', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where([
                'devise_gestion_ufs.id_fu' => $functionalUnit->id,
            ])
            ->orderBy('devise_gestion_ufs.id', 'desc')
            ->get();
        
        
        return view('currency.currency', compact('entreprise', 'functionalUnit', 'devises', 'deviseGest', 'deviseDefault', 'deviseFU'));
    }

    public function createCurrency($id, $id2)
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

        return view('currency.create_currency', compact('entreprise', 'functionalUnit', 'devises', 'deviseGest'));
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
            DeviseGestionUF::create([
                'taux' => $rate_currency_dev,
                'id_devise' => $currency_name_dev,
                'id_fu' => $id_fu
            ]);

            //Notification
            $url = route('app_currency', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "dashboard.added_a_new_currency_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_currency', ['id' => $id_entreprise, 'id2' => $id_fu])
                        ->with('success', __('dashboard.currency_added_successfully'));
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
            $url = route('app_currency', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "dashboard.updated_a_currency_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_currency', ['id' => $id_entreprise, 'id2' => $id_fu])
                    ->with('success', __('dashboard.currency_updated_successfully'));
        }
    }
}