<?php

namespace App\Http\Controllers;

use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use App\Repository\ShortThousand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function dashboard($id, $id2)
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

        $first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
        $last_day_this_month  = date('t-m-Y');

        $tclients = DB::table('clients')->where('id_fu', $functionalUnit->id)->count();
        $totalClient = $this->shortThousand->number_format_short($tclients);

        $articles = DB::table('articles')->where('id_fu', $functionalUnit->id)->count();
        $totalArticle = $this->shortThousand->number_format_short($articles);


        return view('dashboard.dashboard', compact(
            'entreprise', 
            'functionalUnit', 
            'first_day_this_month', 
            'last_day_this_month',
            'deviseGest',
            'deviseGestAll',
            'totalClient',
            'totalArticle',
        ));
    }
}
