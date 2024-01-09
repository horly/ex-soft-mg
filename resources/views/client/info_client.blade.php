@extends('base')
@section('title', __('client.customer_details'))
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
                        <h3>{{ __('client.customer_details') }}</h3>
                        <p class="text-subtitle text-muted"></p> 
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_customer', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('client.customers') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('client.customer_details') }}</li>
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

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.customer_type') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ __('client.' .$client->type ) }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.reference') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->reference_cl }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('client.customer_company_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.company_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->entreprise_name_cl }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                RCCM
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->rccm_cl }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                ID NAT
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->id_nat_cl }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                NIF
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->nif_cl }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('entreprise.account_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->account_num_cl }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.website') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->website_cl }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('client.manager_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->name }} {{-- le nom du gestionnaire à cause de la jointure --}}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.creation_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->created_at }} 
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.last_modification_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $client->updated_at }} 
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                        </div>

                        <div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#contact"
                                        role="tab" aria-controls="home" aria-selected="true">Contacts</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile"
                                        role="tab" aria-controls="profile" aria-selected="false">{{ __('dashboard.sales_invoice') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content p-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="contact" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    
                                    <button class="btn btn-primary mb-3" type="button" onclick="addNewcontact();" data-bs-toggle="modal" data-bs-target="#add_contact">
                                        <i class="fa-solid fa-circle-plus"></i> 
                                        &nbsp;{{ __('auth.add') }}
                                    </button>

                                    <table class="table table-striped table-hover border bootstrap-datatable">
                                        <thead>
                                            <th>N°</th>
                                            <th>{{ __('client.contact_name') }}</th>
                                            <th>{{ __('client.grade') }}</th>
                                            <th>{{ __('client.department') }}</th>
                                            <th>{{ __('main.phone_number') }}</th>
                                            <th>{{ __('main.email_address') }}</th>
                                            <th>{{ __('main.address') }}</th>
                                            <th class="text-end">Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($contacts as $contact)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $contact->fullname_cl }}</td>
                                                    <td>{{ $contact->fonction_contact_cl }}</td>
                                                    <td>{{ $contact->departement_cl }}</td>
                                                    <td>{{ $contact->phone_number_cl }}</td>
                                                    <td>{{ $contact->email_adress_cl }}</td>
                                                    <td>{{ $contact->address_cl }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-success" type="button" onclick="editContactClient('{{ $contact->id }}', '{{ $contact->fullname_cl }}', '{{ $contact->fonction_contact_cl }}', '{{ $contact->departement_cl }}', '{{ $contact->phone_number_cl }}', '{{ $contact->email_adress_cl }}', '{{ $contact->address_cl }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#add_contact">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    Integer interdum diam eleifend metus lacinia, quis gravida eros mollis.
                                    Fusce non sapien
                                    sit amet magna dapibus
                                    ultrices. Morbi tincidunt magna ex, eget faucibus sapien bibendum non. Duis
                                    a mauris ex.
                                    Ut finibus risus sed massa
                                    mattis porta. Aliquam sagittis massa et purus efficitur ultricies. Integer
                                    pretium dolor
                                    at sapien laoreet ultricies.
                                    Fusce congue et lorem id convallis. Nulla volutpat tellus nec molestie
                                    finibus. In nec
                                    odio tincidunt eros finibus
                                    ullamcorper. Ut sodales, dui nec posuere finibus, nisl sem aliquam metus, eu
                                    accumsan
                                    lacus felis at odio. Sed lacus
                                    quam, convallis quis condimentum ut, accumsan congue massa. Pellentesque et
                                    quam vel
                                    massa pretium ullamcorper vitae eu
                                    tortor.
                                </div>
                            </div>
                        </div>

                        

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success" role="button" href="{{ route('app_update_customer', [
                                        'id' => $entreprise->id, 
                                        'id2' => $functionalUnit->id,
                                        'id3' => $client->id
                                        ]) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $client->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_client') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                        {{ __('entreprise.delete') }}
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_contact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="modal-new-contact-form" method="POST" action="{{ route('app_add_new_contact_client') }}">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-contact-title"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @csrf

                {{-- Translation --}}
                <input type="hidden" name="add_a_new_contact_title" id="add_a_new_contact_title" value="{{ __('invoice.add_a_new_contact') }}">
                <input type="hidden" name="edit_contact" id="edit_contact" value="{{ __('invoice.edit_contact') }}">
                <input type="hidden" name="full_name_cl-error-message" id="full_name_cl-error-message" value="{{ __('client.enter_customers_full_name_please') }}">
                <input type="hidden" name="grade_cl-error-message" id="grade_cl-error-message" value="{{ __('client.enter_customer_grade_please') }}">
                <input type="hidden" name="department_cl-error-message" id="department_cl-error-message" value="{{ __('client.please_enter_the_contact_department') }}">
                <input type="hidden" name="email_cl-error-message" id="email_cl-error-message" value="{{ __('client.enter_the_customers_email_address_please') }}">
                <input type="hidden" name="phone_number_cl-error-message" id="phone_number_cl-error-message" value="{{ __('client.enter_the_customers_phone_number_please') }}">
                <input type="hidden" name="address_cl-error-message" id="address_cl-error-message" value="{{ __('client.enter_the_customers_business_address_please') }}">

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="id_client" value="{{ $client->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="id_contact" id="id_contact" value="0"> 

                <div class="mb-4 row">
                    <label for="full_name_cl" class="col-sm-4 col-form-label">{{ __('client.full_name') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="full_name_cl" name="full_name_cl" placeholder="{{ __('client.customers_full_name') }}" value="{{ old('full_name_cl') }}">
                        <small class="text-danger" id="full_name_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="grade_cl" class="col-sm-4 col-form-label">{{ __('client.grade') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="grade_cl" name="grade_cl" placeholder="{{ __('client.customer_grade') }}" value="{{ old('grade_cl') }}">
                        <small class="text-danger" id="grade_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="department_cl" class="col-sm-4 col-form-label">{{ __('client.department') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="department_cl" name="department_cl" placeholder="{{ __('client.contact_department') }}" value="{{ old('department_cl') }}">
                        <small class="text-danger" id="department_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="email_cl" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email_cl" name="email_cl" placeholder="{{ __('client.enter_the_customers_email_address') }}" value="{{ old('email_cl') }}">
                        <small class="text-danger" id="email_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="phone_number_cl" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="phone_number_cl" name="phone_number_cl" placeholder="{{ __('client.enter_the_customers_phone_number') }}" value="{{ old('phone_number_cl') }}">
                        <small class="text-danger" id="phone_number_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="address_cl" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" name="address_cl" id="address_cl" rows="4" placeholder="{{ __('client.enter_the_customers_business_address') }}">{{ old('address_cl') }}</textarea>
                      <small class="text-danger" id="address_cl-error"></small>
                    </div>
                </div>
            
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="save_contact_client">
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

@endsection