<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="#">
                        <img src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="Logo" srcset="">
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

                <li class="sidebar-item @if (Request::route()->getName() == "app_dashboard") active @endif">
                    <a href="{{ route('app_dashboard', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('dashboard.dashboard') }}</span>
                    </a>
                </li>

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
                        <li class="submenu-item @if(Request::route()->getName() == "app_customer") active @endif">
                            <a href="{{ route('app_customer', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.customer') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_supplier") active @endif">
                            <a href="{{ route('app_supplier', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.supplier') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_creditor") active @endif">
                            <a href="{{ route('app_creditor', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.creditors') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_debtor") active @endif">
                            <a href="{{ route('app_debtor', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.debtors') }}
                            </a>
                        </li>
                        {{--
                        <li class="submenu-item ">
                            <a href="#">{{ __('dashboard.vat_administration') }}</a>
                        </li>
                        --}}
                    </ul>
                </li>

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
                                        @endif 
                    has-sub">
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
                            <a href="{{ route('app_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.articles') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_subcategory_article") active @endif">
                            <a href="{{ route('app_subcategory_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('article.article_subcategory') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_category_article") active @endif">
                            <a href="{{ route('app_category_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('article.article_category') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item @if(Request::route()->getName() == "app_category_service" ||
                                            Request::route()->getName() == "app_add_new_category_service" ||
                                            Request::route()->getName() == "app_info_service_category" ||
                                            Request::route()->getName() == "app_update_service_category" ||

                                            Request::route()->getName() == "app_service" ||
                                            Request::route()->getName() == "app_add_new_service" ||
                                            Request::route()->getName() == "app_info_service" ||
                                            Request::route()->getName() == "app_update_service") 
                                                active 
                                        @endif  
                    has-sub">
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
                            <a href="{{ route('app_service', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.price_list') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_category_service") active @endif">
                            <a href="{{ route('app_category_service', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.service_category') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item @if (Request::route()->getName() == "app_currency" ||
                                                Request::route()->getName() == "app_create_currency" ||
                                                Request::route()->getName() == "app_info_currency" ||
                                                Request::route()->getName() == "app_update_currency") active @endif">
                    <a href="{{ route('app_currency', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                        <span>{{ __('dashboard.currencies') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (Request::route()->getName() == "app_payment_methods" ||
                                                Request::route()->getName() == "app_add_new_payment_methods" ||
                                                Request::route()->getName() == "app_info_payment_methods" ||
                                                Request::route()->getName() == "app_update_payment_methods") active @endif">
                    <a href="{{ route('app_payment_methods', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" class='sidebar-link'>
                        <i class="fa-solid fa-coins"></i>
                        <span>{{ __('dashboard.payment_methods') }}</span>
                    </a>
                </li>

                <li class="sidebar-title">{{ __('dashboard.billing') }}</li>

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
                                                Request::route()->getName() == "app_info_delivery_note")
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
                                            Request::route()->getName() == "app_info_delivery_note")
                                                active 
                                        @endif">
                        <li class="submenu-item @if(Request::route()->getName() == "app_sales_invoice") active @endif">
                            <a href="{{ route('app_sales_invoice', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.sales_invoice') }}
                            </a>
                        </li>
                        <li class="submenu-item @if(Request::route()->getName() == "app_proforma") active @endif">
                            <a href="{{ route('app_proforma', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
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
                            <a href="{{ route('app_delivery_note', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.delivery_note') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  @if(Request::route()->getName() == "app_purchases" || 
                                                Request::route()->getName() == "app_add_new_purchase")
                                                    active 
                                            @endif has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-file-invoice"></i>
                        <span>{{ __('dashboard.expenses') }}</span>
                    </a>
                    <ul class="submenu @if(Request::route()->getName() == "app_purchases" || 
                                             Request::route()->getName() == "app_add_new_purchase") 
                                                active 
                                        @endif">
                        <li class="submenu-item @if(Request::route()->getName() == "app_purchases") active @endif">
                            <a href="{{ route('app_purchases', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                                {{ __('dashboard.purchases') }}
                            </a>
                        </li>
                        {{--
                        <li class="submenu-item ">
                            <a href="#">{{ __('dashboard.credit_invoice_purchases') }}</a>
                        </li>
                        --}}
                        <li class="submenu-item ">
                            <a href="#">{{ __('dashboard.order') }}</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="#">{{ __('dashboard.expenses') }}</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-title">{{ __('dashboard.others') }}</li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-circle-up"></i>
                        <span>{{ __('dashboard.debts') }}</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-circle-down"></i>
                        <span>{{ __('dashboard.receivables') }}</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>{{ __('dashboard.report') }}</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>