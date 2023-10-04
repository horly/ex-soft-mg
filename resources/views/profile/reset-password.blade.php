@extends('base')
@section('title', __('auth.reset_password'))
@section('content')

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

@endsection