<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Repository\EntrepriseRepo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
    }

    public function main()
    {
        $entreprises = $this->entrepriseRepo->getAll();

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

    public function createEntreprise()
    {
        return view('entreprise.create-entreprise');
    }
}
