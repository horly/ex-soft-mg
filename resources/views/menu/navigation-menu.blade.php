@php
    $customer_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'customer'
    ])->first();

    $supplier_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'supplier'
    ])->first();

    $creditor_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'creditor'
    ])->first();

    $debtor_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'debtor'
    ])->first();

    $stock_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'stock'
    ])->first();

    $service_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'service'
    ])->first();

    $currency_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'currency'
    ])->first();

    $payment_method_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'payment_method'
    ])->first();

    $debt_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'debt'
    ])->first();

    $receivable_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'receivable'
    ])->first();

    $sale_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'sale'
    ])->first();

    $expense_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'expense'
    ])->first();

    $report_assign = DB::table('permission_assigns')
        ->where([
            'id_user' => Auth::user()->id,
            'id_fu' => $functionalUnit->id,
            //'id_perms' => $permission->id,
            'group' => 'report'
    ])->first();

@endphp


<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="#">
                        @if (config('app.name') == "EXADERP")
                            <img src="{{ asset('assets/img/logo/exad-logo.png') }}" id="logo-exad-erp" alt="Logo" srcset="">
                        @else
                            <img src="{{ asset('assets/img/logo/Prestavice-logo-erp1.png') }}" id="logo-presta" alt="Logo" srcset="">
                        @endif
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                {{-- Start Dashboard --}}
                <li class="sidebar-item @if (Request::route()->getName() == "app_dashboard") active @endif">
                    <a href="{{ route('app_dashboard', ['group' => 'global', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('dashboard.dashboard') }}</span>
                    </a>
                </li>
                {{-- Start Dashboard --}}

                {{-- Start Contact --}}
                @if ($customer_assign || $supplier_assign || $creditor_assign || $debtor_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-item @if(Request::route()->getName() == "app_customer" ||
                                            Request::route()->getName() == "app_add_new_client" ||
                                            Request::route()->getName() == "app_info_customer" ||
                                            Request::route()->getName() == "app_update_customer" ||

                                            Request::route()->getName() == "app_supplier" ||
                                            Request::route()->getName() == "app_add_new_supplier" ||
                                            Request::route()->getName() == "app_info_supplier" ||
                                            Request::route()->getName() == "app_update_supplier" ||

                                            Request::route()->getName() == "app_creditor" ||
                                            Request::route()->getName() == "app_add_new_creditor" ||
                                            Request::route()->getName() == "app_info_creditor" ||
                                            Request::route()->getName() == "app_update_creditor" ||

                                            Request::route()->getName() == "app_debtor" ||
                                            Request::route()->getName() == "app_add_new_debtor" ||
                                            Request::route()->getName() == "app_info_debtor" ||
                                            Request::route()->getName() == "app_update_debtor")
                                            active
                                        @endif has-sub">

                        <a href="#" class='sidebar-link'>
                            <i class="fa-solid fa-circle-user"></i>
                            <span>{{ __('dashboard.my_contacts') }}</span>
                        </a>

                        <ul class="submenu @if(Request::route()->getName() == "app_customer" ||
                                                Request::route()->getName() == "app_add_new_client" ||
                                                Request::route()->getName() == "app_info_customer" ||
                                                Request::route()->getName() == "app_update_customer" ||

                                                Request::route()->getName() == "app_supplier" ||
                                                Request::route()->getName() == "app_add_new_supplier" ||
                                                Request::route()->getName() == "app_info_supplier" ||
                                                Request::route()->getName() == "app_update_supplier" ||

                                                Request::route()->getName() == "app_creditor" ||
                                                Request::route()->getName() == "app_add_new_creditor" ||
                                                Request::route()->getName() == "app_info_creditor" ||
                                                Request::route()->getName() == "app_update_creditor" ||

                                                Request::route()->getName() == "app_debtor" ||
                                                Request::route()->getName() == "app_add_new_debtor" ||
                                                Request::route()->getName() == "app_info_debtor" ||
                                                Request::route()->getName() == "app_update_debtor")
                                                active
                                            @endif">

                            @if ($customer_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                                <li class="submenu-item @if(Request::route()->getName() == "app_customer") active @endif">
                                    <a href="{{ route('app_customer', ['group' => 'customer', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                        {{ __('dashboard.customer') }}
                                    </a>
                                </li>
                            @endif

                            @if ($supplier_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                                <li class="submenu-item @if(Request::route()->getName() == "app_supplier") active @endif">
                                    <a href="{{ route('app_supplier', ['group' => 'supplier', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                        {{ __('dashboard.supplier') }}
                                    </a>
                                </li>
                            @endif

                            @if ($creditor_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                                <li class="submenu-item @if(Request::route()->getName() == "app_creditor") active @endif">
                                    <a href="{{ route('app_creditor', ['group' => 'creditor', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                        {{ __('dashboard.creditors') }}
                                    </a>
                                </li>
                            @endif

                            @if ($debtor_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                                <li class="submenu-item @if(Request::route()->getName() == "app_debtor") active @endif">
                                    <a href="{{ route('app_debtor', ['group' => 'debtor', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                        {{ __('dashboard.debtors') }}
                                    </a>
                                </li>
                            @endif

                            {{--
                            <li class="submenu-item ">
                                <a href="#">{{ __('dashboard.vat_administration') }}</a>
                            </li>
                            --}}
                        </ul>
                    </li>
                @endif
                {{-- End Contact --}}

                {{-- Start Stock --}}
                @if ($stock_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-item @if(Request::route()->getName() == "app_category_article" ||
                                            Request::route()->getName() == "app_add_new_category_article" ||
                                            Request::route()->getName() == "app_info_article_category" ||
                                            Request::route()->getName() == "app_update_article_category" ||

                                            Request::route()->getName() == "app_subcategory_article" ||
                                            Request::route()->getName() == "app_add_new_subcategory_article" ||
                                            Request::route()->getName() == "app_info_article_subcategory" ||
                                            Request::route()->getName() == "app_update_article_subcategory" ||

                                            Request::route()->getName() == "app_article" ||
                                            Request::route()->getName() == "app_add_new_article" ||
                                            Request::route()->getName() == "app_info_article" ||
                                            Request::route()->getName() == "app_update_article")
                                                active
                                        @endif has-sub">

                        <a href="#" class='sidebar-link'>
                            <i class="fa-solid fa-box-archive"></i>
                            <span>{{ __('dashboard.stock') }}</span>
                        </a>

                        <ul class="submenu @if(Request::route()->getName() == "app_category_article" ||
                                                Request::route()->getName() == "app_add_new_category_article" ||
                                                Request::route()->getName() == "app_info_article_category" ||
                                                Request::route()->getName() == "app_update_article_category" ||

                                                Request::route()->getName() == "app_subcategory_article" ||
                                                Request::route()->getName() == "app_add_new_subcategory_article" ||
                                                Request::route()->getName() == "app_info_article_subcategory" ||
                                                Request::route()->getName() == "app_update_article_subcategory" ||

                                                Request::route()->getName() == "app_article" ||
                                                Request::route()->getName() == "app_add_new_article" ||
                                                Request::route()->getName() == "app_info_article" ||
                                                Request::route()->getName() == "app_update_article")
                                                    active
                            @endif">

                            <li class="submenu-item @if(Request::route()->getName() == "app_article") active @endif">
                                <a href="{{ route('app_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.articles') }}
                                </a>
                            </li>
                            <li class="submenu-item @if(Request::route()->getName() == "app_subcategory_article") active @endif">
                                <a href="{{ route('app_subcategory_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('article.article_subcategory') }}
                                </a>
                            </li>
                            <li class="submenu-item @if(Request::route()->getName() == "app_category_article") active @endif">
                                <a href="{{ route('app_category_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('article.article_category') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- End Stock --}}

                {{-- Start Service --}}
                @if ($service_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-item @if(Request::route()->getName() == "app_category_service" ||
                                            Request::route()->getName() == "app_add_new_category_service" ||
                                            Request::route()->getName() == "app_info_service_category" ||
                                            Request::route()->getName() == "app_update_service_category" ||

                                            Request::route()->getName() == "app_service" ||
                                            Request::route()->getName() == "app_add_new_service" ||
                                            Request::route()->getName() == "app_info_service" ||
                                            Request::route()->getName() == "app_update_service")
                                                active
                                        @endif has-sub">

                        <a href="#" class='sidebar-link'>
                            <i class="fa-solid fa-toolbox"></i>
                            <span>{{ __('dashboard.services') }}</span>
                        </a>

                        <ul class="submenu @if(Request::route()->getName() == "app_category_service" ||
                                                Request::route()->getName() == "app_add_new_category_service" ||
                                                Request::route()->getName() == "app_info_service_category" ||
                                                Request::route()->getName() == "app_update_service_category" ||

                                                Request::route()->getName() == "app_service" ||
                                                Request::route()->getName() == "app_add_new_service" ||
                                                Request::route()->getName() == "app_info_service" ||
                                                Request::route()->getName() == "app_update_service")
                                                    active
                                            @endif">
                            <li class="submenu-item @if(Request::route()->getName() == "app_service") active @endif">
                                <a href="{{ route('app_service', ['group' => 'service', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.price_list') }}
                                </a>
                            </li>
                            <li class="submenu-item @if(Request::route()->getName() == "app_category_service") active @endif">
                                <a href="{{ route('app_category_service', ['group' => 'service', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.service_category') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- End Service --}}

                {{-- Start Currency --}}
                    @if ($currency_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                        <li class="sidebar-item @if (Request::route()->getName() == "app_currency" ||
                                                        Request::route()->getName() == "app_create_currency" ||
                                                        Request::route()->getName() == "app_info_currency" ||
                                                        Request::route()->getName() == "app_update_currency") active @endif">
                            <a href="{{ route('app_currency', ['group' => 'currency', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                                <i class="fa-solid fa-money-bill-trend-up"></i>
                                <span>{{ __('dashboard.currencies') }}</span>
                            </a>
                        </li>
                    @endif
                {{-- End Currency --}}

                {{-- Start Payment method --}}
                    @if ($payment_method_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                        <li class="sidebar-item @if (Request::route()->getName() == "app_payment_methods" ||
                                                        Request::route()->getName() == "app_add_new_payment_methods" ||
                                                        Request::route()->getName() == "app_info_payment_methods" ||
                                                        Request::route()->getName() == "app_update_payment_methods") active @endif">
                            <a href="{{ route('app_payment_methods', ['group' => 'payment_method', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                                <i class="fa-solid fa-coins"></i>
                                <span>{{ __('dashboard.payment_methods') }}</span>
                            </a>
                        </li>
                    @endif
                {{-- End Payment method --}}

                @if ($sale_assign || $expense_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-title">{{ __('dashboard.billing') }}</li>
                @endif

                {{-- Start Sales --}}
                @if ($sale_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-item  @if(Request::route()->getName() == "app_sales_invoice" ||
                                                    Request::route()->getName() == "app_add_new_sales_invoice" ||
                                                    Request::route()->getName() == "app_info_sales_invoice" ||
                                                    Request::route()->getName() == "app_update_sales_invoice" ||

                                                    Request::route()->getName() == "app_proforma" ||
                                                    Request::route()->getName() == "app_add_new_proforma" ||
                                                    Request::route()->getName() == "app_info_proforma" ||
                                                    Request::route()->getName() == "app_update_proforma" ||

                                                    Request::route()->getName() == "app_delivery_note" ||
                                                    Request::route()->getName() == "app_add_new_delivery_note" ||
                                                    Request::route()->getName() == "app_info_delivery_note" ||
                                                    Request::route()->getName() == "app_entrances" ||
                                                    Request::route()->getName() == "app_add_new_entrance" ||
                                                    Request::route()->getName() == "app_signature" ||
                                                    Request::route()->getName() == "app_seal")
                                                        active
                                                @endif  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span>{{ __('dashboard.sale') }}</span>
                        </a>
                        <ul class="submenu @if(Request::route()->getName() == "app_sales_invoice" ||
                                                Request::route()->getName() == "app_add_new_sales_invoice" ||
                                                Request::route()->getName() == "app_info_sales_invoice" ||
                                                Request::route()->getName() == "app_update_sales_invoice" ||

                                                Request::route()->getName() == "app_proforma" ||
                                                Request::route()->getName() == "app_add_new_proforma" ||
                                                Request::route()->getName() == "app_info_proforma" ||
                                                Request::route()->getName() == "app_update_proforma" ||

                                                Request::route()->getName() == "app_delivery_note" ||
                                                Request::route()->getName() == "app_add_new_delivery_note" ||
                                                Request::route()->getName() == "app_info_delivery_note" ||
                                                Request::route()->getName() == "app_entrances" ||
                                                Request::route()->getName() == "app_add_new_entrance" ||
                                                Request::route()->getName() == "app_signature" ||
                                                Request::route()->getName() == "app_seal")
                                                    active
                                            @endif">
                            <li class="submenu-item @if(Request::route()->getName() == "app_sales_invoice") active @endif">
                                <a href="{{ route('app_sales_invoice', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.sales_invoice') }}
                                </a>
                            </li>
                            <li class="submenu-item @if(Request::route()->getName() == "app_proforma") active @endif">
                                <a href="{{ route('app_proforma', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.proforma_invoice') }}
                                </a>
                            </li>
                            {{--
                            <li class="submenu-item ">
                                <a href="#">{{ __('dashboard.credit_invoice_sale') }}</a>
                            </li>
                            --}}
                            {{--
                            <li class="submenu-item ">
                                <a href="#">{{ __('dashboard.offers_quotes') }}</a>
                            </li>
                            --}}
                            <li class="submenu-item @if(Request::route()->getName() == "app_delivery_note") active @endif">
                                <a href="{{ route('app_delivery_note', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.delivery_note') }}
                                </a>
                            </li>

                            <li class="submenu-item @if(Request::route()->getName() == "app_entrances") active @endif">
                                <a href="{{ route('app_entrances', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('invoice.entrance') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- End Sales --}}

                {{-- Start Expense --}}
                @if ($expense_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-item  @if(Request::route()->getName() == "app_purchases" ||
                                                    Request::route()->getName() == "app_add_new_purchase" ||
                                                    Request::route()->getName() == "app_update_purchase" ||

                                                    Request::route()->getName() == "app_expenses" ||
                                                    Request::route()->getName() == "app_add_new_expense")
                                                        active
                                                @endif has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa-solid fa-file-invoice"></i>
                            <span>{{ __('dashboard.expenses') }}</span>
                        </a>
                        <ul class="submenu @if(Request::route()->getName() == "app_purchases" ||
                                                Request::route()->getName() == "app_add_new_purchase" ||
                                                Request::route()->getName() == "app_update_purchase" ||

                                                Request::route()->getName() == "app_expenses" ||
                                                Request::route()->getName() == "app_add_new_expense")
                                                    active
                                            @endif">
                            <li class="submenu-item @if(Request::route()->getName() == "app_purchases") active @endif">
                                <a href="{{ route('app_purchases', ['group' => 'expense', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                    {{ __('dashboard.purchases') }}
                                </a>
                            </li>
                            {{--
                            <li class="submenu-item ">
                                <a href="#">{{ __('dashboard.credit_invoice_purchases') }}</a>
                            </li>
                            --}}
                            <li class="submenu-item">
                                <a href="#">{{ __('dashboard.order') }}</a>
                            </li>
                            <li class="submenu-item @if(Request::route()->getName() == "app_expenses") active @endif">
                                <a href="{{ route('app_expenses', ['group' => 'expense', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.expenses') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- End Expense --}}

                @if ($debt_assign || $receivable_assign || $report_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                    <li class="sidebar-title">{{ __('dashboard.others') }}</li>
                @endif

                {{-- Start Debt --}}
                    @if ($debt_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="fa-solid fa-circle-up"></i>
                                <span>{{ __('dashboard.debts') }}</span>
                            </a>
                        </li>
                    @endif
                {{-- End Debt --}}

                {{-- Start Receivable --}}
                    @if ($receivable_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="fa-solid fa-circle-down"></i>
                                <span>{{ __('dashboard.receivables') }}</span>
                            </a>
                        </li>
                    @endif
                {{-- End Receivable --}}

                {{-- Start Report --}}
                    @if ($report_assign || Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'superadmin')
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>{{ __('dashboard.report') }}</span>
                            </a>
                        </li>
                    @endif
                {{-- End Report --}}

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
