@extends('base')
@section('title', __('auth.login'))
@section('content')

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

@endsection
