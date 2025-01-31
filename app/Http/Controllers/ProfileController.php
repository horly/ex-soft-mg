<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailAddressForm;
use App\Http\Requests\changePasswordForm;
use App\Http\Requests\UpdateProfileInfoForm;
use App\Models\Notification;
use App\Models\ReadNotif;
use App\Repository\NotificationRepo;
use App\Services\Email\Email;
use App\Services\Server\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    protected $request;
    protected $email;
    protected $notificationRepo;
    protected $server;

    function __construct(Request $request, Email $email, NotificationRepo $notificationRepo, Server $server)
    {
        $this->request = $request;
        $this->email = $email;
        $this->notificationRepo = $notificationRepo;
        $this->server = $server;
    }

    public function profile()
    {
        return view('profile.profile');
    }

    public function emailPassword()
    {
        return view('profile.email-password');
    }

    public function savePhoto()
    {
        $image = $this->request->input('image-saved');
        $type = $this->request->input('type-photo');
        $id_entreprise = $this->request->input('id-entreprise');
        $id_user = $this->request->input('id-user');

        $public_path = $this->server->getPublicFolder();

        if($type == "entreprise")
        {
            //on hashe uplodad_profile + le md5 uniqid + l'id de l'utilisateur
            $image_hash = 'upload_profile' . md5(uniqid()) . $id_entreprise;
            //$folderPath = base_path() . '/public_html/assets/img/logo/entreprise/';

            $folderPath = $public_path . '/assets/img/logo/entreprise/';

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $image_hash . '.png';
            file_put_contents($file, $image_base64);

            DB::table('entreprises')
                ->where('id', $id_entreprise)
                ->update([
                'url_logo' => $image_hash,
                'url_logo_base64' => $image,
                'updated_at' => new \DateTimeImmutable
            ]);

            $url = route('app_entreprise_info_page', ['id' => $id_entreprise]);
            $description = "entreprise.modified_the_company_logo";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->back()->with('success', __('entreprise.photo_saved_successfully'));
        }
        else if($type == "user")
        {
            //on hashe uplodad_profile + le md5 uniqid + l'id de l'utilisateur
            $image_hash = 'upload_profile' . md5(uniqid()) . $id_user;
            //$folderPath = base_path() . '/public_html/assets/img/profile/';
            $folderPath = $public_path . '/assets/img/profile/';

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $image_hash . '.png';
            file_put_contents($file, $image_base64);

            DB::table('users')
                ->where('id', $id_user)
                ->update([
                'photo_profile_url' => $image_hash,
                'photo_profile_base64' => $image,
                'updated_at' => new \DateTimeImmutable
            ]);

            return redirect()->back()->with('success', __('entreprise.photo_saved_successfully'));
        }
        else if($type == "signature")
        {
            //on hashe uplodad_profile + le md5 uniqid + l'id de l'utilisateur
            $image_hash = 'SIGN_' . $id_user;
            //$folderPath = base_path() . '/public_html/assets/img/profile/';
            $folderPath = $public_path . '/assets/img/invoice/signature/';

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $image_hash . '.jpg';
            file_put_contents($file, $image_base64);

            DB::table('users')
                ->where('id', $id_user)
                ->update([
                'signature_id' => $id_user,
                'updated_at' => new \DateTimeImmutable
            ]);

            return redirect()->back()->with('success', __('invoice.signature_registered_successfully'));
        }
        else
        {
            //on hashe uplodad_profile + le md5 uniqid + l'id de l'utilisateur
            $image_hash = 'SEAL_' . $id_user;
            //$folderPath = base_path() . '/public_html/assets/img/profile/';
            $folderPath = $public_path . '/assets/img/invoice/seal/';

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $image_hash . '.jpg';
            file_put_contents($file, $image_base64);

            DB::table('users')
                ->where('id', $id_user)
                ->update([
                'seal_id' => $id_user,
                'updated_at' => new \DateTimeImmutable
            ]);

            return redirect()->back()->with('success', __('invoice.seal_registered_successfully'));
        }
    }

    public function editProfileInfo()
    {

        $grades = DB::table('grades')->get();

        $countries_gb = DB::table('countries')
                        ->orderBy('name_gb', 'asc')
                        ->get();

        $countries_fr = DB::table('countries')
                        ->orderBy('name_fr', 'asc')
                        ->get();

        return view('profile.edit-profile', compact('grades', 'countries_gb', 'countries_fr'));
    }

    public function saveProfileInfo(UpdateProfileInfoForm $requestF)
    {
        $user = Auth::user();
        $name_profile = $requestF->input('name_profile');
        $function_profile = $requestF->input('function_profile');
        $country_profile = $requestF->input('country_profile');
        $phone_number_profile = $requestF->input('phone_number_profile');
        $registration_number_profile = $requestF->input('registration_number_profile');
        $address_profile = $requestF->input('address_profile');

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $name_profile,
                'grade_id' => 1,
                'grade' => $function_profile,
                'id_country' => $country_profile,
                'phone_number' => $phone_number_profile,
                'matricule' => $registration_number_profile,
                'address' => $address_profile,
                'updated_at' => new \DateTimeImmutable
            ]);

        return redirect()->route('app_profile')->with('success', __('profile.information_updated_successfully'));
    }

    public function changeEmailAddressRequest($token)
    {
        $user = DB::table('users')
                    ->where('two_factor_secret', $token)
                    ->first();

        $this->email->changeEmailAdressRequest($user);

        return redirect()->back()->with('success', __('profile.change_email_address_request_message'));
    }

    public function changeEmailAddress($token)
    {
        $user = Auth::user();

        if($user)
        {
            Auth::logout();
        }

        return view('profile.change-email-address', compact('token'));
    }

    public function changeEmailAddressPost(ChangeEmailAddressForm $requestF)
    {
        $current_email = $requestF->input('current_email');
        $new_email = $requestF->input('new_email');
        //$confirm_new_email = $requestF->input('confirm_new_email');
        $password_new_email = $requestF->input('password_new_email');
        $token = $requestF->input('token');

        $user = DB::table('users')
                    ->where('two_factor_secret', $token)
                    ->first();

        $email = $user->email;
        $password = $user->password;
        $id = $user->id;

        if($current_email == $email)
        {
            if($new_email != $current_email)
            {
                /**
                 *  vérifier qu'une chaîne de texte en clair donnée correspond à un hachage donné :
                 */
                if(Hash::check($password_new_email, $password))
                {
                    DB::table('users')
                        ->where('id', $id)
                        ->update([
                            'email' => $new_email,
                            'updated_at' => new \DateTimeImmutable
                        ]);

                        return redirect('/login')->with('success', __('auth.email_address_was_successfully_updated'));
                }
                else
                {
                    return redirect()->back()->withErrors([
                        'password_new_email' => __('auth.the_password_is_not_correct'),
                    ])->withInput();
                }
            }
            else
            {
                return redirect()->back()->withErrors([
                    'new_email' => __('auth.the_new_email_address_must_be_different_from_the_old_one'),
                ])->withInput();
            }
        }
        else
        {
            return redirect()->back()->withErrors([
                'current_email' => __('auth.your_current_email_address_is_incorrect'),
            ])->withInput();
        }
    }

    public function changePasswordRequest($token)
    {
        $user = DB::table('users')
                ->where('two_factor_secret', $token)
                ->first();

        $this->email->changePasswordRequest($user);

        return redirect()->back()->with('success', __('profile.your_password_change_request_has_been'));
    }

    public function resetPassword($secret)
    {
        $user = Auth::user();

        if($user)
        {
            Auth::logout();
        }

        return view('profile.reset-password', compact('secret'));
    }

    public function changePasswordPost(changePasswordForm $requestF)
    {
        $new_password = $requestF->input('new_password');

        $token = $requestF->input('token');

        $user = DB::table('users')
                    ->where('two_factor_secret', $token)
                    ->first();

        $id = $user->id;

        DB::table('users')
            ->where('id', $id)
            ->update([
                'password' => Hash::make($new_password),
                'updated_at' => new \DateTimeImmutable
        ]);

        return redirect('/login')->with('success', __('profile.your_password_has_been_successfully_updated'));
    }
}
