@extends('base')
@section('title', __('auth.register'))
@section('content')

@include('menu.login-nav')

<div class="d-flex align-items-center container-margin-top">
    <div class="container">

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app_user_management') }}">{{ __('main.user_management') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('main.add_user') }}</li>
            </ol>
        </nav>

        <form id="form-register" class="p-5 rounded border bg-body-tertiary" action="{{ route('app_add_user') }}" method="post" token={{ csrf_token() }}>
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="firstName" class="form-label">{{ __('auth.first_name')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="firstName" id="firstName" class="form-control @error('firstName') is-invalid @enderror" placeholder="{{ __('auth.enter_the_firstname') }}" value="{{ old('firstName') }}" autocomplete="firstname" autofocus>
                    </div>
                    <small class="text-danger">@error('firstName'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="lastName" class="form-label">{{ __('auth.last_name')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="lastName" id="lastName" class="form-control @error('lastName') is-invalid @enderror" placeholder="{{ __('auth.enter_the_lastname') }}" value="{{ old('lastName') }}">
                    </div>
                    <small class="text-danger">@error('lastName'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-12 mb-4">
                    <label for="emailUsr" class="form-label">{{ __('auth.email')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="emailUsr" id="emailUsr" class="form-control @error('emailUsr') is-invalid @enderror" placeholder="{{ __('auth.enter_the_email') }}" value="{{ old('emailUsr') }}">
                    </div>
                    <small class="text-danger">@error('emailUsr'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="passwordUsr" class="form-label">{{ __('auth.password')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="passwordUsr" id="passwordUsr" class="form-control @error('passwordUsr') is-invalid @enderror" placeholder="{{ __('auth.create_your_password') }}" value="{{ old('passwordUsr') }}">
                        <span class="input-group-text cursor-pointer" id="show-password"><i class="fa-solid fa-eye"></i></span>
                        <span class="input-group-text cursor-pointer d-none" id="hide-password"><i class="fa-solid fa-eye-slash"></i></span>
                    </div>
                    <small class="text-danger">@error('passwordUsr'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="passwordConfirm" class="form-label">{{ __('auth.password_confirmation')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control @error('passwordConfirm') is-invalid @enderror" placeholder="{{ __('auth.confirm_your_password') }}" value="{{ old('passwordConfirm') }}">
                        <span class="input-group-text cursor-pointer" id="show-password-confirm"><i class="fa-solid fa-eye"></i></span>
                        <span class="input-group-text cursor-pointer d-none" id="hide-password-confirm"><i class="fa-solid fa-eye-slash"></i></span>
                    </div>
                    <small class="text-danger">@error('passwordConfirm'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="role" class="form-label">Role *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-universal-access"></i></span>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                            <option value="" selected>{{ __('main.choose') }}...</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ __('main.' . $role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">@error('role'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="function" class="form-label">{{ __('main.function') }} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-briefcase"></i></span>
                        <select class="form-select @error('function') is-invalid @enderror" id="function" name="function">
                            <option value="" selected>{{ __('main.choose') }}...</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">@error('function'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="phoneNumber" class="form-label">{{ __('main.phone_number')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-phone"></i></span>
                        <span class="input-group-text" id="phone-number-ind">+243</span>
                        <input type="number" name="phoneNumber" id="phoneNumber" class="form-control @error('phoneNumber') is-invalid @enderror" placeholder="ex : 896587458" value="{{ old('phoneNumber') }}">
                    </div>
                    <small class="text-danger">@error('phoneNumber'){{ $message }}@enderror</small>
                </div>


                <div class="col-md-6 mb-4">
                    <label for="matricule" class="form-label">{{ __('main.registration_number')}} *</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-barcode"></i></span>
                        <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" placeholder="{{ __('main.enter_the_registration_number')}}" value="{{ old('matricule') }}">
                    </div>
                    <small class="text-danger">@error('matricule'){{ $message }}@enderror</small>
                </div>

                <div class="col-md-12 mb-4">
                    <label for="address" class="form-label">{{ __('main.address') }}</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>

                {{-- Button ajout --}}
                @include('button.add-button')
            </div>
        </form>
 
    </div>
</div>

<div class="m-5">
    @include('menu.footer-global')
</div>


@endsection
