@extends('base')
@section('title', __('payment_methods.payment_method_details'))
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
                        <h3>{{ __('payment_methods.payment_method_details') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_payment_methods', ['group' => 'payment_method', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.payment_methods') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('payment_methods.payment_method_details') }}</li>
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
                                {{ __('dashboard.designation') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                @if ($paymentMethod->default == 1)
                                    {{ __('payment_methods.' . $paymentMethod->designation) }}
                                @else
                                    {{ ($paymentMethod->designation) }}
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('payment_methods.collections') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ number_format($paymentReceived, 2, '.', ' ') }} {{ $paymentMethod->iso_code }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('payment_methods.disbursements') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ number_format($paymentMade, 2, '.', ' ') }} {{ $paymentMethod->iso_code }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('payment_methods.balance') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ number_format($paymentReceived - $paymentMade, 2, '.', ' ') }} {{ $paymentMethod->iso_code }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('payment_methods.bank_information') }} ({{ __('payment_methods.if_bank') }})
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('payment_methods.institution_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $paymentMethod->institution_name }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                IBAN
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $paymentMethod->iban }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('entreprise.account_number') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $paymentMethod->account_number }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('dashboard.currency') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $paymentMethod->iso_code }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                BIC/Swift code
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $paymentMethod->bic_swiff }}
                            </div>
                        </div>

                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('payment_methods.operation_list') }}
                        </div>

                        <div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#collections"
                                        role="tab" aria-controls="home" aria-selected="true">{{ __('payment_methods.collections') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#disbursements"
                                        role="tab" aria-controls="profile" aria-selected="false">{{ __('payment_methods.disbursements') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content p-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="collections" role="tabpanel"
                                    aria-labelledby="home-tab">

                                    <table class="table table-striped table-hover border bootstrap-datatable">
                                        <thead>
                                            <th>N°</th>
                                            <th>Date</th>
                                            <th>{{ __('client.reference') }}</th>
                                            <th>{{ __('article.description') }}</th>
                                            <th class="text-end">{{ __('dashboard.amount') }} {{ $paymentMethod->iso_code }}</th>
                                            <th>{{ __('client.manager') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($encaissements as $encaissement)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ date('Y-m-d', strtotime($encaissement->created_at)) }}</td>
                                                    <td>{{ $encaissement->reference_enc }}</td>
                                                    <td>{{ __($encaissement->description) }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($encaissement->amount, 2, '.', ' ') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $managerEn = DB::table('users')->where('id', $encaissement->id_user)->first();
                                                        @endphp
                                                        {{ $managerEn->name }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>


                                </div>

                                <div class="tab-pane fade" id="disbursements" role="tabpanel"
                                    aria-labelledby="profile-tab">


                                    <table class="table table-striped table-hover border bootstrap-datatable" style="width: 100%">
                                        <thead>
                                            <th>N°</th>
                                            <th>Date</th>
                                            <th>{{ __('client.reference') }}</th>
                                            <th>{{ __('article.description') }}</th>
                                            <th class="text-end">{{ __('dashboard.amount') }} {{ $paymentMethod->iso_code }}</th>
                                            <th>{{ __('client.manager') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($decaissements as $decaissement)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ date('Y-m-d', strtotime($decaissement->created_at)) }}</td>
                                                    <td>{{ $decaissement->reference_dec }}</td>
                                                    <td>{{ __($decaissement->description) }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($decaissement->amount, 2, '.', ' ') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $managerDec = DB::table('users')->where('id', $decaissement->id_user)->first();
                                                        @endphp
                                                        {{ $managerDec->name }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                <div class="col-md-6 mb-3">
                                    @if ($paymentMethod->default != 1)
                                        <div class="d-grid gap-2">
                                            <a class="btn btn-success" role="button" href="{{ route('app_update_payment_methods', [
                                                'group' => 'payment_method',
                                                'id' => $entreprise->id,
                                                'id2' => $functionalUnit->id,
                                                'id3' => $paymentMethod->id
                                                ]) }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('entreprise.edit') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success" type="button" disabled>
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('entreprise.edit') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    @if ($paymentMethod->default != 1 && !$encaissement_exit)
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $paymentMethod->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_payment_methods') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                                {{ __('entreprise.delete') }}
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-danger" type="button" title="{{ __('entreprise.delete') }}" disabled>
                                                <i class="fa-solid fa-trash-can"></i>
                                                {{ __('entreprise.delete') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
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
