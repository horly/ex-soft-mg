<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserForm;
use App\Models\User;
use App\Repository\ConnectionHistoryRepo;
use App\Services\Email\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    public function userChecker()
    {
        $user = Auth::user();

        //on génère de nombre aleotoire de 6 chiffres si l'utilisateur choisie de copier coller le code
        $verification_code = "";
        $longeur_code = 6;
        $verification_code_secret = md5(uniqid()) . $user->id . sha1($user->email);

        for($i = 0; $i < $longeur_code; $i++)
        {
            $verification_code .= mt_rand(0,9);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'two_factor_secret' => $verification_code_secret,
                'two_factor_recovery_codes' => $verification_code,
            ]);
        
        $mail = new Email;
        $mail->sendVerifactionCode($user, $verification_code, $verification_code_secret);

        Auth::logout();

        return redirect()->route('app_user_authentication', [
            'secret' => $verification_code_secret,
        ]);
    }

    public function userAuthentication($secret)
    {
        $user = User::where('two_factor_secret', $secret)->first();

        return view('auth.user-authentication', [
            'email' => $user->email,
            'secret' => $secret,
        ]);
    }

    public function resendAuthCodeDv($secret)
    {
       $user = User::where('two_factor_secret', $secret)->first();
       $code = $user->two_factor_recovery_codes;

       $mail = new Email;
       $mail->sendVerifactionCode($user, $code, $secret);

       return back()->with('success', __('auth.code_resend_successully'));
    }

    public function confirmAuth()
    {
        $secret = $this->request->input('secret');
        $code = $this->request->input('verification-code');

        $user = User::where('two_factor_secret', $secret)->first();

        if($code != $user->two_factor_recovery_codes)
        {
            return back()->with([
                'danger' => __('auth.verification_code_is_incorrect'),
                'verification-code-error' => 'verification-code-error',
            ]);
        }
        else
        {
            Auth::loginUsingId($user->id);

            $history = new ConnectionHistoryRepo;
            $history->createHistory($user->id);
            return redirect()->route('app_main');
        }
    }

    public function resetPassword($secret)
    {
        return view('auth.reset-password');
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

    public function addUser(AddUserForm $requestF)
    {
        /*$name = $this->request->input('name');
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
        ]);*/
    }
}
