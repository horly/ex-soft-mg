<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function userChecker()
    {
        return redirect()->route('app_main');
    }

    public function addUserPage()
    {
        $grades = DB::table('grades')->get();
        $roles = DB::table('roles')->get();

        return view('auth.add_user_page', [
            'grades' => $grades,
            'roles' => $roles,
        ]);
    }

    public function addUser()
    {
        $name = $this->request->input('name');
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $role = $this->request->input('role');
        $grade = $this->request->input('grade');
        $phone_number = $this->request->input('phone_number');
        $address = $this->request->input('address');
        $matricule = $this->request->input('matricule');

        $array = array(
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role,
            'grade_id' => $grade,
            'phone_number' => $phone_number,
            'matricule' => $matricule,
            'address' => $address
        );

        $user = User::create($array);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'user' => $user,
        ]);
    }
}
