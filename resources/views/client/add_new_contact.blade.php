@extends('base')
@section('title', "Contact")
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
                        <h3>{{ __('client.customer_contact_details') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_info_customer', ['group' => 'customer', 'id' => $entreprise->id, 'id2' => $functionalUnit->id, 'id3' => $client->id]) }}">{{ __('client.customers') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('client.customer_contact_details') }}</li>
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
                        <form action="{{ route('app_add_new_contact_client') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_client" value="{{ $client->id }}">
                            <input type="hidden" name="modalRequest" id="modalRequest" value="{{ $id_contact != 0 ? "edit" : "add" }}"> {{-- Default is add but can be edit also --}}
                            <input type="hidden" name="id_contact" id="id_contact" value="{{ $id_contact }}">

                            <div class="mb-4 row">
                                <label for="full_name_cl" class="col-sm-4 col-form-label">{{ __('client.full_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control full_name_cl @error('full_name_cl') is-invalid @enderror" id="full_name_cl" name="full_name_cl" placeholder="{{ __('client.customers_full_name') }}" value="{{ $contact ? $contact->fullname_cl : old('full_name_cl') }}">
                                    <small class="text-danger" id="full_name_cl-error">@error('full_name_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="grade_cl" class="col-sm-4 col-form-label">{{ __('client.grade') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control grade_cl @error('grade_cl') is-invalid @enderror" id="grade_cl" name="grade_cl" placeholder="{{ __('client.customer_grade') }}" value="{{ $contact ? $contact->fonction_contact_cl : old('grade_cl') }}">
                                    <small class="text-danger" id="grade_cl-error">@error('grade_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="department_cl" class="col-sm-4 col-form-label">{{ __('client.department') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control department_cl @error('department_cl') is-invalid @enderror" id="department_cl" name="department_cl" placeholder="{{ __('client.contact_department') }}" value="{{ $contact ? $contact->departement_cl : old('department_cl') }}">
                                    <small class="text-danger" id="department_cl-error">@error('department_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="email_cl" class="col-sm-4 col-form-label">{{ __('main.email_address') }}</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control email_cl @error('email_cl') is-invalid @enderror" id="email_cl" name="email_cl" placeholder="{{ __('client.enter_the_customers_email_address') }}" value="{{ $contact ? $contact->email_adress_cl : old('email_cl') }}">
                                    <small class="text-danger" id="email_cl-error">@error('email_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="phone_number_cl" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control phone_number_cl @error('phone_number_cl') is-invalid @enderror" id="email_cl" id="phone_number_cl" name="phone_number_cl" placeholder="{{ __('client.enter_the_customers_phone_number') }}" value="{{ $contact ? $contact->phone_number_cl : old('phone_number_cl') }}">
                                    <small class="text-danger" id="phone_number_cl-error">@error('phone_number_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="address_cl" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                                <div class="col-sm-8">
                                <textarea class="form-control address_cl @error('address_cl') is-invalid @enderror" name="address_cl" id="address_cl" rows="4" placeholder="{{ __('client.enter_the_customers_business_address') }}">{{ $contact ? $contact->address_cl : old('address_cl') }}</textarea>
                                <small class="text-danger" id="address_cl-error">@error('address_cl') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
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
