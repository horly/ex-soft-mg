<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Repository\ConnectionHistoryRepo;
use App\Repository\EntrepriseRepo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $connectHistory;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo, ConnectionHistoryRepo $connectHistory)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->connectHistory = $connectHistory;
    }

    public function main()
    {
        $entreprises = $this->entrepriseRepo->getEntrepriseBySub();

        return view('main.main', [
            'entreprises' => $entreprises
        ]);
    }

    public function infosOnlineUser($matricule)
    {
        $user = User::where('matricule', $matricule)->first();
        $grade = Grade::where('id', $user->grade_id)->first();

        return view('main.infos-online-user', [
            'user' => $user,
            'grade' => $grade,
        ]);
    }

    public function userManagement()
    {
        $user = Auth::user();

        $users = User::where('sub_id', $user->sub_id)
                            ->orderBy('id', 'asc')
                            ->get();

        return view('main.user-management',
            compact('users')
        );
    }

    public function loginHistory()
    {
        $user = Auth::user();

        $histories = $this->connectHistory->getHistoryByUser($user->id);

        return view('auth.login-histoty', compact('histories'));
    }
}
