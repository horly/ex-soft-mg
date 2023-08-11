@extends('base')
@section('title', __('auth.login'))
@section('content')

<div class="vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <img class="rounded mx-auto d-block" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="200">
                <p class="text-center text-muted">ERP</p>
                <p class="text-muted text-center h5 mb-5"> {{ __('auth.login') }}</p>

                <div class="d-flex justify-content-end mb-3">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-language"></i> Lang
                            @if (Config::get('app.locale') == 'en')
                                <i class="flag-icon flag-icon-gb rounded"></i>
                            @else
                                <i class="flag-icon flag-icon-fr rounded"></i>
                            @endif
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'fr']) }}"><i class="flag-icon flag-icon-fr rounded"></i> Français</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'en']) }}"><i class="flag-icon flag-icon-gb rounded"></i> English</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form class="card" action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="card-body bg-body-tertiary p-5">

                        @error('email')
                            <div class="alert alert-danger text-center" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('password')
                            <div class="alert alert-danger text-center" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <label for="email" class="form-label">{{ __('auth.email')}}</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('auth.enter_your_email') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>

                        <label for="password" class="form-label">{{ __('auth.password')}}</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('auth.enter_your_password') }}" required autocomplete="current-password">
                        </div>
                        

                        <div class="row mb-4">

                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-lebel" for="remember">{{ __('auth.remember_me')}}</label>
                                </div>
                            </div>

                            <div class="col-md-6 text-end">
                                <a href="#" class="link-underline-light">{{ __('auth.forgot_password')}}</a>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">{{ __('auth.sign_in')}}</button>
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

@endsection
