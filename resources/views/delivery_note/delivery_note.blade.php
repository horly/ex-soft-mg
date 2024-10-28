@extends('base')
@section('title', __('dashboard.delivery_note'))
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
                        <h3>{{ __('dashboard.delivery_note') }}</h3>
                        <p class="text-subtitle text-muted">{{ __('invoice.delivery_notes_list') }}</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_dashboard', ['group' => 'global', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ $functionalUnit->name }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.delivery_note') }}</li>
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

                        @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                            <a href="#" onclick="setUpinvoice('{{ $functionalUnit->id }}', '{{  $entreprise->id }}', '{{ csrf_token() }}', '{{ route('app_setup_invoice') }}', '{{ 0 }}', '{{ 0 }}', '{{ 1 }}', '{{ 0 }}')" class="btn btn-primary mb-3" role="button">
                                <i class="fa-solid fa-circle-plus"></i>
                                &nbsp;{{ __('auth.add') }}
                            </a>
                        @endif

                        <table class="table table-striped table-hover border bootstrap-datatable">
                            <thead>
                                <th>N°</th>
                                <th>{{ __('client.reference') }}</th>
                                <th>{{ __('invoice.date') }}</th>
                                <th>{{ __('invoice.customer') }}</th>
                                {{--
                                <th>{{ __('invoice.due_date') }}</th>
                                <th class="text-end">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</th>
                                <th class="text-end">{{ __('invoice.payment_received') }} {{ $deviseGest->iso_code }}</th>
                                --}}
                                <th>{{ __('client.manager') }}</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('app_info_delivery_note', [
                                                'group' => 'sale',
                                                'id' => $entreprise->id,
                                                'id2' => $functionalUnit->id,
                                                'ref_invoice' => $invoice->reference_sales_invoice ]) }}">
                                                {{ $invoice->reference_personalized }}
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
                                        {{--
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
                                        --}}
                                        <td>
                                            @php
                                                $user = DB::table('users')->where('id', $invoice->id_user)->first();
                                            @endphp
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('app_info_delivery_note', [
                                                'group' => 'sale',
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
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>

        </div>
    </div>
</div>

@endsection
