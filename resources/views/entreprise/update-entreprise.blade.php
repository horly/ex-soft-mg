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

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <form class="border bg-body-tertiary p-4" action="{{ route('app_save_entreprise') }}" method="POST">
        @csrf

        <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
        <input type="hidden" name="entrepriseRequest" id="entrepriseRequest" value="edit"> {{-- Default is add but can be edit also --}}

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
        {{--
        <div class="mb-4 row">
            <label for="address_entreprise" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
            <div class="col-sm-8">
              <textarea class="form-control @error('address_entreprise') is-invalid @enderror" name="address_entreprise" id="address_entreprise" rows="4" placeholder="{{ __('main.enter_your_company_address') }}">{{ $entreprise->address }}</textarea>
              <small class="text-danger">@error('address_entreprise') {{ $message }} @enderror</small>
            </div>
        </div>
        --}}

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
        {{--
        <div class="mb-4 row">
            {{-- Hidden input seulement pour valider le formulaire --}}
            {{--
            <input type="hidden" name="phone_entreprise" id="phone_entreprise" value="123456789">
            <label for="phone_entreprise" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}</label>
            <div class="col-sm-8">
                <div class="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button mini-accordion-button-header" type="button" data-bs-toggle="collapse" data-bs-target="#phones" aria-expanded="true" aria-controls="phones">
                                {{ __('entreprise.all_your_phone_numbers') }}
                          </button>
                        </h2>
                        <div id="phones" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="list-group list-group-flush border mb-3">
                                    @foreach ($phoneNumbers as $phone)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><i class="fa-solid fa-phone"></i></span>&nbsp;&nbsp;
                                                <span class="">+{{ $entrepriseContry->telephone_code }}</span>
                                                <span>{{ chunk_split($phone->phone_number, 3, ' ') }}</span>
                                            </div>
                                            <div>
                                                <button class="btn btn-success" type="button" onclick="editNewPhoneNModal('{{ $phone->id }}', '{{ $phone->phone_number }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#newPhone">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $phone->id }}', '{{ route('app_delete_phone_number_entreprise') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                            
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="btn btn-primary" type="button" onclick="addNewPhoneNModal();"  data-bs-toggle="modal" data-bs-target="#newPhone">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    {{ __('auth.add') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --}}
        {{--
        <div class="mb-4 row">
            {{-- Hidden input seulement pour valider le formulaire --}}
            {{--
            <input type="hidden" name="email_entreprise" id="email_entreprise" value="sales@exadgroup.org">
            <label for="email_entreprise" class="col-sm-4 col-form-label">{{ __('main.email_address') }}</label>
            <div class="col-sm-8">
                <div class="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button mini-accordion-button-header" type="button" data-bs-toggle="collapse" data-bs-target="#emails" aria-expanded="true" aria-controls="phones">
                                  {{ __('entreprise.all_your_email_address') }}
                            </button>
                        </h2>

                        <div id="emails" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="list-group list-group-flush border mb-3">
                                    @foreach ($emailAdresss as $emails)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><i class="fa-solid fa-envelope"></i></span>&nbsp;&nbsp;
                                                <span>{{ $emails->email }}</span>
                                            </div>
                                            <div>
                                                <button class="btn btn-success" type="button" onclick="editNewEmailNModal('{{ $emails->id }}', '{{ $emails->email }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#newEmail">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $emails->id }}', '{{ route('app_delete_email_entreprise') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="btn btn-primary" type="button" onclick="addNewEmailNModal();" data-bs-toggle="modal" data-bs-target="#newEmail">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    {{ __('auth.add') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --}}

        <div class="mb-4 row">
            <label for="bank_account_entreprise" class="col-sm-4 col-form-label">{{ __('main.bank_account') }}</label>
            <div class="col-sm-8">
                <div class="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button mini-accordion-button-header" type="button" data-bs-toggle="collapse" data-bs-target="#banks" aria-expanded="true" aria-controls="phones">
                                  {{ __('entreprise.all_your_bank_account') }}
                            </button>
                        </h2>
                        <div id="banks" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="list-group list-group-flush border mb-3">
                                    @foreach ($bankAccounts as $bank)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            @php
                                                $devise = DB::table('devises')->where('id', $bank->id_devise)->first();
                                            @endphp
                                            <div>
                                                <span><i class="fa-solid fa-building-columns"></i></span>&nbsp;&nbsp;
                                                <span>{{ $bank->bank_name }}</span>&nbsp;&nbsp;
                                                <span>/</span>&nbsp;&nbsp;
                                                <span>{{ $bank->account_number }}</span>&nbsp;&nbsp;
                                                <span>-</span>&nbsp;&nbsp;
                                                <span>{{ $devise->iso_code }}</span>&nbsp;&nbsp;
                                                <span>-</span>&nbsp;&nbsp;
                                                <span>{{ $bank->account_title }}</span>&nbsp;&nbsp;
                                            </div>
                                            <div>
                                                <button class="btn btn-success" type="button" onclick="editBankAccountNModal('{{ $bank->id }}', '{{ $bank->bank_name }}', '{{ $bank->account_title }}', '{{ $bank->account_number }}', '{{ $devise->id }}', '{{ $devise->iso_code }}', '{{ $devise->motto_en }}', '{{ $devise->motto }}', '{{ route('app_get_all_devise_json_format') }}', '{{ csrf_token() }}', '{{ Config::get('app.locale') }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#newAccount">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $bank->id }}', '{{ route('app_delete_bank_account') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="btn btn-primary" type="button" onclick="addBankAccountNModal();" data-bs-toggle="modal" data-bs-target="#newAccount">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    {{ __('auth.add') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="mb-4 row">
            <label for="website_entreprise" class="col-sm-4 col-form-label">{{ __('main.website') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">https://</span>
                  <input type="text" class="form-control" id="website_entreprise" name="website_entreprise" placeholder="{{ __('main.enter_your_company_website') }}" value="{{ $entreprise->website }}">
                </div>
            </div>
        </div>

        {{-- button de sauvegarde --}}
        @include('button.save-button')
        
    </form>


    <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>

@php
    $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
@endphp

{{-- Modal new phone--}}
<div class="modal fade" id="newPhone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_new_phone_number_entreprise" action="{{ route('app_add_new_phone_number_entreprise') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-number-phone-modal">{{-- Le est complété selon la requête avec javascript --}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary  p-4">
                @csrf
                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="id_phone" id="id_phone" value="0"> {{-- Default value of number is 0 but can be changed if edit--}}

                <label for="new_phone_number" class="form-label"> {{ __('main.phone_number') }} </label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">+{{ $country->telephone_code }}</span>
                    <input type="number" class="form-control" id="new_phone_number" name="new_phone_number" placeholder="{{ __('main.enter_your_business_phone_number') }}">
                </div>
                <small class="text-danger" id="error_new_phone_number"></small>
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="save_number_entreprise">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </div> 
            </div>
        </form>
    </div>
</div>

{{-- Modal new email--}}
<div class="modal fade" id="newEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_new_email_entreprise" action="{{ route('app_add_new_email_entreprise') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-email-modal">{{ __('entreprise.add_new_email_address') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf
                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="id_email" id="id_email" value="0"> {{-- Default value of number is 0 but can be changed if edit--}}

                <div class="mb-3">
                    <label for="new_email_address" class="form-label"> {{ __('main.email_address') }} </label>
                    <input type="email" class="form-control" id="new_email_address" name="new_email_address" placeholder="{{ __('main.enter_your_company_email_address') }}">
                    <small class="text-danger" id="error_new_email_addressr"></small>
                </div>
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="save_email_entreprise">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </div> 
            </div>
        </form>
    </div>
</div>

{{-- Modal new Account--}}
<div class="modal fade" id="newAccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_new_bank_account_entreprise" action="{{ route('app_add_new_bank_account_entreprise') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-bank-account-modal">{{ __('entreprise.add_a_new_bank_account') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf
                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="id_bank" id="id_bank" value="0"> {{-- Default value of number is 0 but can be changed if edit--}}

                <div class="mb-3">
                    <label for="bank_name" class="form-label"> {{ __('entreprise.bank_name') }} </label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="{{ __('entreprise.enter_your_bank_name') }}">
                    <small class="text-danger" id="error_bank_name"></small>
                </div>
                <div class="mb-3">
                    <label for="account_title" class="form-label"> {{ __('entreprise.account_title') }} </label>
                    <input type="text" class="form-control" id="account_title" name="account_title" placeholder="{{ __('entreprise.enter_your_account_title') }}">
                    <small class="text-danger" id="error_account_title"></small>
                </div>
                <div class="mb-3">
                    <label for="account_number" class="form-label"> {{ __('entreprise.account_number') }} </label>
                    <input type="number" class="form-control" id="account_number" name="account_number" placeholder="{{ __('entreprise.enter_your_account_number') }}">
                    <small class="text-danger" id="error_account_number"></small>
                </div>
                <div class="mb-3" id="save-select-entreprise">
                    <label for="account_currency_save" class="form-label"> {{ __('entreprise.currency') }} </label>
                    <select name="account_currency_save" id="account_currency_save" class="form-select">
                        <option value="" selected>{{ __('entreprise.select_your_curreny') }}</option>
                        @if (Config::get('app.locale') == 'en')
                            @foreach ($devises as $devise)
                                <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                            @endforeach
                        @else
                            @foreach ($devises as $devise)
                                <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-danger" id="error_account_currency"></small>
                </div>
                <div class="mb-3 d-none" id="update-select-entreprise">
                    <label for="account_currency_update" class="form-label"> {{ __('entreprise.currency') }} </label>
                    <select name="account_currency_update" id="account_currency_update" class="form-select">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="save_bank_account_entreprise">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </div> 
            </div>
        </form>
    </div>
</div>

<form>
    <input type="hidden" id="message_new_phone_number_entreprise" value="{{ __('main.enter_a_valid_phone_number_please') }}">
    <input type="hidden" id="message_new_email_entreprise" value="{{ __('main.enter_a_valid_company_email_address_please') }}">
    <input type="hidden" id="message_account_title" value="{{ __('entreprise.enter_your_account_title_please') }}">
    <input type="hidden" id="message_account_number" value="{{ __('entreprise.enter_your_account_number_please') }}">
    <input type="hidden" id="message_account_currency" value="{{ __('entreprise.select_your_curreny_please') }}">
    <input type="hidden" id="message_bank_name" value="{{ __('entreprise.enter_your_bank_name_please') }}">

    <input type="hidden" id="title_add_a_new_number" value="{{ __('entreprise.add_a_new_number') }}">
    <input type="hidden" id="title_edit_the_phone_number" value="{{ __('entreprise.edit_the_phone_number') }}">

    <input type="hidden" id="title_add_new_email_address" value="{{ __('entreprise.add_new_email_address') }}">
    <input type="hidden" id="title_edit_the_email_address" value="{{ __('entreprise.edit_the_email_address') }}">

    <input type="hidden" id="title_add_a_new_bank_account" value="{{ __('entreprise.add_a_new_bank_account') }}">
    <input type="hidden" id="title_edit_a_new_bank_account" value="{{ __('entreprise.edit_a_new_bank_account') }}">

</form>

@endsection