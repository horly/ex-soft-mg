@extends('base')
@section('title', __('supplier.supplier_details'))
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
                        <h3>{{ __('supplier.supplier_details') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_supplier', ['group' => 'supplier', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('supplier.suppliers') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('supplier.supplier_details') }}</li>
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
                                {{ __('supplier.supplier_type') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ __('client.' . $supplier->type_sup ) }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.reference') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->reference_sup }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('supplier.supplier_company_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.company_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->entreprise_name_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                RCCM
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->rccm_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                ID NAT
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->id_nat_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                NIF
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->nif_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('entreprise.account_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->account_num_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.website') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->website_sup }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('supplier.supplier_contact_details') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.full_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->contact_name_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.grade') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->fonction_contact_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.email_address') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->email_adress_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.phone_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->phone_number_sup }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.address') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->address_sup }}
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
                                {{ $supplier->name }} {{-- le nom du gestionnaire à cause de la jointure --}}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.creation_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->created_at }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.last_modification_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $supplier->updated_at }}
                            </div>
                        </div>

                        @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <div class="d-grid gap-2">
                                        <a class="btn btn-success" role="button" href="{{ route('app_update_supplier', [
                                            'group' => 'supplier',
                                            'id' => $entreprise->id,
                                            'id2' => $functionalUnit->id,
                                            'id3' => $supplier->id
                                            ]) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            {{ __('entreprise.edit') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $supplier->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_supplier') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                            {{ __('entreprise.delete') }}
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endif

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
