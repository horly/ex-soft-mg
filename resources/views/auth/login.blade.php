@extends('base')
@section('title', __('auth.login'))
@section('content')

@if (config('app.name') == "EXADERP")
    <div class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    @include('global.logo')

                    <p class="text-center text-muted">ERP</p>
                    <p class="text-muted text-center h5 mb-5"> {{ __('auth.login') }}</p>

                    <div class="d-flex justify-content-end mb-3">
                        @include('button.language-dropdown')
                    </div>

                    <form class="card" action="{{ route('login') }}" method="post">
                        @csrf

                        <div class="card-body bg-body-tertiary p-5">

                            @include('message.flash-message')

                            <label for="email" class="form-label">{{ __('auth.email')}}</label>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('auth.enter_your_email') }}" value="{{ old('email') }}">
                                </div>
                                <small class="text-danger">@error('email'){{ $message }}@enderror</small>
                            </div>


                            <label for="password" class="form-label">{{ __('auth.password')}}</label>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('auth.enter_your_password') }}">
                                </div>
                                <small class="text-danger">@error('password'){{ $message }}@enderror</small>
                            </div>



                            <div class="row mb-4">

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-lebel" for="remember">{{ __('auth.remember_me')}}</label>
                                    </div>
                                </div>

                                <div class="col-md-6 text-end">
                                    <a href="{{ route('app_email_reset_password_request') }}" class="link-underline-light">{{ __('auth.forgot_password')}}</a>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary save" type="submit">
                                    {{ __('auth.sign_in')}}
                                </button>
                                <button class="btn btn-primary btn-loading d-none" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    {{ __('auth.loading') }}
                                </button>
                            </div>
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
                    <h1 class="auth-title presta-text-color">{{ __('auth.login') }}</h1>
                    <p class="auth-subtitle mb-5">
                        <div class="d-flex justify-content-end mb-3">
                            @include('button.language-dropdown')
                        </div>
                    </p>

                    @include('message.flash-message')

                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        <div for="email" class="form-group position-relative has-icon-left mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="{{ __('auth.email')}}" value="{{ old('email') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <small class="text-danger">@error('email'){{ $message }}@enderror</small>
                            </div>
                        </div>


                        <div for="password" class="form-group position-relative has-icon-left mb-4">
                            <input type="password" id="password" name="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="{{ __('auth.password')}}">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <small class="text-danger">@error('password'){{ $message }}@enderror</small>
                        </div>

                        <div class="form-check form-check-lg d-flex align-items-end mb-4">
                            <input class="form-check-input me-2" type="checkbox" id="remember" name="remember" value="" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label text-gray-600" for="flexCheckDefault">
                                {{ __('auth.remember_me')}}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary save" type="submit">
                                {{ __('auth.sign_in')}}
                            </button>
                            <button class="btn btn-primary btn-loading d-none" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ __('auth.loading') }}
                            </button>
                        </div>

                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        {{--
                        <p class="text-gray-600">Don't have an account? <a href="auth-register.html"
                                class="font-bold">Sign
                                up</a>.</p> --}}
                        <p>
                            <a class="font-bold" href="{{ route('app_email_reset_password_request') }}">
                                {{ __('auth.forgot_password')}}
                            </a>.
                        </p>
                    </div>

                    <div class="p-3 text-center">
                        <a href="{{ route('app_contact_us') }}">{{ __('main.contact_us') }}</a>
                    </div>

                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right" class="presta-bg-color">
                    <div class="d-flex flex-column justify-content-center align-items-center vh-100">
                        <img src="{{ asset('assets/img/logo/Prestavice-white1.png') }}" width="200" alt="">
                        <img src="{{ asset('assets/img/other/undraw_secure_login_pdn4.svg') }}" width="600" class="img-fluid" alt="...">
                    </div>
                </div>
            </div>
        </div>


    </div>
@endif


@endsection
