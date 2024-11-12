@extends('base')
@section('title', __('auth.forgot_password'))
@section('content')

@if (config('app.name') == "EXADERP")

<div class="vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                @include('global.logo')

                <p class="text-center text-muted">ERP</p>
                <p class="text-muted text-center h5 mb-5"> {{ __('auth.forgot_password') }}</p>

                <div class="d-flex justify-content-end mb-3">
                    @include('button.language-dropdown')
                </div>

                <form class="card" method="post" action="{{ route('app_email_reset_password_post') }}">
                    @csrf

                    <div class="card-body bg-body-tertiary p-5">
                        <div class="alert alert-primary text-center" role="alert">
                            <div><i class="fa-solid fa-circle-info"></i></div>
                            {{ __('auth.provide_your_email_and_we_will') }}</b>
                        </div>

                         {{-- Message de session--}}
                         @include('message.flash-message')

                        <label for="emailPassReq" class="form-label">{{ __('auth.email')}}</label>
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="emailPassReq" id="emailPassReq" class="form-control @error('emailPassReq') is-invalid @enderror" placeholder="{{ __('auth.enter_your_email') }}" value="{{ old('emailPassReq') }}">
                            </div>
                            <small class="text-danger">@error('emailPassReq'){{ $message }}@enderror</small>
                        </div>

                        @include('button.send-button')

                    </div>

                </form>

                <div class="m-5">
                    @include('menu.footer-global')
                </div>
            </div>
        </div>
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
                <h2 class="presta-text-color">{{ __('auth.forgot_password') }}</h2>
                    <p class="auth-subtitle mb-5">
                        <div class="d-flex justify-content-end mb-3">
                            @include('button.language-dropdown')
                        </div>
                    </p>



                    <form method="post" action="{{ route('app_email_reset_password_post') }}">
                        @csrf

                            <div class="alert alert-primary text-center" role="alert">
                                <div><i class="fa-solid fa-circle-info"></i></div>
                                {{ __('auth.provide_your_email_and_we_will') }}</b>
                            </div>

                            {{-- Message de session--}}
                            @include('message.flash-message')


                            <label for="emailPassReq" class="form-label">{{ __('auth.email')}}</label>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="emailPassReq" id="emailPassReq" class="form-control @error('emailPassReq') is-invalid @enderror" placeholder="{{ __('auth.enter_your_email') }}" value="{{ old('emailPassReq') }}">
                                </div>
                                <small class="text-danger">@error('emailPassReq'){{ $message }}@enderror</small>
                            </div>

                            @include('button.send-button')

                    </form>

            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right" class="presta-bg-color">
                <div class="d-flex flex-column justify-content-center align-items-center vh-100">
                    <img src="{{ asset('assets/img/logo/Prestavice-white1.png') }}" width="200" class="mb-4" alt="">
                    <img src="{{ asset('assets/img/other/undraw_secure_login_pdn4.svg') }}" height="100" class="img-fluid" alt="...">
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection
