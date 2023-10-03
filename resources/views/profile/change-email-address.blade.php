@extends('base')
@section('title', __('auth.change_email_address'))
@section('content')

<div class="container-margin-top">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <img class="rounded mx-auto d-block" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="200">
                <p class="text-center text-muted">ERP</p>
                <p class="text-muted text-center h5 mb-5"> {{ __('auth.change_email_address') }}</p>

                <div class="d-flex justify-content-end mb-3">
                    @include('button.language-dropdown')
                </div>

                <form class="card" action="{{ route('app_change_email_address_post') }}" method="post">
                    @csrf

                    <div class="card-body bg-body-tertiary p-5">

                        @include('message.flash-message')

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4">
                            <label for="current_email" class="col-form-label">{{ __('profile.current_email_address') }}*</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control @error('current_email') is-invalid @enderror" name="current_email" id="current_email" placeholder="{{ __('profile.enter_the_current_email_address') }}" value="{{ old('current_email') }}">
                            </div>
                            <small class="text-danger">@error('current_email'){{ $message }}@enderror</small>
                        </div>

                        <div class="mb-4">
                            <label for="new_email" class="col-form-label">{{ __('profile.new_email_address') }}*</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control @error('new_email') is-invalid @enderror" name="new_email" id="new_email" placeholder="{{ __('profile.enter_the_new_email_address') }}" value="{{ old('new_email') }}">
                            </div>
                            <small class="text-danger">@error('new_email'){{ $message }}@enderror</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_new_email" class="col-form-label">{{ __('profile.confirm_the_new_email_address') }}*</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control @error('confirm_new_email') is-invalid @enderror" name="confirm_new_email" id="confirm_new_email" placeholder="{{ __('profile.enter_the_new_email_address') }}" value="{{ old('confirm_new_email') }}">
                            </div>
                            <small class="text-danger">@error('confirm_new_email'){{ $message }}@enderror</small>
                        </div>

                        <label for="password_new_email" class="form-label">{{ __('auth.password')}}</label>
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password_new_email" id="password_new_email" class="form-control @error('password_new_email') is-invalid @enderror" placeholder="{{ __('auth.enter_your_password') }}">
                            </div>
                            <small class="text-danger">@error('password_new_email'){{ $message }}@enderror</small>
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


@endsection