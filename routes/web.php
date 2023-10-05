<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Translator route
 */
Route::get('/lang/{lang}',
    [LanguageController::class, 'switchLang'])
        ->name('app_language');

Route::get('/', function () {
    return view('auth.login');
});

Route::controller(HomeController::class)->group(function(){
    Route::get('/infos-online-user/{matricule}', 'infosOnlineUser')->name('app_infos_online_user');
    Route::middleware('auth')->group(function(){
        Route::middleware('admin')->group(function(){
            Route::get('/user_management', 'userManagement')->name('app_user_management');
            Route::get('/user_management_info/{id:int}', 'userManagementInfo')->name('app_user_management_info');
            Route::post('/delete_user', 'deleteUser')->name('app_delete_user');
        });
        Route::get('/main', 'main')->name('app_main');
        Route::get('/login_history', 'loginHistory')->name('app_login_history');
    });
});

Route::controller(LoginController::class)->group(function(){
    Route::get('/user_checker', 'userChecker')->name('user_checker');
    Route::get('/logout-user', 'logout')->name('app_logout');
    Route::post('/add_user', 'addUser')->name('app_add_user');
    Route::get('/resend-device-auth-code/{secret}', 'resendAuthCodeDv')->name('app_resend_device_auth_code');
    Route::post('/confirm-authentication', 'confirmAuth')->name('app_confirm_auth');
    Route::middleware('auth')->group(function(){
        Route::middleware('admin')->group(function(){
            Route::get('/add_user_page', 'addUserPage')->name('app_add_user_page');
        });
    });
    Route::middleware('guest')->group(function(){
        Route::get('/user-authentication/{secret}', 'userAuthentication')->name('app_user_authentication');
        Route::get('/email_reset_password_request', 'emailResetPasswordRequest')->name('app_email_reset_password_request');
        Route::post('/email_reset_password_post', 'emailResetPasswordPost')->name('app_email_reset_password_post');
    });
});

Route::controller(EntrepriseController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('admin')->group(function(){
            Route::get('/create_entreprise', 'createEntreprise')->name('app_create_entreprise');
            Route::get('/create_functional_unit/{id:int}', 'createFunctionalUnit')->name('app_create_functional_unit');
            Route::get('/update_entreprise/{id:int}', 'updateEntreprise')->name('app_update_entreprise');
        });
        Route::get('/entreprise/{id:int}', 'entreprise')->name('app_entreprise');
        Route::get('/entreprise_info_page/{id:int}', 'entrepriseInfo')->name('app_entreprise_info_page');

        Route::post('/save_entreprise', 'saveEntreprise')->name('app_save_entreprise');
        Route::post('/delete_entreprise', 'deleteEntreprise')->name('app_delete_entreprise');

        Route::post('/save_functional_unit', 'saveFunctionalUnit')->name('app_save_functional_unit');

        Route::post('/add_new_phone_number_entreprise', 'addNewPhoneNumber')->name('app_add_new_phone_number_entreprise');
        Route::post('/delete_phone_number_entreprise', 'deletePhoneNumberEntr')->name('app_delete_phone_number_entreprise');

        Route::post('/add_new_email_entreprise', 'addNewEmail')->name('app_add_new_email_entreprise');
        Route::post('/delete_email_entreprise', 'deleteEmailAddress')->name('app_delete_email_entreprise');

        Route::post('/add_new_bank_account_entreprise', 'addNewBankAccount')->name('app_add_new_bank_account_entreprise');
        Route::post('/delete_bank_account', 'deleteBankAccount')->name('app_delete_bank_account');

        Route::post('/get_all_devise_json_format', 'getAlldevise')->name('app_get_all_devise_json_format');
    });
});

Route::controller(ProfileController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::get('/profile', 'profile')->name('app_profile');
        //Route::get('/email_password', 'emailPassword')->name('app_email_password');
        Route::get('/edit_profile_info', 'editProfileInfo')->name('app_edit_profile_info');
        
        Route::post('/save_photo', 'savePhoto')->name('app_save_photo');
        Route::post('/save_profile_info', 'saveProfileInfo')->name('app_save_profile_info');
    });

    Route::post('/change_email_address_post', 'changeEmailAddressPost')->name('app_change_email_address_post');
    Route::post('/change_password_post', 'changePasswordPost')->name('app_change_password_post');
    Route::get('/reset-password-page/{secret}', 'resetPassword')->name('app_reset_password');
    Route::get('/change_email_address/{token}', 'changeEmailAddress')->name('app_change_email_address');
    Route::get('/change_email_address_request/{token}', 'changeEmailAddressRequest')->name('app_change_email_address_request');
    Route::get('/change_password_request/{token}', 'changePasswordRequest')->name('app_change_password_request');
});
