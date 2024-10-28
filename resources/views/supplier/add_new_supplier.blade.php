@extends('base')
@section('title', __('supplier.add_new_supplier'))
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
                        <h3>{{ __('supplier.add_new_supplier') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_supplier', ['group' => 'supplier', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('supplier.suppliers') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('supplier.add_new_supplier') }}</li>
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
                        <form action="{{ route('app_create_supplier') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_supplier" value="0">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="customer_type_sup" class="col-sm-4 col-form-label">{{ __('supplier.supplier_type') }}*</label>
                                <div class="col-sm-8">
                                  <select name="customer_type_sup" id="customer_type_sup" class="form-select type_contact @error('customer_type_sup') is-invalid @enderror">
                                      <option value="" selected>{{ __('supplier.select_supplier_type') }}</option>
                                      <option value="particular">{{ __('client.particular') }}</option>
                                      <option value="company">{{ __('client.company') }}</option>
                                  </select>
                                  <small class="text-danger">@error('customer_type_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="border-bottom mb-4 fw-bold d-none company_info_contact">
                                {{ __('supplier.supplier_company_information') }}
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_name_sup" class="col-sm-4 col-form-label">{{ __('main.company_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('company_name_sup') is-invalid @enderror" id="company_name_sup" name="company_name_sup" placeholder="{{ __('client.enter_the_company_name') }}" value="{{ old('company_name_sup') }}">
                                    <small class="text-danger">@error('company_name_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_rccm_sup" class="col-sm-4 col-form-label">RCCM</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_rccm_sup" name="company_rccm_sup" placeholder="{{ __('client.enter_the_company_rccm') }}" value="{{ old('company_rccm_sup') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_id_nat_sup" class="col-sm-4 col-form-label">ID NAT</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_id_nat_sup" name="company_id_nat_sup" placeholder="{{ __('client.enter_the_company_id_nat') }}" value="{{ old('company_id_nat_sup') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_nif_sup" class="col-sm-4 col-form-label">NIF</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_nif_sup" name="company_nif_sup" placeholder="{{ __('client.enter_the_companys_tax_number') }}" value="{{ old('company_nif_sup') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_account_number_sup" class="col-sm-4 col-form-label">{{ __('entreprise.account_number') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_account_number_sup" name="company_account_number_sup" placeholder="{{ __('client.enter_the_account_number') }}" value="{{ old('company_account_number_sup') }}">
                                </div>
                            </div>

                            <div class="mb-4 row d-none company_info_contact">
                                <label for="company_website_sup" class="col-sm-4 col-form-label">{{ __('main.website') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                      <span class="input-group-text" id="basic-addon1">https://</span>
                                      <input type="text" class="form-control" id="company_website_sup" name="company_website_sup" placeholder="{{ __('client.enter_the_company_website') }}" value="{{ old('company_website_sup') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="border-bottom mb-4 fw-bold">
                                {{ __('supplier.supplier_contact_details') }}
                            </div>

                            <div class="mb-4 row">
                                <label for="full_name_sup" class="col-sm-4 col-form-label">{{ __('client.full_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('full_name_sup') is-invalid @enderror" id="full_name_sup" name="full_name_sup" placeholder="{{ __('supplier.supplier_full_name') }}" value="{{ old('full_name_sup') }}">
                                    <small class="text-danger">@error('full_name_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="grade_sup" class="col-sm-4 col-form-label">{{ __('client.grade') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('grade_sup') is-invalid @enderror" id="grade_sup" name="grade_sup" placeholder="{{ __('supplier.supplier_grade') }}" value="{{ old('grade_sup') }}">
                                    <small class="text-danger">@error('grade_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="email_sup" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control @error('email_sup') is-invalid @enderror" id="email_sup" name="email_sup" placeholder="{{ __('supplier.enter_the_supplier_email_address') }}" value="{{ old('email_sup') }}">
                                    <small class="text-danger">@error('email_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="phone_number_sup" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control @error('phone_number_sup') is-invalid @enderror" id="phone_number_sup" name="phone_number_sup" placeholder="{{ __('supplier.enter_the_suppliers_phone_number') }}" value="{{ old('phone_number_sup') }}">
                                    <small class="text-danger">@error('phone_number_sup') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="address_sup" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control  @error('address_sup') is-invalid @enderror" name="address_sup" id="address_sup" rows="4" placeholder="{{ __('supplier.enter_the_suppliers_business_address') }}">{{ old('address_sup') }}</textarea>
                                  <small class="text-danger">@error('address_sup') {{ $message }} @enderror</small>
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
