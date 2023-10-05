<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserForm;
use App\Http\Requests\PasswordResetRequestForm;
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
    protected $email;

    function __construct(Request $request, Email $email)
    {
        $this->request = $request;
        $this->email = $email;
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

    public function addUserPage()
    {
        $grades = DB::table('grades')->get();
        $roles = DB::table('roles')->get();


        $countries_gb = DB::table('countries')
                        ->orderBy('name_gb', 'asc')
                        ->get();

        $countries_fr = DB::table('countries')
                        ->orderBy('name_fr', 'asc')
                        ->get();

        return view('auth.add_user_page', [
            'grades' => $grades,
            'roles' => $roles,
            'countries_gb' => $countries_gb,
            'countries_fr' => $countries_fr,
        ]);
    }

    public function addUser(AddUserForm $requestF)
    {
        $name = $requestF->input('firstName') . " " . $requestF->input('lastName');
        $email = $requestF->input('emailUsr');
        //$password = $requestF->input('passwordUsr');
        $role = $requestF->input('role');
        $grade = $requestF->input('function');
        $phone_number = $requestF->input('phoneNumber');
        $countryUsr = $requestF->input('countryUsr');
        $address = $requestF->input('address');
        $matricule = $requestF->input('matricule');

        $userExit = DB::table('users')
                        ->where('email', $email)
                        ->first();
        if(!$userExit)
        {
            /**
             * on génère un mot de passe de 8 caratère 
             */
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 10; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $password = implode($pass); //turn the array into a string

            //dd($password);


            $array = array(
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $role,
                'grade_id' => $grade,
                'id_country' => $countryUsr,
                'phone_number' => $phone_number,
                'matricule' => $matricule,
                'address' => $address
            );

            $userP1 = User::create($array);

            $userExp = Auth::user();

            //on génère de nombre aleotoire de 6 chiffres si l'utilisateur choisie de copier coller le code
            $verification_code = "";
            $longeur_code = 6;
            $verification_code_secret = md5(uniqid()) . $userP1->id . sha1($userP1->email);

            for($i = 0; $i < $longeur_code; $i++)
            {
                $verification_code .= mt_rand(0,9);
            }

            DB::table('users')
                ->where('id', $userP1->id)
                ->update([
                    'two_factor_secret' => $verification_code_secret,
                    'two_factor_recovery_codes' => $verification_code,
            ]);

            $userP2 = DB::table('users')->where('id', $userP1->id)->first();

            $this->email->inviteUser($userP2, $userExp, $password);

            return redirect()->route('app_user_management')->with('success', __('main.user_added'));
        }
        else
        {
            return redirect()->back()->withErrors([
                'emailUsr' => __('auth.this_email_address_is_already_used')
            ])->withInput();
        }
    }

    public function emailResetPasswordRequest()
    {
        return view('auth.email-to-reset-password-request');
    }

    public function emailResetPasswordPost(PasswordResetRequestForm $requestF)
    {
        $emailPassReq = $requestF->input('emailPassReq');

        $user = DB::table('users')
                ->where('email', $emailPassReq)
                ->first();

        if($user)
        {
            $this->email->changePasswordRequest($user);

            return redirect()->back()->with('success', __('profile.your_password_change_request_has_been'))->withInput();
        }
        else
        {
            return redirect()->back()
                ->withErrors([
                    'emailPassReq' => __('auth.this_email_address_does_not_correspond_to_any_user')
                ])
                ->withInput();
        }
    }
}
