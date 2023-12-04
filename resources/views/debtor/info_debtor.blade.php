@extends('base')
@section('title', __('debtor.debtor_details'))
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
                        <h3>{{ __('debtor.debtor_details') }}</h3>
                        <p class="text-subtitle text-muted"></p> 
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_debtor', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.debtors') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('debtor.debtor_details') }}</li>
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
                                {{ __('debtor.debtor_type') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ __('client.' . $debtor->type_deb ) }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.reference') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->reference_deb }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('debtor.debtor_company_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.company_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->entreprise_name_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                RCCM
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->rccm_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                ID NAT
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->id_nat_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                NIF
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->nif_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('entreprise.account_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->account_num_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.website') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->website_deb }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('debtor.debtor_contact_details') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.full_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->contact_name_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.grade') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->fonction_contact_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.email_address') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->email_adress_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.phone_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->phone_number_deb }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.address') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->address_deb }}
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
                                {{ $debtor->name }} {{-- le nom du gestionnaire à cause de la jointure --}}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.creation_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->created_at }} 
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.last_modification_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $debtor->updated_at }} 
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success" role="button" href="{{ route('app_update_debtor', [
                                        'id' => $entreprise->id, 
                                        'id2' => $functionalUnit->id,
                                        'id3' => $debtor->id
                                        ]) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $debtor->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_debtor') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
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

@endsection