@extends('base')
@section('title', __('auth.register'))
@section('content')

<div class="vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <img class="rounded mx-auto d-block" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="200">
                <p class="text-center text-muted mb-5">Soft Manager</p>

                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#">{{ __('main.home') }}</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{ __('main.add_user') }}</li>
                    </ol>
                </nav>

                <form id="form-register" class="p-5 rounded border bg-body-tertiary" action="{{ route('app_add_user') }}" method="post" token={{ csrf_token() }}>
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="firstname" class="form-label">{{ __('auth.first_name')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="firstname" id="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="{{ __('auth.enter_your_firstname') }}" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus>
                            </div>
                            <small class="error-register-field text-danger" id="error-firstname"></small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="lastname" class="form-label">{{ __('auth.last_name')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="lastname" id="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="{{ __('auth.enter_your_lastname') }}" value="{{ old('lastname') }}" required autocomplete="lastname">
                            </div>
                            <small class="error-register-field text-danger" id="error-lastname"></small>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="email" class="form-label">{{ __('auth.email')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('auth.enter_your_email') }}" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            <small class="error-register-field text-danger" id="error-email"></small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">{{ __('auth.password')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('auth.create_your_password') }}">
                            </div>
                            <small class="error-register-field text-danger" id="error-password"></small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password-confirm" class="form-label">{{ __('auth.password_confirmation')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password-confirm" id="password-confirm" class="form-control" placeholder="{{ __('auth.confirm_your_password') }}">
                            </div>
                            <small class="error-register-field text-danger" id="error-password-confirmation"></small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label">Role *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-universal-access"></i></span>
                                <select class="form-select" id="role" name="role">
                                    <option value="" selected>{{ __('main.choose') }}...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ __('main.' . $role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="error-register-field text-danger" id="error-role"></small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="function" class="form-label">{{ __('main.function') }} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-briefcase"></i></span>
                                <select class="form-select" id="function" name="function">
                                    <option value="" selected>{{ __('main.choose') }}...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="error-register-field text-danger" id="error-function"></small>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="phone-number" class="form-label">{{ __('main.phone_number')}} *</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-phone"></i></span>
                                <span class="input-group-text" id="phone-number-ind">+243</span>
                                <input type="text" name="phone-number" id="phone-number" class="form-control" placeholder="ex : 896587458">
                            </div>
                            <small class="error-register-field text-danger" id="error-phone-number"></small>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="address" class="form-label">{{ __('main.address') }}</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button id="register-user" class="btn btn-primary" type="button">{{ __('auth.register')}}</button>
                            <button id="loading-btn" class="btn btn-primary d-none" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ __('auth.loading') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column mb-3">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="d-flex flex-row h-100 align-items-center">
                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>Flex item 2</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('main.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form --}}
<form>
    <input type="hidden" id="error-firstname-register-message" value="{{ __('auth.error_firstname_register_message')}}">
    <input type="hidden" id="error-lastname-register-message" value="{{ __('auth.error_lastname_register_message')}}">
    <input type="hidden" id="error-email-register-message" value="{{ __('auth.error_email_register_message')}}">
    <input type="hidden" id="error-password-register-message" value="{{ __('auth.error_password_register_message')}}">
    <input type="hidden" id="error-password-confirmation-register-message" value="{{ __('auth.password_confirmation_register_message')}}">
    <input type="hidden" id="error-country-register-message" value="{{ __('auth.error_country_register_message')}}">

    <input type="hidden" id="error-role-register-message" value="{{ __('auth.error_role_register_message')}}">
    <input type="hidden" id="error-function-register-message" value="{{ __('auth.error_function_register_message')}}">
    <input type="hidden" id="error-phone-number-register-message" value="{{ __('auth.error_phone_number_register_message')}}">
</form>

@endsection
