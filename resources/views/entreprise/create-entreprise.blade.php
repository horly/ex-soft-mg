@extends('base')
@section('title', __('main.create_entreprise'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_main') }}">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.create_entreprise') }}</li>
        </ol>
    </nav>

    <form class="card" id="save-entreprise-form" action="{{ route('app_save_entreprise') }}" method="POST" token={{ csrf_token() }}>
        @csrf

        <div class="card-body">
          <input type="hidden" name="id_entreprise" value="0">
          <input type="hidden" name="entrepriseRequest" id="entrepriseRequest" value="add"> {{-- Default is add but can be edit also --}}
          
          <div class="mb-4 row">
              <label for="name_entreprise" class="col-sm-4 col-form-label">{{ __('main.company_name') }}*</label>
              <div class="col-sm-8">
                <input type="text" class="form-control @error('name_entreprise') is-invalid @enderror" id="name_entreprise" name="name_entreprise" placeholder="{{ __('main.enter_company_name') }}" value="{{ old('name_entreprise') }}">
                <small class="text-danger">@error('name_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          <div class="mb-4 row">
              <label for="slogan_entreprise" class="col-sm-4 col-form-label">Slogan</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="slogan_entreprise" name="slogan_entreprise" placeholder="{{ __('main.enter_your_company_slogan') }}" >
              </div>
          </div>
          <div class="mb-4 row">
              <label for="rccm_entreprise" class="col-sm-4 col-form-label">RCCM*</label>
              <div class="col-sm-8">
                <input type="text" class="form-control @error('rccm_entreprise') is-invalid @enderror" id="rccm_entreprise" name="rccm_entreprise" placeholder="{{ __('main.enter_your_companys_rccm') }}" value="{{ old('rccm_entreprise') }}">
                <small class="text-danger">@error('rccm_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          <div class="mb-4 row">
              <label for="idnat_entreprise" class="col-sm-4 col-form-label">ID NAT*</label>
              <div class="col-sm-8">
                <input type="text" class="form-control @error('idnat_entreprise') is-invalid @enderror" id="idnat_entreprise" name="idnat_entreprise" placeholder="{{ __('main.enter_your_companys_national_identification') }}" value="{{ old('idnat_entreprise') }}">
                <small class="text-danger">@error('idnat_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          <div class="mb-4 row">
              <label for="nif_entreprise" class="col-sm-4 col-form-label">NIF*</label>
              <div class="col-sm-8">
                <input type="text" class="form-control @error('nif_entreprise') is-invalid @enderror" id="nif_entreprise" name="nif_entreprise" placeholder="{{ __('main.enter_your_companys_tax_id_number') }}" value="{{ old('nif_entreprise') }}">
                <small class="text-danger">@error('nif_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          {{--
          <div class="mb-4 row">
              <label for="address_entreprise" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
              <div class="col-sm-8">
                <textarea class="form-control @error('address_entreprise') is-invalid @enderror" name="address_entreprise" id="address_entreprise" rows="4" placeholder="{{ __('main.enter_your_company_address') }}">{{ old('address_entreprise') }}</textarea>
                <small class="text-danger">@error('address_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          --}}
          <div class="mb-4 row">
            <label for="country_entreprise" class="col-sm-4 col-form-label">{{ __('main.country') }}*</label>
            <div class="col-sm-8">
              <select class="form-select @error('country_entreprise') is-invalid @enderror" name="country_entreprise" id="country_entreprise" onchange="changeIsoCode();">
                <option value="" selected>{{ __('main.select_your_company_country') }}</option>
                  @if (Config::get('app.locale') == 'en')
                      @foreach ($countries_gb as $country)
                          <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_gb }} (+{{ $country->telephone_code }})</option>
                      @endforeach
                  @else
                      @foreach ($countries_fr as $country)
                          <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_fr }} (+{{ $country->telephone_code }})</option>
                      @endforeach
                  @endif
              </select>
              <small class="text-danger">@error('country_entreprise') {{ $message }} @enderror</small>
            </div>
          </div>
          {{--
          <div class="mb-4 row">
              <label for="phone_entreprise" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
              <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text iso-code-label"></span>
                  <input type="number" class="form-control @error('phone_entreprise') is-invalid @enderror" id="phone_entreprise" name="phone_entreprise" placeholder="{{ __('main.enter_your_business_phone_number') }}" value="{{ old('phone_entreprise') }}">
                </div>
                <small class="text-danger">@error('phone_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          --}}
          {{--
          <div class="mb-4 row">
              <label class="col-sm-4 col-form-label"></label>
              <div class="col-sm-8">
                  <a class="link-underline-light" href="#"><i class="fa-solid fa-circle-plus"></i> {{ __('main.add_another_phone_number') }}</a>
              </div>
          </div>
          --}}
          {{--
          <div class="mb-4 row">
              <label for="email_entreprise" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
              <div class="col-sm-8">
                <input type="email" class="form-control @error('email_entreprise') is-invalid @enderror" id="email_entreprise" name="email_entreprise" placeholder="{{ __('main.enter_your_company_email_address') }}" value="{{ old('email_entreprise') }}">
                <small class="text-danger">@error('email_entreprise') {{ $message }} @enderror</small>
              </div>
          </div>
          --}}
          <div class="mb-4 row">
            <label for="website_entreprise" class="col-sm-4 col-form-label">{{ __('main.website') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">https://</span>
                  <input type="text" class="form-control" id="website_entreprise" name="website_entreprise" placeholder="{{ __('main.enter_your_company_website') }}" >
                </div>
            </div>
          </div>

          {{-- button de sauvegarde --}}
          @include('button.save-button')
        </div>

    </form>


    <div class="m-5">
      @include('menu.footer-global')
    </div>
</div>

@endsection
