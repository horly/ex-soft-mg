<?php

namespace App\Http\Controllers;

use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

    public function dashboard($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('dashboard.dashboard', compact('entreprise', 'functionalUnit'));
    }
}