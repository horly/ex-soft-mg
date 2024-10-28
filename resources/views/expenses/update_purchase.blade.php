@extends('base')
@section('title', __('expenses.purchase_details'))
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

        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ __('expenses.purchase_details') }}</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{ route('app_purchases', ['group' => 'expense', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.purchases') }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">{{ __('expenses.purchase_details') }}</li>
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

                    {{-- le contenu de l'achat --}}
                    @include('expenses.purchase_detail_content')

                    <div class="p-4">
                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('invoice.payment_details') }}
                        </div>

                        <table class="table table-striped border mb-4">
                            <thead>
                                <th>Date</th>
                                <th>{{ __('payment_methods.disbursements') }}</th>
                                <th>{{ __('dashboard.payment_methods') }}</th>
                                <th>{{ __('client.manager') }}</th>
                                <th class="text-end">{{ __('dashboard.amount') }} {{ $deviseGest->iso_code }}</th>
                            </thead>
                            <tbody>

                                @foreach ($decaissements as $decaissement)
                                    <tr>
                                        <td>{{ date('Y-m-d', strtotime($decaissement->created_at)) }}</td>
                                        <td>{{ __($decaissement->description) }}</td>
                                        <td>
                                            @if ($decaissement->default == 1)
                                                {{ __('payment_methods.' . $decaissement->designation) }} ({{ $decaissement->iso_code }})
                                            @else
                                                {{ $decaissement->designation }} ({{ $decaissement->iso_code }})
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $user = DB::table('users')->where('id', $decaissement->id_user)->first();
                                            @endphp
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-end">{{ number_format($decaissement->amount, 2, '.', ' ') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="fw-bold">{{ __('expenses.amount_to_be_paid') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="fw-bold text-end">{{ number_format($purchases->amount, 2, '.', ' ') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('expenses.payment_made') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="fw-bold text-end">{{ number_format($paymentReceived, 2, '.', ' ') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('invoice.remaining_balance') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="fw-bold text-end">{{ number_format($remainingBalance, 2, '.', ' ') }}</td>
                                </tr>
                            </tbody>

                        </table>


                    </div>

                </div>
            </div>
        </section>



        <div class="m-5">
            @include('menu.footer-global')
        </div>

    </div>

</div>


@endsection
