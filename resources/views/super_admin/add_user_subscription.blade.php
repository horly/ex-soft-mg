@extends('base')
@section('title', __('main.add_user'))
@section('content')

<div id="app">

    @include('super_admin.navigation-menu-super-admin')

    @include('menu.login-nav')

    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">

                        @if ($user)
                            <h3>{{ __('super_admin.update_user') }}</h3>
                        @else
                            <h3>{{ __('main.add_user') }}</h3>
                        @endif

                        <p class="text-subtitle text-muted"></p>
                    </div>

                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('app_user_super_admin') }}">{{ __('super_admin.users') }}</a></li>

                                @if ($user)
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('super_admin.update_user') }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('super_admin.add_user') }}</li>
                                @endif

                            </ol>
                        </nav>
                    </div>

                </div>
            </div>

            {{-- On inlut les messages flash--}}
            @include('message.flash-message')

            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('app_add_user') }}">
                            @csrf

                            <input type="hidden" name="id_user" value="{{ $user ? $user->id : 0 }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="{{ $user ? "edit" : "add" }}">
                            <input type="hidden" name="add_type" value="super_admin">

                            @php
                                if ($user) {
                                    $result = explode(" ", $user->name);
                                    $firstname = $result[0];
                                    $lastname = $result[1];
                                }
                            @endphp

                            <div class="mb-4 row">
                                <label for="firstName" class="col-sm-4 col-form-label">{{ __('auth.first_name')}} *</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control @error('firstName') is-invalid @enderror" name="firstName" id="firstName" placeholder="{{ __('auth.enter_the_firstname') }}" value="{{ $user ? $firstname : old('firstName') }}">
                                    </div>
                                    <small class="text-danger">@error('firstName'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="lastName" class="col-sm-4 col-form-label">{{ __('auth.last_name')}} *</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" id="lastName" placeholder="{{ __('auth.enter_the_lastname') }}" value="{{ $user ? $lastname : old('lastName') }}">
                                    </div>
                                    <small class="text-danger">@error('lastName'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="emailUsr" class="col-sm-4 col-form-label">{{ __('auth.email')}} *</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('emailUsr') is-invalid @enderror" name="emailUsr" id="emailUsr" placeholder="{{ __('auth.enter_the_email') }}" value="{{ $user ? $user->email : old('emailUsr') }}">
                                    </div>
                                    <small class="text-danger">@error('emailUsr'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="passwordUsr" class="col-sm-4 col-form-label">{{ __('super_admin.default_password')}}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                        <input type="text" name="passwordUsr" id="passwordUsr" class="form-control @error('passwordUsr') is-invalid @enderror" placeholder="{{ __('auth.create_your_password') }}" value="123456789" readonly>
                                    </div>
                                </div>
                            </div>

                            {{--

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

                            --}}

                            <div class="mb-4 row">
                                <label for="role" class="col-sm-4 form-label">{{ __('auth.role') }} *</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-universal-access"></i></span>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                            @if ($user)
                                                <option value="{{ $user->role->id }}" selected>{{ __('main.' . $user->role->name) }}</option>
                                            @else
                                                <option value="" selected>{{ __('main.choose') }}...</option>
                                            @endif

                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ __('main.' . $role->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-danger">@error('role'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="function" class="col-sm-4 form-label">{{ __('main.function') }} *</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-briefcase"></i></span>
                                        <input type="text" class="form-control @error('function') is-invalid @enderror" id="function" name="function" placeholder="{{ __('main.enter_your_grade') }}" value="{{ $user ? $user->grade : old('grade') }}">
                                        {{--
                                        <select class="form-select @error('function') is-invalid @enderror" id="function" name="function">
                                            <option value="" selected>{{ __('main.choose') }}...</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                        --}}
                                    </div>
                                    <small class="text-danger">@error('function'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="countryUsr" class="col-sm-4 col-form-label">{{ __('auth.country') }}*</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-earth-africa"></i></span>
                                        <select class="form-select country-select @error('countryUsr') is-invalid @enderror" id="country_profile" name="countryUsr">

                                            @if (Config::get('app.locale') == 'en')
                                                @if ($user)
                                                    <option iso-code="{{ $user->country->telephone_code }}" value="{{ $user->country->id }}">{{ $user->country->name_gb }} (+{{ $user->country->telephone_code }})</option>
                                                @else
                                                    <option iso-code="" value="" selected>{{ __('auth.select_the_country') }}</option>
                                                @endif

                                                @foreach ($countries_gb as $country)
                                                    <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_gb }} (+{{ $country->telephone_code }})</option>
                                                @endforeach
                                            @else
                                                @if ($user)
                                                    <option iso-code="{{ $user->country->telephone_code }}" value="{{ $user->country->id }}">{{ $user->country->name_fr }} (+{{ $user->country->telephone_code }})</option>
                                                @else
                                                    <option iso-code="" value="" selected>{{ __('auth.select_the_country') }}</option>
                                                @endif

                                                @foreach ($countries_fr as $country)
                                                    <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_fr }} (+{{ $country->telephone_code }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <small class="text-danger">@error('countryUsr'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="phoneNumber" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-phone"></i></span>
                                        <span class="input-group-text">
                                            +<span class="country-code-label">{{ $user ? $user->country->telephone_code : "" }}</span>
                                        </span>
                                        <input type="number" class="form-control @error('phoneNumber') is-invalid @enderror" name="phoneNumber" id="phoneNumber" placeholder="{{ __('auth.enter_the_phone_number') }}" value="{{ $user ? $user->phone_number : old('phoneNumber') }}">
                                    </div>
                                    <small class="text-danger">@error('phoneNumber'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="matricule" class="col-sm-4 col-form-label">{{ __('main.registration_number') }}*</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-barcode"></i></span>
                                        <input type="text" class="form-control @error('matricule') is-invalid @enderror" name="matricule" id="matricule" placeholder="{{ __('main.enter_the_registration_number')}}" value="{{ $user ? $user->matricule  : "00000" }}">
                                    </div>
                                    <small class="text-danger">@error('matricule'){{ $message }}@enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="address" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                                <div class="col-md-8">
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="5" placeholder="{{ __('auth.enter_the_address') }}">{{ $user ? $user->address : ""}}</textarea>
                                </div>
                            </div>

                            {{-- Button ajout --}}
                            @include('button.add-button')
                        </form>
                    </div>
                </div>
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>

        </div>

    </div>
</div>

@endsection
