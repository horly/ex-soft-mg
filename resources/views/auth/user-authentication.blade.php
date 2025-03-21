@extends('base')
@section('title', __('auth.user_authentication'))
@section('content')

@if (config('app.name') == "EXADERP")
    <div class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    @include('global.logo')

                    <p class="text-center text-muted">ERP</p>
                    <p class="text-muted text-center h5 mb-5"> {{ __('auth.device_vrification') }}</p>

                    <div class="d-flex justify-content-end mb-3">
                        @include('button.language-dropdown')
                    </div>

                    <form class="card" method="post" action="{{ route('app_confirm_auth') }}">
                        @csrf

                        <div class="card-body bg-body-tertiary p-5">
                            <div class="text-center mb-4">
                                <i class="fa-regular fa-envelope fa-3x mb-3"></i>
                                <h4>Email</h4>
                            </div>

                            <div class="alert alert-primary text-center" role="alert">
                                <div><i class="fa-solid fa-circle-info"></i></div>
                                {{ __('auth.code_email_utication_message') }} <b>{{ $email }}</b>.
                            </div>

                            {{-- Message de session--}}
                            @include('message.flash-message')

                            <input type="hidden" name="secret" id="secret" value="{{ $secret }}">

                            <label for="verification-code" class="form-label">{{ __('auth.device_verification_code')}}</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
                                <input type="number" name="verification-code" id="verification-code" class="form-control @if(Session::has('verification-code-error')) is-invalid @endif" placeholder="XXXXXX"  autofocus>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <a href="{{ route('app_change_email_address_request', ['token' => $secret ]) }}" id="change-email-request-save" class="link-underline-light">{{ __('auth.change_email_address') }}</a>
                                    <a href="#" class="link-underline-light d-none" id="change-email-request-loading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ __('auth.loading') }}
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('app_resend_device_auth_code', ['secret' => $secret]) }}" role="button" class="link-underline-light save">{{ __('auth.resend_code')}}</a>
                                    <a href="#" class="link-underline-light btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ __('auth.loading') }}
                                    </a>
                                </div>
                            </div>



                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">{{ __('auth.verify')}}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="mb-5">
            @include('menu.footer-global')
        </div>
    </div>
@else

<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-login-presta">
                    <a href="/"><img src="{{ asset('assets/img/logo/Prestavice-logo-erp1.png') }}" width="200" alt="Logo"></a>
                </div>
                <h2 class="presta-text-color">{{ __('auth.device_vrification') }}</h2>
                    <p class="auth-subtitle mb-5">
                        <div class="d-flex justify-content-end mb-3">
                            @include('button.language-dropdown')
                        </div>
                    </p>



                    <form method="post" action="{{ route('app_confirm_auth') }}">
                        @csrf

                            <div class="alert alert-primary text-center" role="alert">
                                <div><i class="fa-solid fa-circle-info"></i></div>
                                {{ __('auth.code_email_utication_message') }} <b>{{ $email }}</b>.
                            </div>

                            {{-- Message de session--}}
                            @include('message.flash-message')

                            <input type="hidden" name="secret" id="secret" value="{{ $secret }}">

                            <label for="verification-code" class="form-label">{{ __('auth.device_verification_code')}}</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
                                <input type="number" name="verification-code" id="verification-code" class="form-control @if(Session::has('verification-code-error')) is-invalid @endif" placeholder="XXXXXX"  autofocus>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <a href="{{ route('app_change_email_address_request', ['token' => $secret ]) }}" id="change-email-request-save" class="link-underline-light">{{ __('auth.change_email_address') }}</a>
                                    <a href="#" class="link-underline-light d-none" id="change-email-request-loading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ __('auth.loading') }}
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('app_resend_device_auth_code', ['secret' => $secret]) }}" class="link-underline-light save">{{ __('auth.resend_code')}}</a>
                                    <a href="#" class="link-underline-light btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ __('auth.loading') }}
                                    </a>
                                </div>
                            </div>



                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">{{ __('auth.verify')}}</button>
                            </div>
                    </form>

            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right" class="presta-bg-color">
                <div class="d-flex flex-column justify-content-center align-items-center vh-100">
                    <img src="{{ asset('assets/img/logo/Prestavice-white1.png') }}" width="200" class="mb-4" alt="">
                    <img src="{{ asset('assets/img/other/undraw_confirmed_re_sef7.svg') }}" height="100" class="img-fluid" alt="...">
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
