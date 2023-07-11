<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function main()
    {
        return view('main.main');
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
}
