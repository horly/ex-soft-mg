@extends('base')
@section('title', __('profile.edit_profile_info'))
@section('content')

@include('menu.login-nav')

<div class="d-flex align-items-center container-margin-top">
    <div class="container">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app_profile') }}">{{ __('profile.profile_information') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('profile.edit_profile_info') }}</li>
            </ol>
        </nav>

        <form class="p-5 border bg-body-tertiary" action="{{ route('app_save_profile_info') }}" method="POST">
            @csrf
            
            <div class="mb-4 row">
                <label for="name_profile" class="col-sm-4 col-form-label">{{ __('main.name') }}*</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control @error('name_profile') is-invalid @enderror" name="name_profile" id="name_profile" value="{{ Auth::user()->name }}">
                    </div>
                    <small class="text-danger">@error('name_profile'){{ $message }}@enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="function_profile" class="col-sm-4 col-form-label">{{ __('main.function') }}*</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-briefcase"></i></span>
                        <select class="form-select @error('function_profile') is-invalid @enderror" id="function_profile" name="function_profile">
                            <option value="{{ Auth::user()->grade->id }}" selected>{{ Auth::user()->grade->name }}</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">@error('function_profile'){{ $message }}@enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="country_profile" class="col-sm-4 col-form-label">{{ __('auth.country') }}*</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-earth-africa"></i></span>
                        <select class="form-select country-select @error('country_profile') is-invalid @enderror" id="country_profile" name="country_profile">
                            @if (Config::get('app.locale') == 'en')
                                <option iso-code="{{ Auth::user()->country->telephone_code }}" value="{{ Auth::user()->country->id }}" selected>{{ Auth::user()->country->name_gb }}</option>
                                @foreach ($countries_gb as $country)
                                    <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_gb }} (+{{ $country->telephone_code }})</option>
                                @endforeach
                            @else
                                <option iso-code="{{ Auth::user()->country->telephone_code }}" value="{{ Auth::user()->country->id }}" selected>{{ Auth::user()->country->name_fr }}</option>
                                @foreach ($countries_fr as $country)
                                    <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_fr }} (+{{ $country->telephone_code }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <small class="text-danger">@error('country_profile'){{ $message }}@enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="phone_number_profile" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-phone"></i></span>
                        <span class="input-group-text">
                            +<span class="country-code-label">{{ Auth::user()->country->telephone_code }}</span>
                        </span>
                        <input type="number" class="form-control @error('phone_number_profile') is-invalid @enderror" name="phone_number_profile" id="phone_number_profile" value="{{ Auth::user()->phone_number }}">
                    </div>
                    <small class="text-danger">@error('phone_number_profile'){{ $message }}@enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="registration_number_profile" class="col-sm-4 col-form-label">{{ __('main.registration_number') }}*</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-barcode"></i></span>
                        <input type="text" class="form-control @error('registration_number_profile') is-invalid @enderror" name="registration_number_profile" id="registration_number_profile" value="{{ Auth::user()->matricule }}">
                    </div>
                    <small class="text-danger">@error('registration_number_profile'){{ $message }}@enderror</small>
                </div>
            </div>
            
            <div class="mb-4 row">
                <label for="address_profile" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                <div class="col-md-8">
                    <textarea name="address_profile" class="form-control @error('address_profile') is-invalid @enderror" id="address_profile" rows="5">{{ Auth::user()->address }}</textarea>
                </div>
            </div>

            @include('button.save-button')

        </form>

    </div>
</div>

<div class="m-5">
    @include('menu.footer-global')
</div>

@endsection