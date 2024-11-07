@extends('base')
@section('title', __('auth.reset_password'))
@section('content')


@if (config('app.name') == "EXADERP")

<div class="container-margin-top">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                @include('global.logo')

                <p class="text-center text-muted">ERP</p>
                <p class="text-muted text-center h5 mb-5"> {{ __('auth.reset_password') }}</p>

                <div class="d-flex justify-content-end mb-3">
                    @include('button.language-dropdown')
                </div>

                <form class="card" action="{{ route('app_change_password_post') }}" method="POST">
                    @csrf

                    <div class="card-body bg-body-tertiary p-5">
                        @include('message.flash-message')

                        <input type="hidden" name="token" value="{{ $secret }}">

                        <div class="mb-4">
                            <label for="new_password" class="form-label">{{ __('auth.new_password')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="new_password" id="passwordUsr" class="form-control @error('new_password') is-invalid @enderror" placeholder="{{ __('auth.create_your_password') }}" value="{{ old('new_password') }}">
                                <span class="input-group-text cursor-pointer" id="show-password"><i class="fa-solid fa-eye"></i></span>
                                <span class="input-group-text cursor-pointer d-none" id="hide-password"><i class="fa-solid fa-eye-slash"></i></span>
                            </div>
                            <small class="text-danger">@error('new_password'){{ $message }}@enderror</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">{{ __('auth.password_confirmation')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="confirm_password" id="passwordConfirm" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="{{ __('auth.confirm_your_password') }}" value="{{ old('confirm_password') }}">
                                <span class="input-group-text cursor-pointer" id="show-password-confirm"><i class="fa-solid fa-eye"></i></span>
                                <span class="input-group-text cursor-pointer d-none" id="hide-password-confirm"><i class="fa-solid fa-eye-slash"></i></span>
                            </div>
                            <small class="text-danger">@error('confirm_password'){{ $message }}@enderror</small>
                        </div>

                        @include('button.save-button')

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
                <h2 class="presta-text-color">{{ __('auth.reset_password') }}</h2>
                    <p class="auth-subtitle mb-5">
                        <div class="d-flex justify-content-end mb-3">
                            @include('button.language-dropdown')
                        </div>
                    </p>



                    <form class="card" method="post" action="{{ route('app_change_password_post') }}">
                        @csrf

                        @include('message.flash-message')

                        <input type="hidden" name="token" value="{{ $secret }}">

                        <div class="mb-4">
                            <label for="new_password" class="form-label">{{ __('auth.new_password')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="new_password" id="passwordUsr" class="form-control @error('new_password') is-invalid @enderror" placeholder="{{ __('auth.create_your_password') }}" value="{{ old('new_password') }}">
                                <span class="input-group-text cursor-pointer" id="show-password"><i class="fa-solid fa-eye"></i></span>
                                <span class="input-group-text cursor-pointer d-none" id="hide-password"><i class="fa-solid fa-eye-slash"></i></span>
                            </div>
                            <small class="text-danger">@error('new_password'){{ $message }}@enderror</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">{{ __('auth.password_confirmation')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="confirm_password" id="passwordConfirm" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="{{ __('auth.confirm_your_password') }}" value="{{ old('confirm_password') }}">
                                <span class="input-group-text cursor-pointer" id="show-password-confirm"><i class="fa-solid fa-eye"></i></span>
                                <span class="input-group-text cursor-pointer d-none" id="hide-password-confirm"><i class="fa-solid fa-eye-slash"></i></span>
                            </div>
                            <small class="text-danger">@error('confirm_password'){{ $message }}@enderror</small>
                        </div>

                        @include('button.save-button')

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
