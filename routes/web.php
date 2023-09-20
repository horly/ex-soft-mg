<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginController;

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
        Route::get('/reset-password-page/{secret}', 'resetPassword')->name('app_reset_password');
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
        Route::post('/save_entreprise', 'saveEntreprise')->name('app_save_entreprise');
        Route::post('/save_functional_unit', 'saveFunctionalUnit')->name('app_save_functional_unit');

        Route::post('/add_new_phone_number_entreprise', 'addNewPhoneNumber')->name('app_add_new_phone_number_entreprise');
        Route::post('/delete_phone_number_entreprise', 'deletePhoneNumberEntr')->name('app_delete_phone_number_entreprise');

        Route::post('/add_new_email_entreprise', 'addNewEmail')->name('app_add_new_email_entreprise');
        Route::post('/add_new_bank_account_entreprise', 'addNewBankAccount')->name('app_add_new_bank_account_entreprise');
    });
});
