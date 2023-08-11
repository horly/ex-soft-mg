<?php

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
    Route::middleware('auth')->group(function(){
        Route::match(['get', 'post'], '/main', 'main')->name('app_main');
    });
    Route::get('/infos-online-user/{matricule}', 'infosOnlineUser')->name('app_infos_online_user');
});

Route::controller(LoginController::class)->group(function(){
    Route::get('/user_checker', 'userChecker')->name('user_checker');
    Route::get('/logout-user', 'logout')->name('app_logout');
    Route::get('/add_user_page', 'addUserPage')->name('app_add_user_page');
    Route::post('/add_user', 'addUser')->name('app_add_user');
    Route::get('/resend-device-auth-code/{secret}', 'resendAuthCodeDv')->name('app_resend_device_auth_code');
    Route::post('/confirm-authentication', 'confirmAuth')->name('app_confirm_auth');
    Route::middleware('guest')->group(function(){
        Route::match(['get', 'post'], '/user-authentication/{secret}', 'userAuthentication')->name('app_user_authentication');
        Route::match(['get', 'post'], '/reset-password-page/{secret}', 'resetPassword')->name('app_reset_password');
    });
});
