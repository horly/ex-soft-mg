@extends('base')
@section('title', __('main.permissions'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_assign_functional_unit_to_user', ['id' => $entreprise->id, 'idUser' => $user->id ]) }}">{{ __('main.functional_unit_management') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page"> {{ __('main.permissions') }}</li>
        </ol>
    </nav>

     {{-- On inlut les messages flash--}}
     @include('message.flash-message')

     <div class="card">
        <div class="card-body">
            <form action="{{ route('app_save_permissions') }}" method="POST">
                @csrf

                <div class="p-4 mb-4 border rounded">

                    <div class="border-bottom mb-4 fw-bold">
                        {{ __('entreprise.company_information') }}
                    </div>

                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                    <div class="mb-4 row">
                        <div class="col-sm-6"><i class="fa-solid fa-building permissions-icons"></i>
                            {{ __('main.company') }}</div>
                        <div class="col-md-6 text-primary fw-bold">{{ $entreprise->name }}</div>
                    </div>

                    <div class="mb-4 row">
                        <div class="col-sm-6"><i class="fa-solid fa-building-circle-arrow-right permissions-icons"></i>
                            {{ __('entreprise.functional_unit') }}</div>
                        <div class="col-md-6 text-primary fw-bold">{{ $fu->name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <i class="fa-solid fa-user permissions-icons"></i>
                            {{ __('main.user') }}</div>
                        <div class="col-md-6 text-primary fw-bold">{{ $user->name }}</div>
                    </div>
                </div>

                <div class="p-4 mb-4 border rounded">

                    <div class="border-bottom mb-4 fw-bold">
                        {{ __('main.global_permissions') }}
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="full_dashboard_view" name="full_dashboard_view" @if($global_permissions['full_dashboard_view_assgn'] === 1) checked @endif>
                        <label class="form-check-label" for="full_dashboard_view">{{ __('main.full_dashboard_view') }}</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="edit_delete_contents" name="edit_delete_contents" @if($global_permissions['edit_delete_contents_assgn'] === 1) checked @endif>
                        <label class="form-check-label" for="edit_delete_contents">{{ __('main.edit_delete_contents') }}</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="billing" name="billing" @if($global_permissions['billing_assgn'] === 1) checked @endif>
                        <label class="form-check-label" for="billing">{{ __('main.billing') }}</label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="report_generation" name="report_generation" @if($global_permissions['report_generation_assgn'] === 1) checked @endif>
                        <label class="form-check-label" for="report_generation">{{ __('main.report_generation') }}</label>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            @include('button.save-button')
                        </div>
                    </div>
                </div>

            </form>

            <div class="p-4 mb-4 border rounded">
                <div class="border-bottom mb-4 fw-bold">
                    {{ __('main.menu_permissions') }}
                </div>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4">
                        <div class="list-group" role="tablist">

                            <a class="list-group-item list-group-item-action active"
                                id="list-home-list" data-bs-toggle="list" href="#contact"
                                role="tab">
                                <i class="fa-solid fa-circle-user permissions-icons"></i>
                                {{ __('dashboard.my_contacts') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-profile-list" data-bs-toggle="list"
                                href="#stock" role="tab">
                                <i class="fa-solid fa-box-archive permissions-icons"></i>
                                {{ __('dashboard.stock') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-messages-list" data-bs-toggle="list"
                                href="#service" role="tab">
                                <i class="fa-solid fa-briefcase permissions-icons"></i>
                                Services
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#currencies" role="tab">
                                <i class="fa-solid fa-money-bill-trend-up permissions-icons"></i>
                                {{ __('dashboard.currencies') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#payment_methods" role="tab">
                                <i class="fa-solid fa-coins permissions-icons"></i>
                                {{ __('dashboard.payment_methods') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#sales" role="tab">
                                <i class="fa-solid fa-file-invoice-dollar permissions-icons"></i>
                                {{ __('dashboard.sale') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#expenses" role="tab">
                                <i class="fa-solid fa-file-invoice permissions-icons"></i>
                                {{ __('dashboard.expenses') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#debts" role="tab">
                                <i class="fa-solid fa-circle-up permissions-icons"></i>
                                {{ __('dashboard.debts') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#receivables" role="tab">
                                <i class="fa-solid fa-circle-down permissions-icons"></i>
                                {{ __('dashboard.receivables') }}
                            </a>

                            <a class="list-group-item list-group-item-action"
                                id="list-settings-list" data-bs-toggle="list"
                                href="#report" role="tab">
                                <i class="fa-solid fa-clipboard-list"></i>
                                {{ __('dashboard.report') }}
                            </a>

                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 mt-1 p-4 border rounded">
                        <div class="tab-content text-justify" id="nav-tabContent">

                            <div class="tab-pane show active" id="contact" role="tabpanel"
                                aria-labelledby="list-home-list">
                                {{-- Contact tab-content --}}
                                <form action="{{ route('app_save_contact_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="customer_assign" name="customer_assign" @if($contact_permissions['app_customer_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="customer_assign">{{ __('client.customers') }}</label>
                                    </div>

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="suppliers_assign" name="suppliers_assign" @if($contact_permissions['app_supplier_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="suppliers_assign">{{ __('supplier.suppliers') }}</label>
                                    </div>


                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="creditors_assign" name="creditors_assign" @if($contact_permissions['app_creditor_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="creditors_assign">{{ __('dashboard.creditors') }}</label>
                                    </div>

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="debtors_assign" name="debtors_assign" @if($contact_permissions['app_debtor_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="debtors_assign">{{ __('dashboard.debtors') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>

                                </form>

                            </div>

                            <div class="tab-pane" id="stock" role="tabpanel"
                                aria-labelledby="list-profile-list">

                                <form action="{{ route('app_save_stock_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="stock_assign" name="stock_assign" @if($stock_permissions['app_stock_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="stock_assign">{{ __('dashboard.stock') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="service" role="tabpanel"
                                aria-labelledby="list-messages-list">

                                <form action="{{ route('app_save_service_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="service_assign" name="service_assign" @if($service_permissions['app_service_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="service_assign">{{ __('dashboard.services') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="currencies" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_currency_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="currency_assign" name="currency_assign" @if($currency_permissions['app_currency_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="currency_assign">{{ __('dashboard.currency') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="payment_methods" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_payment_method_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="payment_method_assign" name="payment_method_assign" @if($payment_method_permissions['app_payment_method_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="payment_method_assign">{{ __('dashboard.payment_methods') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="sales" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_sales_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="sale_assign" name="sale_assign" @if($sale_permissions['app_sale_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="sale_assign">{{ __('dashboard.sale') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="expenses" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_expense_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="expense_assign" name="expense_assign" @if($expense_permissions['app_expense_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="expense_assign">{{ __('dashboard.expenses') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="debts" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_debt_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="debt_assign" name="debt_assign" @if($debt_permissions['app_debt_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="debt_assign">{{ __('dashboard.debts') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="receivables" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_receivable_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="receivable_assign" name="receivable_assign" @if($receivable_permissions['app_receivable_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="receivable_assign">{{ __('dashboard.receivables') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="report" role="tabpanel"
                                aria-labelledby="list-settings-list">

                                <form action="{{ route('app_save_report_permissions') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id_fu" id="id_fu" value="{{ $fu->id }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" id="report_assign" name="report_assign" @if($report_permissions['app_report_assgn'] === 1) checked @endif>
                                        <label class="form-check-label" for="report_assign">{{ __('dashboard.report') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('button.save-button')
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

     <div class="m-5">
        @include('menu.footer-global')
    </div>

</div>

@endsection
