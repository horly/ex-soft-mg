@extends('base')
@section('title', __('auth.user_authentication'))
@section('content')

<div class="vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <img class="rounded mx-auto d-block" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="200">
                <p class="text-center text-muted">ERP</p>
                <p class="text-muted text-center h5 mb-5"> {{ __('auth.device_vrification') }}</p>

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
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-calculator"></i></span>
                            <input type="number" name="verification-code" id="verification-code" class="form-control @if(Session::has('verification-code-error')) is-invalid @endif" placeholder="XXXXXX"  autofocus>
                        </div>

                        <div class="text-end mb-3">
                            <a href="{{ route('app_resend_device_auth_code', ['secret' => $secret]) }}" role="button" class="link-underline-light">{{ __('auth.resend_code')}}</a>
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

@endsection