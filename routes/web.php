<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\FunctionalUnitController;
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
            Route::post('/assign_entreprise_to_user', 'assignEntreUser')->name('app_assign_entreprise_to_user');
            Route::post('/delete_management_entreprise', 'deleteManagementEntr')->name('app_delete_management_entreprise');

            Route::get('/assign_functional_unit_to_user/{id:int}/{idUser:int}', 'assignFunctUser')->name('app_assign_functional_unit_to_user');
        });
        Route::get('/main', 'main')->name('app_main');
        Route::get('/login_history', 'loginHistory')->name('app_login_history');
        Route::get('/all_notification', 'allNotif')->name('app_all_notification');
        Route::get('/unviewed_notifications', 'unviewedNotif')->name('app_unviewed_notifications');
        Route::post('/read_notification', 'readNotif')->name('app_read_notification');
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
    Route::prefix('entreprise')->group(function(){
        Route::middleware('auth')->group(function(){
            Route::middleware('admin')->group(function(){
                Route::get('/create', 'createEntreprise')->name('app_create_entreprise');
                Route::get('/create_functional_unit/{id:int}', 'createFunctionalUnit')->name('app_create_functional_unit');
                Route::get('/update/{id:int}', 'updateEntreprise')->name('app_update_entreprise');
            });
            Route::middleware('entreprise')->group(function(){
                Route::get('/functional_unit/{id:int}', 'entreprise')->name('app_entreprise');
                Route::get('/infos/{id:int}', 'entrepriseInfo')->name('app_entreprise_info_page');
            });
            
            Route::post('/save_entreprise', 'saveEntreprise')->name('app_save_entreprise');
            Route::post('/delete_entreprise', 'deleteEntreprise')->name('app_delete_entreprise');

            Route::post('/add_new_bank_account_entreprise', 'addNewBankAccount')->name('app_add_new_bank_account_entreprise');
            Route::post('/delete_bank_account', 'deleteBankAccount')->name('app_delete_bank_account');

            Route::post('/get_all_devise_json_format', 'getAlldevise')->name('app_get_all_devise_json_format');
        });
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

Route::controller(FunctionalUnitController::class)->group(function(){
    Route::prefix('functional_unit')->group(function(){
        Route::middleware('auth')->group(function(){
            Route::middleware('entreprise')->group(function(){
                Route::middleware('funcUnit')->group(function(){
                    Route::get('/modules/{id:int}/{id2:int}', 'modules')->name('app_modules');
                    Route::get('/infos/{id:int}/{id2:int}', 'fuInfos')->name('app_fu_infos');

                    Route::middleware('admin')->group(function(){
                        Route::get('/update_page/{id:int}/{id2:int}', 'upDatePageFu')->name('app_update_page_fu');
                    });
                });
            });

            Route::middleware('admin')->group(function(){
                Route::post('/save_functional_unit', 'saveFunctionalUnit')->name('app_save_functional_unit');
                Route::post('/delete_functional_unit', 'deleteFunctionalUnit')->name('app_delete_functional_unit');

                Route::post('/assign_fu_to_user', 'assignFUtoUSer')->name('app_assign_fu_to_user');
                Route::post('/delete_management_fu', 'deleteManagementFU')->name('app_delete_management_fu');

                Route::post('/add_new_phone_number_entreprise', 'addNewPhoneNumber')->name('app_add_new_phone_number_entreprise');
                Route::post('/add_new_email_entreprise', 'addNewEmail')->name('app_add_new_email_entreprise');
                
                Route::post('/delete_phone_number_entreprise', 'deletePhoneNumberEntr')->name('app_delete_phone_number_entreprise');
                Route::post('/delete_email_entreprise', 'deleteEmailAddress')->name('app_delete_email_entreprise');
            });
        });
    });
});


Route::controller(DashboardController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::get('/dashboard/{id:int}/{id2:int}', 'dashboard')->name('app_dashboard');
            });
        });
    });
});

Route::controller(CurrencyController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::get('/currency/{id:int}/{id2:int}', 'currency')->name('app_currency');
                Route::get('/create_currency/{id:int}/{id2:int}', 'createCurrency')->name('app_create_currency');
            });
        });

        Route::post('/save_currency', 'saveCurrency')->name('app_save_currency');

    });
});
