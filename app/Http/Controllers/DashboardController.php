<?php

namespace App\Http\Controllers;

use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use App\Repository\ShortThousand;
use App\Services\Number\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;
    protected $shortThousand;

    function __construct(Request $request,
                            EntrepriseRepo $entrepriseRepo,
                            NotificationRepo $notificationRepo,
                            ShortThousand $shortThousand)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
        $this->shortThousand = $shortThousand;
    }

    public function dashboard($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $deviseGest = DB::table('devises')
            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where([
                'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                'devise_gestion_ufs.default_cur_manage' => 1,
        ])->first();

        $deviseGestAll = DB::table('devises')
            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
            ->where([
                'devise_gestion_ufs.id_fu' => $functionalUnit->id
        ])->get();

        $amount_all_invoices = DB::table('sales_invoices')
                                ->where([
                                    'is_simple_invoice' => 1,
                                    'id_fu' => $functionalUnit->id
                                ])
                                ->sum('total');

        $collections_all = DB::table('payment_methods')
                            ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                            ->where([
                                'encaissements.is_invoice' => 1,
                                'payment_methods.id_currency' => $deviseGest->id
                            ])->sum('amount');

        //dd($collections_all);

        $amount_from_client_to_be_paied = $amount_all_invoices - $collections_all;

        if($amount_from_client_to_be_paied < 0)
        {
            $amount_from_client_to_be_paied = 0;
        }

        $amount_all_purchases = DB::table('purchases')
                                ->where('id_fu', $functionalUnit->id)
                                ->sum('amount');


        $decaissement_all = DB::table('payment_methods')
                            ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                            ->where([
                                'decaissements.is_purchase' => 1,
                                'payment_methods.id_currency' => $deviseGest->id
                            ])->sum('amount');

        //dd($decaissement_all);

        $amount_from_me_to_be_paied = $amount_all_purchases - $decaissement_all;



        if(Session::has('id_devise_dashboard'))
        {
            $id_devise_dashboard = Session::get('id_devise_dashboard');

            $deviseGest = DB::table('devises')
                ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                ->where([
                    'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                    'devise_gestion_ufs.id' => $id_devise_dashboard,
            ])->first();
        }

        $year = date('Y');

        if(Session::has('year'))
        {
            $year = Session::get('year');
        }

        $first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
        $last_day_this_month  = date('t-m-Y');

        $tclients = DB::table('clients')->where('id_fu', $functionalUnit->id)->count();
        $totalClient = $this->shortThousand->number_format_short($tclients);

        $articles = DB::table('articles')->where('id_fu', $functionalUnit->id)->count();
        $totalArticle = $this->shortThousand->number_format_short($articles);


        /**
         * recettesAll : pour récupérer toutes les recettes
         * recettesAll est juste pour tester
         */
        $recettesAll = DB::table('payment_methods')
                    ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                    ->where('payment_methods.id_currency', $deviseGest->id)->sum('amount');

        /**
         * recettesPeriod : pour récupérer les récettes du mois
         */
        $start_date = date('m-01'); // hard-coded '01' for first day
        $end_date  = date('m-t');

        $recettesPeriod = DB::table('payment_methods')
                    ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                    ->where('payment_methods.id_currency', $deviseGest->id)
                    ->whereBetween('encaissements.created_at', [$year . '-' . $start_date, $year . '-' . $end_date])->sum('amount');

        //dd($recettesPeriod);


        /**
         * depensesAll : pour récupérer toutes les sorties d'argent
         * depensesAll est juste pour tester
         */
        $depensesAll = DB::table('payment_methods')
                    ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                    ->where('payment_methods.id_currency', $deviseGest->id)->sum('amount');

        /**
         * recettesPeriod : pour récupérer les récettes du mois
         */
        $depensePeriod = DB::table('payment_methods')
                    ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                    ->where('payment_methods.id_currency', $deviseGest->id)
                    ->whereBetween('decaissements.created_at', [$year . '-' . $start_date, $year . '-' . $end_date])->sum('amount');

        //dd($depensePeriod);

        $number = new Number;
        $recettes = $number->formatNumber($recettesPeriod);
        $depenses = $number->formatNumber($depensePeriod);

        $full_dashboard_view = DB::table('permissions')->where('name', 'full_dashboard_view')->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $id2,
                'id_perms' => $full_dashboard_view->id
            ])->first();


        $sale_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $functionalUnit->id,
                //'id_perms' => $permission->id,
                'group' => 'sale'
        ])->first();

        $expense_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $functionalUnit->id,
                //'id_perms' => $permission->id,
                'group' => 'expense'
        ])->first();

        $stock_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $functionalUnit->id,
                //'id_perms' => $permission->id,
                'group' => 'stock'
        ])->first();

        $customer_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => Auth::user()->id,
                'id_fu' => $functionalUnit->id,
                //'id_perms' => $permission->id,
                'group' => 'customer'
        ])->first();

        return view('dashboard.dashboard', compact(
            'entreprise',
            'functionalUnit',
            'first_day_this_month',
            'last_day_this_month',
            'deviseGest',
            'deviseGestAll',
            'totalClient',
            'totalArticle',
            'recettes',
            'depenses',
            'year',
            'amount_from_client_to_be_paied',
            'amount_from_me_to_be_paied',
            'permission_assign',
            'group',
            'sale_assign',
            'expense_assign',
            'stock_assign',
            'customer_assign',
        ));
    }

    public function changeDevGl()
    {
        $id_devise = $this->request->input('id_devise');

        $this->request->session()->put('id_devise_dashboard', $id_devise);

        return response()->json([
            'code' => 200,
            'status' => "success"
        ]);
    }

    public function incomeGlobal()
    {
        $id_fu = $this->request->input('id_fu');
        $id_devise = $this->request->input('id_devise');
        $month = $this->request->input('month');
        $year = $this->request->input('year');
        $monthly = $this->request->input('monthly');

        $recettesPeriod = null;
        $depensePeriod = null;

        if($monthly == "true")
        {
            $start_day = date('01'); // hard-coded '01' for first day
            $end_day  = date('31');

            $recettesPeriod = DB::table('payment_methods')
                        ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where('payment_methods.id_currency', $id_devise)
                        ->whereBetween('encaissements.created_at', [$year . '-' . $month . '-' . $start_day, $year . '-' . $month . '-' . $end_day])->sum('amount');

            //dd($recettesPeriod);


            /**
             * recettesPeriod : pour récupérer les récettes du mois
             */
            $depensePeriod = DB::table('payment_methods')
                        ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where('payment_methods.id_currency', $id_devise)
                        ->whereBetween('decaissements.created_at', [$year . '-' . $month . '-' . $start_day, $year . '-' . $month . '-' . $end_day])->sum('amount');

            //dd($depensePeriod);
        }
        else
        {
            $recettesPeriod = DB::table('payment_methods')
            ->join('encaissements', 'encaissements.id_pay_meth', '=', 'payment_methods.id')
            ->where('payment_methods.id_currency', $id_devise)
            ->whereBetween('encaissements.created_at', [$year . '-01-01', $year . '-12-31'])->sum('amount');

            //dd($recettesPeriod);


            /**
             * recettesPeriod : pour récupérer les récettes du mois
             */
            $depensePeriod = DB::table('payment_methods')
                        ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                        ->where('payment_methods.id_currency', $id_devise)
                        ->whereBetween('decaissements.created_at', [$year . '-01-01', $year . '-12-31'])->sum('amount');

            //dd($depensePeriod);
        }


        $recettes = number_format($recettesPeriod, 2, '.', '');
        $depenses = number_format($depensePeriod, 2, '.', '');
        $resultats = $recettes - $depenses;

        return response()->json([
            'recettes' => $recettes,
            'depenses' => $depenses,
            'resultats' => $resultats,
        ]);
    }

    public function set_year_evolution()
    {
        $year_evolution = $this->request->input('year-evolution');

        if(!$year_evolution)
        {
            $year_evolution = date('Y');
        }

        $this->request->session()->put('year', $year_evolution);

        return redirect()->back();
    }
}
