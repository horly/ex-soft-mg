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
                              <li class="breadcrumb-item"><a href="{{ route('app_customer', ['group' => 'customer', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('client.customers') }}</a></li>
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
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#proforma_invoice"
                                        role="tab" aria-controls="profile" aria-selected="false">{{ __('dashboard.proforma_invoice') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#sales_invoice"
                                        role="tab" aria-controls="profile" aria-selected="false">{{ __('dashboard.sales_invoice') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content p-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="contact" role="tabpanel"
                                    aria-labelledby="home-tab">

                                    @include('client.commun.contact')

                                </div>

                                <div class="tab-pane fade" id="proforma_invoice" role="tabpanel"
                                    aria-labelledby="profile-tab">

                                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                        <a href="#" onclick="setUpinvoice('{{ $functionalUnit->id }}', '{{  $entreprise->id }}', '{{ csrf_token() }}', '{{ route('app_setup_invoice') }}', '{{ 1 }}', '{{ 1 }}', '{{ 0 }}', '{{ $client->id }}')" class="btn btn-primary mb-3" role="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            &nbsp;{{ __('auth.add') }}
                                        </a>
                                    @endif

                                    <table class="table table-striped table-hover border bootstrap-datatable" style="width: 100%">
                                        <thead>
                                            <th>N°</th>
                                            <th>{{ __('client.reference') }}</th>
                                            <th>{{ __('invoice.date') }}</th>
                                            <th>{{ __('invoice.customer') }}</th>
                                            <th>{{ __('invoice.due_date') }}</th>
                                            <th class="text-end">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</th>
                                            <th>{{ __('client.manager') }}</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices_proforma as $invoice)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ route('app_info_proforma', [
                                                            'group' => "sale",
                                                            'id' => $entreprise->id,
                                                            'id2' => $functionalUnit->id,
                                                            'ref_invoice' => $invoice->reference_sales_invoice ]) }}">
                                                            {{ $invoice->reference_sales_invoice }}
                                                        </a>
                                                    </td>
                                                    <td>{{ date('Y-m-d', strtotime($invoice->created_at)) }}</td>
                                                    <td>
                                                        @if ($invoice->entreprise_name_cl == "-" || $invoice->entreprise_name_cl == "")
                                                            @php
                                                                $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();
                                                            @endphp
                                                            {{ $contact->fullname_cl }}
                                                        @else
                                                            {{ $invoice->entreprise_name_cl }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('Y-m-d', strtotime($invoice->due_date)) }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($invoice->total, 2, '.', ' ') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $user = DB::table('users')->where('id', $invoice->id_user)->first();
                                                        @endphp
                                                        {{ $user->name }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('app_info_proforma', [
                                                            'group' => "sale",
                                                            'id' => $entreprise->id,
                                                            'id2' => $functionalUnit->id,
                                                            'ref_invoice' => $invoice->reference_sales_invoice ]) }}">
                                                            {{ __('main.show') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                                <div class="tab-pane fade" id="sales_invoice" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                        <a href="#" onclick="setUpinvoice('{{ $functionalUnit->id }}', '{{  $entreprise->id }}', '{{ csrf_token() }}', '{{ route('app_setup_invoice') }}', '{{ 0 }}', '{{ 1 }}', '{{ 0 }}', '{{ $client->id }}')" class="btn btn-primary mb-3" role="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            &nbsp;{{ __('auth.add') }}
                                        </a>
                                    @endif

                                    <table class="table table-striped table-hover border bootstrap-datatable" style="width:100%">
                                        <thead>
                                            <th>N°</th>
                                            <th>{{ __('client.reference') }}</th>
                                            <th>{{ __('invoice.date') }}</th>
                                            <th>{{ __('invoice.customer') }}</th>
                                            <th>{{ __('invoice.due_date') }}</th>
                                            <th class="text-end">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</th>
                                            <th class="text-end">{{ __('invoice.payment_received') }} {{ $deviseGest->iso_code }}</th>
                                            <th>{{ __('client.manager') }}</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ route('app_info_sales_invoice', [
                                                            'group' => "sale",
                                                            'id' => $entreprise->id,
                                                            'id2' => $functionalUnit->id,
                                                            'ref_invoice' => $invoice->reference_sales_invoice ]) }}">
                                                            {{ $invoice->reference_sales_invoice }}
                                                        </a>
                                                    </td>
                                                    <td>{{ date('Y-m-d', strtotime($invoice->created_at)) }}</td>
                                                    <td>
                                                        @if ($invoice->entreprise_name_cl == "-" || $invoice->entreprise_name_cl == "")
                                                            @php
                                                                $contact = DB::table('customer_contacts')->where('id', $invoice->id_contact)->first();
                                                            @endphp
                                                            {{ $contact->fullname_cl }}
                                                        @else
                                                            {{ $invoice->entreprise_name_cl }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('Y-m-d', strtotime($invoice->due_date)) }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($invoice->total, 2, '.', ' ') }}
                                                    </td>
                                                    <td class="text-end">
                                                        @php
                                                            $paymentReceived = DB::table('encaissements')
                                                                ->where([
                                                                    'reference_enc' => $invoice->reference_sales_invoice,
                                                                    'id_fu' => $functionalUnit->id,
                                                                ])->sum('amount');
                                                        @endphp
                                                        {{ number_format($paymentReceived, 2, '.', ' ') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $user = DB::table('users')->where('id', $invoice->id_user)->first();
                                                        @endphp
                                                        {{ $user->name }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('app_info_sales_invoice', [
                                                            'group' => "sale",
                                                            'id' => $entreprise->id,
                                                            'id2' => $functionalUnit->id,
                                                            'ref_invoice' => $invoice->reference_sales_invoice ]) }}">
                                                            {{ __('main.show') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                        @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")


                            <a class="btn btn-success" role="button" href="{{ route('app_update_customer', [
                                'group' => 'customer',
                                'id' => $entreprise->id,
                                'id2' => $functionalUnit->id,
                                'id3' => $client->id
                                ]) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('entreprise.edit') }}
                            </a>

                            @php
                                $invoice_exst = DB::table('sales_invoices')->where('id_client', $client->id)->first();
                            @endphp
                            @if ($invoice_exst)
                                <button class="btn btn-danger" type="button" title="{{ __('entreprise.delete') }}" disabled>
                                    <i class="fa-solid fa-trash-can"></i>
                                    {{ __('entreprise.delete') }}
                                </button>
                            @else
                                <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $client->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_client') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                    {{ __('entreprise.delete') }}
                                </button>
                            @endif

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
