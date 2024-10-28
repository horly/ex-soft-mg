@extends('base')
@section('title', __('payment_methods.update_a_payment_method'))
@section('content')

<div id="app">
    @include('menu.navigation-menu')

    @include('menu.login-nav')

    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">

            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>{{ __('payment_methods.update_a_payment_method') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_payment_methods', ['group' => 'payment_method', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.payment_methods') }}</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('app_info_payment_methods', ['group' => 'payment_method', 'id' => $entreprise->id, 'id2' => $functionalUnit->id, 'id3' => $paymentMethod->id]) }}">{{ __('payment_methods.payment_method_details') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('payment_methods.update_a_payment_method') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- On inlut les messages flash--}}
            @include('message.flash-message')

            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('app_create_payment_methods') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_payment_methods" value="{{ $paymentMethod->id }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="edit">

                            <div class="mb-4 row">
                                <label for="designation_pay_meth" class="col-sm-4 col-form-label">{{ __('dashboard.designation') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('designation_pay_meth') is-invalid @enderror" id="designation_pay_meth" name="designation_pay_meth" placeholder="{{ __('payment_methods.enter_the_payment_method_designation') }}" value="{{ $paymentMethod->designation }}">
                                    <small class="text-danger">@error('designation_pay_meth') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="border-bottom mb-4 fw-bold">
                                {{ __('payment_methods.bank_information') }} ({{ __('payment_methods.if_bank') }})
                            </div>

                            <div class="mb-4 row">
                                <label for="instu_name_pay_meth" class="col-sm-4 col-form-label">{{ __('payment_methods.institution_name') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('instu_name_pay_meth') is-invalid @enderror" id="instu_name_pay_meth" name="instu_name_pay_meth" placeholder="{{ __('payment_methods.enter_the_institution_name') }}" value="{{ $paymentMethod->institution_name }}">
                                    <small class="text-danger">@error('instu_name_pay_meth') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="iban_pay_meth" class="col-sm-4 col-form-label">IBAN</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('iban_pay_meth') is-invalid @enderror" id="iban_pay_meth" name="iban_pay_meth" placeholder="{{ __('payment_methods.enter_the_iban') }}" value="{{ $paymentMethod->iban }}">
                                    <small class="text-danger">@error('iban_pay_meth') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="account_number_pay_meth" class="col-sm-4 col-form-label">{{ __('entreprise.account_number') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('account_number_pay_meth') is-invalid @enderror" id="account_number_pay_meth" name="account_number_pay_meth" placeholder="{{ __('client.enter_the_account_number') }}" value="{{ $paymentMethod->account_number }}">
                                    <small class="text-danger">@error('account_number_pay_meth') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="devise_pay_meth" class="col-sm-4 col-form-label">{{ __('dashboard.currency') }}</label>
                                <div class="col-sm-5">

                                    @if ($encaissement_exit)
                                        <select class="form-select" name="" id="" name="" disabled>
                                            @if (Config::get('app.locale') == 'en')
                                                <option value="{{ $devisePaymethod->id }}" selected>{{ $devisePaymethod->iso_code }} - {{ $devisePaymethod->motto_en }}</option>
                                            @else
                                                <option value="{{ $devisePaymethod->id }}" selected>{{ $devisePaymethod->iso_code }} - {{ $devisePaymethod->motto }}</option>
                                            @endif
                                        </select>
                                    @else
                                        <select class="form-select" name="devise_pay_meth" id="devise_pay_meth" name="devise_pay_meth">
                                            @if (Config::get('app.locale') == 'en')
                                                <option value="{{ $devisePaymethod->id }}" selected>{{ $devisePaymethod->iso_code }} - {{ $devisePaymethod->motto_en }}</option>
                                                @foreach ($deviseGests as $devise)
                                                    <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ $devisePaymethod->id }}" selected>{{ $devisePaymethod->iso_code }} - {{ $devisePaymethod->motto }}</option>
                                                @foreach ($deviseGests as $devise)
                                                    <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @endif
                                    <small class="text-danger">@error('devise_pay_meth') {{ $message }} @enderror</small>

                                </div>
                                @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                    <div class="col-sm-3 d-grid gap-2">
                                        <a href="{{ route('app_create_currency', ['group' => 'currency', 'id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary mb-3" role="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            &nbsp;{{ __('auth.add') }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4 row">
                                <label for="bic_swift_pay_meth" class="col-sm-4 col-form-label">BIC/Swift code</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('bic_swift_pay_meth') is-invalid @enderror" id="bic_swift_pay_meth" name="bic_swift_pay_meth" placeholder="{{ __('payment_methods.enter_the_bic_swift_code') }}" value="{{ $paymentMethod->bic_swiff }}">
                                    <small class="text-danger">@error('bic_swift_pay_meth') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
                            @endif

                        </form>
                    </div>
                </div>
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>

        </div>
    </div>
</div>

@endsection
