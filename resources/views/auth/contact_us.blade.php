@extends('base')
@section('title', __('main.contact_us'))
@section('content')

<div id="auth">
    <div class="row h-100">

        <div class="col-lg-6 col-12">
            <div id="auth-left">
                <div class="auth-login-presta">
                    <a href="/"><img src="{{ asset('assets/img/logo/Prestavice-logo-erp1.png') }}" width="200" alt="Logo"></a>
                </div>

                <h2 class="presta-text-color">{{ __('main.get_in_touch') }}</h2>
                <p class="auth-subtitle mb-5">
                    <div class="d-flex justify-content-end mb-3">
                        @include('button.language-dropdown')
                    </div>
                </p>

                @include('message.alert')

                <form method="post" id="send-message-form" action="{{ route('app_send_message') }}">

                    <input type="hidden" id="send-message-token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <input type="text" id="first_name" name="first_name" class="form-control form-control-xl" placeholder="{{ __('home.first_name') }}">
                            <small class="text-danger" id="first_name-error" message="{{ __('home.please_enter_a_valid_first_name') }}"></small>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <input type="text" id="last_name" name="last_name" class="form-control form-control-xl" placeholder="{{ __('home.last_name') }}">
                            <small class="text-danger" id="last_name-error" message="{{ __('home.please_enter_a_valid_last_name') }}"></small>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <input type="number" id="phone_number" name="phone_number" class="form-control form-control-xl" placeholder="{{ __('home.phone_number') }}">
                            <small class="text-danger" id="phone_number-error" message="{{ __('home.please_enter_a_valid_phone_number') }}"></small>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <input type="email" id="email_addr" name="email_addr" class="form-control form-control-xl" placeholder="Email">
                            <small class="text-danger" id="email_addr-error" message="{{ __('home.please_enter_a_valid_email_address') }}"></small>
                        </div>

                        <div class="col-sm-12 mb-4">
                            <input type="text" id="subject" name="subject" class="form-control form-control-xl" placeholder="{{ __('home.subject') }}">
                            <small class="text-danger" id="subject-error" message="{{ __('home.please_enter_your_subject') }}"></small>
                        </div>

                        <div class="col-sm-12 mb-4">
                            <textarea cols="30" rows="5" class="form-control form-control-xl" id="message_text" placeholder="{{ __('home.your_message') }}"></textarea>
                            <small class="text-danger" id="message_text-error" message="{{ __('home.please_enter_your_message') }}"></small>
                        </div>

                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" id="send-message" type="button">
                            {{ __('home.send_message') }}
                        </button>
                        <button class="btn btn-primary btn-lg d-none" id="send-message-loading" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{ __('auth.loading') }}
                        </button>
                    </div>

                </form>


            </div>
        </div>

        <div class="col-lg-6 d-none d-lg-block">
            <div id="auth-right" class="presta-bg-color">
                <div class="d-flex flex-column justify-content-center align-items-center vh-100">
                    <img src="{{ asset('assets/img/logo/Prestavice-white1.png') }}" width="200" alt="">
                    <img src="{{ asset('assets/img/other/undraw_contact-us_kcoa.svg') }}" width="600" class="img-fluid" alt="...">
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
