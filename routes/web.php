<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CreditorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\DomPdfController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FpdfController;
use App\Http\Controllers\FunctionalUnitController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentMethodesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SupplierController;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\DB;

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

Route::middleware('guest')->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    });
});

/*Route::get('/', function () {
        return view('auth.login');
});*/

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
            Route::get('/permissions/{id_user:int}/{id_fu:int}', 'permissions')->name('app_permissions');
        });
        Route::get('/main', 'main')->name('app_main');
        Route::get('/login_history', 'loginHistory')->name('app_login_history');
        Route::get('/all_notification', 'allNotif')->name('app_all_notification');
        Route::get('/unviewed_notifications', 'unviewedNotif')->name('app_unviewed_notifications');
        Route::post('/read_notification', 'readNotif')->name('app_read_notification');
        Route::post('/get_permission', 'get_permission')->name('app_get_permission');
        Route::post('/save_permissions', 'save_permissions')->name('app_save_permissions');
        Route::post('/save_contact_permissions', 'save_contact_permissions')->name('app_save_contact_permissions');
        Route::post('/save_stock_permissions', 'save_stock_permissions')->name('app_save_stock_permissions');
        Route::post('/save_service_permissions', 'save_service_permissions')->name('app_save_service_permissions');
        Route::post('/save_currency_permissions', 'save_currency_permissions')->name('app_save_currency_permissions');
        Route::post('/save_payment_method_permissions', 'save_payment_method_permissions')->name('app_save_payment_method_permissions');
        Route::post('/save_debt_permissions', 'save_debt_permissions')->name('app_save_debt_permissions');
        Route::post('/save_receivable_permissions', 'save_receivable_permissions')->name('app_save_receivable_permissions');
        Route::post('/save_sales_permissions', 'save_sales_permissions')->name('app_save_sales_permissions');
        Route::post('/save_expense_permissions', 'save_expense_permissions')->name('app_save_expense_permissions');
        Route::post('/save_report_permissions', 'save_report_permissions')->name('app_save_report_permissions');
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
                Route::get('/dashboard/{group:string}/{id:int}/{id2:int}', 'dashboard')->name('app_dashboard');
            });
        });

        Route::post('/change-devise-view-global', 'changeDevGl')->name('app_change_devise_view_global');
        Route::post('/income-global', 'incomeGlobal')->name('app_income_global');
        Route::post('/set-year-evolution', 'set_year_evolution')->name('app_set_year_evolution');
    });
});

Route::controller(CurrencyController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/currency/{group:string}/{id:int}/{id2:int}', 'currency')->name('app_currency');
                    Route::get('/create_currency/{group:string}/{id:int}/{id2:int}', 'createCurrency')->name('app_create_currency');
                    Route::get('/info_currency/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoCurrency')->name('app_info_currency');
                    Route::get('/update_currency/{group:string}/{id:int}/{id2:int}/{id3:int}', 'upDatecurrency')->name('app_update_currency');
                });
            });
        });

        Route::post('/save_currency', 'saveCurrency')->name('app_save_currency');
        Route::post('/change_default_currency', 'changeDefaultcurrency')->name('app_change_default_currency');
        Route::post('/delete_currency', 'deleteCurrency')->name('app_delete_currency');

    });
});

Route::controller(CustomerController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/customer/{group:string}/{id:int}/{id2:int}', 'customer')->name('app_customer');
                    Route::get('/add_new_client/{group:string}/{id:int}/{id2:int}', 'addNewClient')->name('app_add_new_client');
                    Route::get('/info_customer/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoCustomer')->name('app_info_customer');
                    Route::get('/update_customer/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateCustomer')->name('app_update_customer');
                });
            });
        });

        //
        Route::post('/create_client', 'createClient')->name('app_create_client');
        Route::post('/delete_client', 'deleteClient')->name('app_delete_client');

        Route::post('/add_new_contact_client', 'addNewContactClient')->name('app_add_new_contact_client');
    });
});

Route::controller(SupplierController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/supplier/{group:string}/{id:int}/{id2:int}', 'supplier')->name('app_supplier');
                    Route::get('/add_new_supplier/{group:string}/{id:int}/{id2:int}', 'addNewSupplier')->name('app_add_new_supplier');
                    Route::get('/info_supplier/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoSupplier')->name('app_info_supplier');
                    Route::get('/update_supplier/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateSupplier')->name('app_update_supplier');
                });
            });
        });

        //
        Route::post('/create_supplier', 'createSupplier')->name('app_create_supplier');
        Route::post('/delete_supplier', 'deleteSupplier')->name('app_delete_supplier');
    });
});


Route::controller(CreditorController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/creditor/{group:string}/{id:int}/{id2:int}', 'creditor')->name('app_creditor');
                    Route::get('/add_new_creditor/{group:string}/{id:int}/{id2:int}', 'addNewCreditor')->name('app_add_new_creditor');
                    Route::get('/info_creditor/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoCreditor')->name('app_info_creditor');
                    Route::get('/update_creditor/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateCreditor')->name('app_update_creditor');
                });
            });
        });

        //
        Route::post('/create_creditor', 'createCreditor')->name('app_create_creditor');
        Route::post('/delete_creditor', 'deleteCreditor')->name('app_delete_creditor');
    });
});

Route::controller(DebtorController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/debtor/{group:string}/{id:int}/{id2:int}', 'debtor')->name('app_debtor');
                    Route::get('/add_new_debtor/{group:string}/{id:int}/{id2:int}', 'addNewDebtor')->name('app_add_new_debtor');
                    Route::get('/info_debtor/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoDebtor')->name('app_info_debtor');
                    Route::get('/update_debtor/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateDebtor')->name('app_update_debtor');
                });
            });
        });

        //
        Route::post('/create_debtor', 'createDebtor')->name('app_create_debtor');
        Route::post('/delete_debtor', 'deleteDebtor')->name('app_delete_debtor');
    });
});

Route::controller(ArticleController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    /**
                     * Article Category
                     */
                    Route::get('/category_article/{group:string}/{id:int}/{id2:int}', 'categoryArticle')->name('app_category_article');
                    Route::get('/add_new_category_article/{group:string}/{id:int}/{id2:int}', 'addNewCategoryArticle')->name('app_add_new_category_article');
                    Route::get('/info_article_category/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoArticleCategory')->name('app_info_article_category');
                    Route::get('/update_article_category/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateArticleCategory')->name('app_update_article_category');
                    /**
                     * Article Subcategory
                     */
                    Route::get('/subcategory_article/{group:string}/{id:int}/{id2:int}', 'subCategoryArticle')->name('app_subcategory_article');
                    Route::get('/add_new_subcategory_article/{group:string}/{id:int}/{id2:int}', 'addNewSubCategoryArticle')->name('app_add_new_subcategory_article');
                    Route::get('/info_article_subcategory/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoArticleSubCategory')->name('app_info_article_subcategory');
                    Route::get('/update_article_subcategory/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateArticleSubCategory')->name('app_update_article_subcategory');
                    /**
                     * Article
                     */
                    Route::get('/article/{group:string}/{id:int}/{id2:int}', 'article')->name('app_article');
                    Route::get('/add_new_article/{group:string}/{id:int}/{id2:int}', 'addNewArticle')->name('app_add_new_article');
                    Route::get('/info_article/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoArticle')->name('app_info_article');
                    Route::get('/update_article/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateArticle')->name('app_update_article');
                });
            });
        });

        /**
         * Article Category
         */
        Route::post('/create_category_article', 'createCategoryArticle')->name('app_create_category_article');
        Route::post('/delete_category_article', 'deleteCategoryArticle')->name('app_delete_category_article');
        /**
         * Article Subcategory
         */
        Route::post('/create_subcategory_article', 'createSubCategoryArticle')->name('app_create_subcategory_article');
        Route::post('/delete_subcategory_article', 'deleteSubCategoryArticle')->name('app_delete_subcategory_article');
        /**
         * Article
         */
        Route::post('/create_article', 'createArticle')->name('app_create_article');
        Route::post('/delete_article', 'deleteArticle')->name('app_delete_article');
    });
});

Route::controller(ServiceController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    /**
                     * Service Category
                     */
                    Route::get('/category_service/{group:string}/{id:int}/{id2:int}', 'categoryService')->name('app_category_service');
                    Route::get('/add_new_category_service/{group:string}/{id:int}/{id2:int}', 'addNewCategoryService')->name('app_add_new_category_service');
                    Route::get('/info_service_category/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoServiceCategory')->name('app_info_service_category');
                    Route::get('/update_service_category/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateServiceCategory')->name('app_update_service_category');
                    /**
                     * Service
                     */
                    Route::get('/service/{group:string}/{id:int}/{id2:int}', 'service')->name('app_service');
                    Route::get('/add_new_service/{group:string}/{id:int}/{id2:int}', 'addNewService')->name('app_add_new_service');
                    Route::get('/info_service/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoService')->name('app_info_service');
                    Route::get('/update_service/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateService')->name('app_update_service');
                });
            });
        });

        /**
         * Service Category
         */
        Route::post('/create_category_service', 'createCategoryService')->name('app_create_category_service');
        Route::post('/delete_category_service', 'deleteCategoryService')->name('app_delete_category_service');
        /**
         * Service
         */
        Route::post('/create_service', 'createService')->name('app_create_service');
        Route::post('/delete_service', 'deleteService')->name('app_delete_service');
    });
});


Route::controller(PaymentMethodesController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/payment_methods/{group:string}/{id:int}/{id2:int}', 'paymentMethods')->name('app_payment_methods');
                    Route::get('/add_new_payment_methods/{group:string}/{id:int}/{id2:int}', 'addNewPaymentMethods')->name('app_add_new_payment_methods');
                    Route::get('/info_payment_methods/{group:string}/{id:int}/{id2:int}/{id3:int}', 'infoPaymentMethods')->name('app_info_payment_methods');
                    Route::get('/update_payment_methods/{group:string}/{id:int}/{id2:int}/{id3:int}', 'upDatePaymentMethods')->name('app_update_payment_methods');
                });
            });
        });

        Route::post('/create_payment_methods', 'createPaymentMethods')->name('app_create_payment_methods');
        Route::post('/delete_payment_methods', 'deletePaymentMethods')->name('app_delete_payment_methods');

    });
});

Route::controller(SalesInvoiceController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){
                    Route::get('/sales_invoice/{group:string}/{id:int}/{id2:int}', 'salesInvoice')->name('app_sales_invoice');
                    Route::get('/add_new_sales_invoice/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'addNewSalesInvoice')->name('app_add_new_sales_invoice');
                    Route::get('/info_sales_invoice/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'infoSalesInvoice')->name('app_info_sales_invoice');
                    Route::get('/update_sales_invoice/{id:int}/{id2:int}/{id3:int}', 'upDateSalesInvoice')->name('app_update_sales_invoice');

                    Route::get('/proforma/{group:string}/{id:int}/{id2:int}', 'proforma')->name('app_proforma');
                    Route::get('/add_new_proforma/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'addNewProforma')->name('app_add_new_proforma');
                    Route::get('/info_proforma/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'infoProforma')->name('app_info_proforma');
                    Route::get('/update_proforma/{group:string}/{id:int}/{id2:int}/{id3:int}', 'updateProforma')->name('app_update_proforma');

                    Route::get('/delivery_note/{group:string}/{id:int}/{id2:int}', 'deliveryNote')->name('app_delivery_note');
                    Route::get('/add_new_delivery_note/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'addNewDeliveryNote')->name('app_add_new_delivery_note');
                    Route::get('/info_delivery_note/{group:string}/{id:int}/{id2:int}/{ref_invoice:string}', 'infoDeliveryNote')->name('app_info_delivery_note');

                    Route::get('/entrances/{group:string}/{id:int}/{id2:int}', 'entrances')->name('app_entrances');
                    Route::get('/add_new_entrance/{group:string}/{id:int}/{id2:int}/{ref_entrance:string}', 'add_new_entrance')->name('app_add_new_entrance');

                    Route::prefix('invoice_settings')->group(function(){
                        Route::get('/signature/{group:string}/{id:int}/{id2:int}', 'signature')->name('app_signature');
                        Route::get('seal/{group:string}/{id:int}/{id2:int}', 'seal')->name('app_seal');
                    });
                });
            });
        });

        Route::post('/setup_invoice', 'setUpInvoice')->name('app_setup_invoice');
        Route::post('/create_sales_invoice', 'createSalesInvoice')->name('app_create_sales_invoice');
        Route::post('/delete_sales_invoice', 'deleteSalesInvoice')->name('app_delete_sales_invoice');
        Route::post('/calculate_margin', 'calculateMargin')->name('app_calculate_margin');
        Route::post('/insert_invoice_element', 'insertInvoiceElemebt')->name('app_insert_invoice_element');
        Route::post('/delete_invoice_element', 'deleteInvoiceElement')->name('app_delete_invoice_element');
        Route::post('/change_vat', 'changeVat')->name('app_change_vat');
        Route::post('/change_discount_customer', 'changeDiscountCustomer')->name('app_change_discount_customer');
        Route::post('/save_sale_invoice', 'saveSaleInvoice')->name('app_save_sale_invoice');
        Route::post('/check_records_amount_invoice', 'checkRecordsAmountInvoice')->name('app_check_records_amount_invoice');
        Route::post('/save_record_payment', 'saveRecordPayment')->name('app_save_record_payment');

        Route::post('/transform_invoice_simple', 'transformInvoiceSimple')->name('app_transform_invoice_simple');
        Route::post('/get_contact_client_invoice', 'getContactClientinvoice')->name('app_get_contact_client_invoice');
        Route::post('/add_serial_number_invoice', 'addSerialNumberinvoice')->name('app_add_serial_number_invoice');
        Route::post('/delete_serial_number_invoice', 'deleteSerialNumberInvoice')->name('app_delete_serial_number_invoice');
        Route::post('/generate_delivery_note', 'generateDeliveryNote')->name('app_generate_delivery_note');

        Route::post('/setup_enrance', 'setup_enrance')->name('app_setup_enrance');
        Route::post('/save_entrance', 'save_entrance')->name('app_save_entrance');

        Route::post('/add_note_invoice', 'add_note_invoice')->name('app_add_note_invoice');
        Route::post('/delete_note_invoice', 'delete_note_invoice')->name('app_delete_note_invoice');
    });
});

/*Route::controller(FpdfController::class)->group(function(){
    Route::get('/invoice_pdf/{id:int}/{id2:int}/{ref_invoice:string}', 'invoicePdf')->name('app_invoice_pdf');
    Route::get('/invoice_pdf/{id:int}/{id2:int}/{ref_invoice:string}', function (Codedge\Fpdf\Fpdf\Fpdf $fpdf, $id, $id2) {

        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $fpdf->AddPage();
        $fpdf->SetFont('Courier', 'B', 18);


        $fpdf->Cell(50, 25, 'Hello World!');
        $fpdf->Output();
        exit;

    });
});*/

Route::controller(DomPdfController::class)->group(function(){
    Route::get('/invoice_pdf/{id:int}/{id2:int}/{ref_invoice:string}', 'invoicePdf')->name('app_invoice_pdf');
    Route::get('/delivery_note_pdf/{id:int}/{id2:int}/{ref_invoice:string}', 'deliveryNotePdf')->name('app_delivery_note_pdf');
});

Route::controller(ExpensesController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                    Route::middleware('menu_access')->group(function(){
                    Route::get('/purchases/{group:string}/{id:int}/{id2:int}', 'purchases')->name('app_purchases');
                    Route::get('/add_new_purchase/{group:string}/{id:int}/{id2:int}/{ref_purchase:string}', 'addNewPurchase')->name('app_add_new_purchase');
                    Route::get('/update_purchase/{group:string}/{id:int}/{id2:int}/{ref_purchase:string}', 'updatePurchase')->name('app_update_purchase');

                    Route::get('/expenses/{group:string}/{id:int}/{id2:int}', 'expenses')->name('app_expenses');
                    Route::get('/add_new_expense/{group:string}/{id:int}/{id2:int}/{ref_expense:string}', 'addNewExpense')->name('app_add_new_expense');
                });
            });
        });

        Route::post('/setup_purchase', 'setUpPurchase')->name('app_setup_purchase');
        Route::post('/upload_purchase_pdf', 'uploadPurchasePdf')->name('app_upload_purchase_pdf');
        Route::post('/save_purchase', 'savePurchase')->name('app_save_purchase');
        Route::post('/delete_purchase', 'deletePurchase')->name('app_delete_purchase');
        Route::post('/delete_purchase_file', 'delete_purchase_file')->name('app_delete_purchase_file');

        Route::post('/setup_expense', 'setUpExpense')->name('app_setup_expense');
        Route::post('/save_expense', 'saveExpense')->name('app_save_expense');
    });
});


Route::controller(DebtController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){

                });
            });
        });
    });
});

Route::controller(ReceivableController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){

                });
            });
        });
    });
});

Route::controller(ReportController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('entreprise')->group(function(){
            Route::middleware('funcUnit')->group(function(){
                Route::middleware('menu_access')->group(function(){

                });
            });
        });
    });
});

Route::controller(SuperAdminController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::middleware('super_admin')->group(function(){
            Route::prefix('super_admin')->group(function(){
                Route::get('/super_admin_dashboard', 'super_admin_dashboard')->name('app_super_admin_dashboard');
                Route::get('/subscription', 'subscription')->name('app_subscription');
                Route::get('/user', 'user_super_admin')->name('app_user_super_admin');
                Route::get('/add_subscription/{id:int}', 'add_subscription')->name('app_add_subscription');
                Route::get('/add_user_admin/{id:int}', 'add_user_admin')->name('app_add_user_admin');
            });

            Route::post('/create_subscription', 'create_subscription')->name('app_create_subscription');
            Route::post('/delete_subscription', 'delete_subscription')->name('app_delete_subscription');
        });
    });
});
