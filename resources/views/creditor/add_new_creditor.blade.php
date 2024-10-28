@extends('base')
@section('title', __('creditor.add_new_creditor'))
@section('content')

<div id="app">
    @include('menu.navigation-menu')

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
                        <h3>{{ __('creditor.add_new_creditor') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_creditor', ['group' => 'creditor', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.creditors') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('creditor.add_new_creditor') }}</li>
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
                        <form action="{{ route('app_create_creditor') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_creditor" value="0">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="customer_type_cr" class="col-sm-4 col-form-label">{{ __('creditor.creditor_type') }}*</label>
                                <div class="col-sm-8">
                                  <select name="customer_type_cr" id="customer_type_cr" class="form-select type_contact @error('customer_type_cr') is-invalid @enderror">
                                      <option value="" selected>{{ __('creditor.select_creditor_type') }}</option>
                                      <option value="particular">{{ __('client.particular') }}</option>
                                      <option value="company">{{ __('client.company') }}</option>
                                  </select>
                                  <small class="text-danger">@error('customer_type_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="border-bottom mb-4 fw-bold d-none company_info_contact">
                                {{ __('creditor.creditor_company_information') }}
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_name_cr" class="col-sm-4 col-form-label">{{ __('main.company_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('company_name_cr') is-invalid @enderror" id="company_name_cr" name="company_name_cr" placeholder="{{ __('client.enter_the_company_name') }}" value="{{ old('company_name_cr') }}">
                                    <small class="text-danger">@error('company_name_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_rccm_cr" class="col-sm-4 col-form-label">RCCM</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_rccm_cr" name="company_rccm_cr" placeholder="{{ __('client.enter_the_company_rccm') }}" value="{{ old('company_rccm_cr') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_id_nat_cr" class="col-sm-4 col-form-label">ID NAT</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_id_nat_cr" name="company_id_nat_cr" placeholder="{{ __('client.enter_the_company_id_nat') }}" value="{{ old('company_id_nat_cr') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_nif_cr" class="col-sm-4 col-form-label">NIF</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_nif_cr" name="company_nif_cr" placeholder="{{ __('client.enter_the_companys_tax_number') }}" value="{{ old('company_nif_cr') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_account_number_cr" class="col-sm-4 col-form-label">{{ __('entreprise.account_number') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_account_number_cr" name="company_account_number_cr" placeholder="{{ __('client.enter_the_account_number') }}" value="{{ old('company_account_number_cr') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_website_cr" class="col-sm-4 col-form-label">{{ __('main.website') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                      <span class="input-group-text" id="basic-addon1">https://</span>
                                      <input type="text" class="form-control" id="company_website_cr" name="company_website_cr" placeholder="{{ __('client.enter_the_company_website') }}" value="{{ old('company_website_cr') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="border-bottom mb-4 fw-bold">
                                {{ __('creditor.creditor_contact_details') }}
                            </div>

                            <div class="mb-4 row">
                                <label for="full_name_cr" class="col-sm-4 col-form-label">{{ __('client.full_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('full_name_cr') is-invalid @enderror" id="full_name_cr" name="full_name_cr" placeholder="{{ __('creditor.creditor_full_name') }}" value="{{ old('full_name_cr') }}">
                                    <small class="text-danger">@error('full_name_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="grade_cr" class="col-sm-4 col-form-label">{{ __('client.grade') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('grade_cr') is-invalid @enderror" id="grade_cr" name="grade_cr" placeholder="{{ __('creditor.creditor_grade') }}" value="{{ old('grade_cr') }}">
                                    <small class="text-danger">@error('grade_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="email_cr" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control @error('email_cr') is-invalid @enderror" id="email_cr" name="email_cr" placeholder="{{ __('creditor.enter_the_creditors_email_address') }}" value="{{ old('email_cr') }}">
                                    <small class="text-danger">@error('email_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="phone_number_cr" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control @error('phone_number_cr') is-invalid @enderror" id="phone_number_cr" name="phone_number_cr" placeholder="{{ __('creditor.enter_the_creditors_phone_number') }}" value="{{ old('phone_number_cr') }}">
                                    <small class="text-danger">@error('phone_number_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="address_cr" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control  @error('address_cr') is-invalid @enderror" name="address_cr" id="address_cr" rows="4" placeholder="{{ __('creditor.enter_the_creditors_business_address') }}">{{ old('address_cr') }}</textarea>
                                  <small class="text-danger">@error('address_cr') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
                            @endif

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
