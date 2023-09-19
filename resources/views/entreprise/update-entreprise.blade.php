@extends('base')
@section('title', __('entreprise.update_the_company'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ $entreprise->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('entreprise.update_the_company') }}</li>
        </ol>
    </nav>

    <form class="border bg-body-tertiary p-4" action="{{ route('app_save_entreprise') }}" method="POST">
        @csrf

        <div class="mb-4 row">
            <label for="name_entreprise" class="col-sm-4 col-form-label">{{ __('main.company_name') }}*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control @error('name_entreprise') is-invalid @enderror" id="name_entreprise" name="name_entreprise" placeholder="{{ __('main.enter_company_name') }}" value="{{ $entreprise->name }}">
              <small class="text-danger">@error('name_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="slogan_entreprise" class="col-sm-4 col-form-label">Slogan</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="slogan_entreprise" name="slogan_entreprise" placeholder="{{ __('main.enter_your_company_slogan') }}" value="{{ $entreprise->slogan }}">
            </div>
        </div>

        <div class="mb-4 row">
            <label for="rccm_entreprise" class="col-sm-4 col-form-label">RCCM*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control @error('rccm_entreprise') is-invalid @enderror" id="rccm_entreprise" name="rccm_entreprise" placeholder="{{ __('main.enter_your_companys_rccm') }}" value="{{ $entreprise->rccm }}">
              <small class="text-danger">@error('rccm_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="idnat_entreprise" class="col-sm-4 col-form-label">ID NAT*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control @error('idnat_entreprise') is-invalid @enderror" id="idnat_entreprise" name="idnat_entreprise" placeholder="{{ __('main.enter_your_companys_national_identification') }}" value="{{ $entreprise->id_nat }}">
              <small class="text-danger">@error('idnat_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="nif_entreprise" class="col-sm-4 col-form-label">NIF*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control @error('nif_entreprise') is-invalid @enderror" id="nif_entreprise" name="nif_entreprise" placeholder="{{ __('main.enter_your_companys_tax_id_number') }}" value="{{ $entreprise->nif }}">
              <small class="text-danger">@error('nif_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="address_entreprise" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
            <div class="col-sm-8">
              <textarea class="form-control @error('address_entreprise') is-invalid @enderror" name="address_entreprise" id="address_entreprise" rows="4" placeholder="{{ __('main.enter_your_company_address') }}">{{ $entreprise->address }}</textarea>
              <small class="text-danger">@error('address_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="country_entreprise" class="col-sm-4 col-form-label">{{ __('main.country') }}*</label>
            <div class="col-sm-8">
              <select class="form-select @error('country_entreprise') is-invalid @enderror" name="country_entreprise" id="country_entreprise" onchange="changeIsoCode();">
                  @if (Config::get('app.locale') == 'en')
                        <option iso-code="{{ $entrepriseContry->telephone_code }}" value="{{ $entrepriseContry->id }}" selected>{{ $entrepriseContry->name_gb }}</option>
                        @foreach ($countries_gb as $country)
                            <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_gb }} (+{{ $country->telephone_code }})</option>
                        @endforeach
                  @else
                        <option iso-code="{{ $entrepriseContry->telephone_code }}" value="{{ $entrepriseContry->id }}" selected>{{ $entrepriseContry->name_fr }}</option>
                        @foreach ($countries_fr as $country)
                            <option iso-code="{{ $country->telephone_code }}" value="{{ $country->id }}">{{ $country->name_fr }} (+{{ $country->telephone_code }})</option>
                        @endforeach
                  @endif
              </select>
              <small class="text-danger">@error('country_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>

        <div class="mb-4 row">
            {{-- Hidden input seulement pour valider le formulaire --}}
            <input type="hidden" name="phone_entreprise" id="phone_entreprise" value="123456789">
            <label for="phone_entreprise" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}</label>
            <div class="col-sm-8">
                <div class="input-group mb-3">
                    <span class="input-group-text iso-code-label">+{{ $entrepriseContry->telephone_code }}</span>
                    @foreach ($phoneNumbers as $phone)
                        <span class="input-group-text">{{ chunk_split($phone->phone_number, 3, ' ') }}</span>
                    @endforeach
                    <button class="btn btn-primary" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#newPhone">
                        <i class="fa-solid fa-circle-plus"></i>
                        {{ __('entreprise.add_a_new_number') }}
                    </button>
                </div>
            </div>
        </div>
        
    </form>


    <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>

{{-- Modals--}}
<div class="modal fade" id="newPhone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('app_add_new_phone_number_entreprise') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('entreprise.add_a_new_number') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf

                
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary save" type="button" id="save-number_entreprise">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loading d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </div> 
            </div>
        </form>
    </div>
</div>

@endsection