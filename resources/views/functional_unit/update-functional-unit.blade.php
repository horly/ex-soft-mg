@extends('base')
@section('title', __('entreprise.update_functional_unit'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_fu_infos', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ $functionalUnit->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('entreprise.update_functional_unit') }}</li>
        </ol>
    </nav>

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <form class="card" action="{{ route('app_save_functional_unit') }}" method="POST">
        @csrf

        <div class="card-body">
            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
            <input type="hidden" name="fuRequest" id="fuRequest" value="edit"> {{-- Default is add but can be edit also --}}

            <div class="mb-4 row">
                <label for="unit_name" class="col-sm-4 col-form-label">{{ __('entreprise.functional_unit_name') }}*</label>
                <div class="col-sm-8">
                <input type="text" class="form-control @error('unit_name') is-invalid @enderror" id="unit_name" name="unit_name" placeholder="{{ __('entreprise.enter_your_functional_unit_name') }}" value="{{ $functionalUnit->name }}">
                <small class="text-danger">@error('unit_name') {{ $message }} @enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="currency_fu" class="col-sm-4 col-form-label">{{ __('entreprise.default_currency') }}*</label> 
                <div class="col-sm-8">
                  <select name="currency_fu" id="currency_fu" class="form-select @error('currency_fu') is-invalid @enderror">
                      @if (Config::get('app.locale') == 'en')
                          @foreach ($devises as $devise)
                            <option value="{{ $deviseDefault->id }}" selected>{{ $deviseDefault->iso_code }} - {{ $deviseDefault->motto_en }}</option>
                            <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                          @endforeach
                      @else
                          @foreach ($devises as $devise)
                            <option value="{{ $deviseDefault->id }}" selected>{{ $deviseDefault->iso_code }} - {{ $deviseDefault->motto }}</option>
                            <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                          @endforeach
                      @endif
                  </select>
                  <small class="text-danger">@error('currency_fu') {{ $message }} @enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="unit_address" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                <div class="col-sm-8">
                <textarea class="form-control  @error('unit_address') is-invalid @enderror" name="unit_address" id="unit_address" rows="4" placeholder="{{ __('entreprise.enter_your_functional_unit_address') }}">{{ $functionalUnit->address }}</textarea>
                <small class="text-danger">@error('unit_address') {{ $message }} @enderror</small>
                </div>
            </div>

            <div class="mb-4 row">
                <label for="unit_phone" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}</label>
                <input type="hidden" id="unit_phone" name="unit_phone" value="1234567890">
                <div class="col-sm-8">
                    <ul class="list-group list-group-flush border mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('entreprise.all_your_phone_numbers') }}
                        </li>
                        @foreach ($phones as $phone)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span><i class="fa-solid fa-phone"></i></span>&nbsp;&nbsp;
                                    {{ '+' . $country->telephone_code }}
                                    {{ chunk_split($phone->phone_number, 3, ' ') }}
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
                    <button class="btn btn-primary" type="button" onclick="addNewPhoneNModal();" data-bs-toggle="modal" data-bs-target="#newPhone">
                        <i class="fa-solid fa-circle-plus"></i>
                        {{ __('auth.add') }}
                    </button>

                </div>
            </div>
            
            <div class="mb-4 row">
                {{-- Hidden input seulement pour valider le formulaire --}}
                <input type="hidden" name="unit_email" id="unit_email" value="sales@exadgroup.org">
                <label for="unit_email" class="col-sm-4 col-form-label">{{ __('main.email_address') }}</label>
                <div class="col-sm-8">
                    <ul class="list-group list-group-flush border mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('entreprise.all_your_email_address') }}
                        </li>
                        @foreach ($emails as $email)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span><i class="fa-solid fa-envelope"></i></span>&nbsp;&nbsp;
                                    <span>{{ $email->email }}</span>
                                </div>
                                <div>
                                    <button class="btn btn-success" type="button" onclick="editNewEmailNModal('{{ $email->id }}', '{{ $email->email }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#newEmail">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $email->id }}', '{{ route('app_delete_email_entreprise') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
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

            {{-- button de sauvegarde --}}
            @include('button.save-button')

        </div>
    </form>

    <div class="m-5">
        @include('menu.footer-global')
    </div>

</div>

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
                    <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
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
                    <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
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