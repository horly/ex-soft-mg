@extends('base')
@section('title', __('dashboard.expenses'))
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
                        <h3>{{ __('dashboard.expenses') }}</h3>
                        <p class="text-subtitle text-muted">{{ __('expenses.expenses_list') }}</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_dashboard', ['group' => 'global', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ $functionalUnit->name }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.expenses') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- On inlut les messages flash--}}
        @include('message.flash-message')

        <section class="section">
            <div class="card">
                <div class="card-body">
                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                        <a href="#" onclick="setExpense('{{ $functionalUnit->id }}', '{{  $entreprise->id }}', '{{ csrf_token() }}', '{{ route('app_setup_expense') }}')" class="btn btn-primary mb-3" role="button">
                            <i class="fa-solid fa-circle-plus"></i>
                            &nbsp;{{ __('auth.add') }}
                        </a>
                    @endif

                    <table class="table table-striped table-hover border bootstrap-datatable">
                        <thead>
                            <th>N°</th>
                            <th>{{ __('invoice.date') }}</th>
                            <th>{{ __('client.reference') }}</th>
                            <th>{{ __('article.description') }}</th>
                            <th class="text-end">{{ __('dashboard.amount') }}</th>
                            <th>{{ __('dashboard.payment_methods') }}</th>
                            <th>{{ __('client.manager') }}</th>
                            <th class="text-center">Action</th>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('Y-m-d', strtotime($expense->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('app_add_new_expense', [
                                            'group' => 'expense',
                                            'id' => $entreprise->id,
                                            'id2' => $functionalUnit->id,
                                            'ref_expense' => $expense->reference_exp ]) }}">
                                            {{ $expense->reference_exp }}
                                        </a>
                                    </td>
                                    <td>{{ $expense->description }}</td>
                                    <td class="text-end">
                                        @php
                                            $paymentMethods = DB::table('devises')
                                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                                            ->join('payment_methods', 'payment_methods.id_currency', '=', 'devise_gestion_ufs.id')
                                            ->join('decaissements', 'decaissements.id_pay_meth', '=', 'payment_methods.id')
                                            ->where([
                                                'payment_methods.id_fu' => $functionalUnit->id,
                                                'decaissements.reference_dec' => $expense->reference_exp,
                                            ])->first();

                                        @endphp
                                        {{ number_format($expense->amount, 2, '.', ' ') }} {{ $paymentMethods->iso_code }}
                                    </td>
                                    <td>
                                        @php
                                            $decaissements = DB::table('decaissements')->where('reference_dec', $expense->reference_exp)->first();
                                            $paymentMethode = DB::table('payment_methods')->where('id', $decaissements->id_pay_meth)->first();
                                        @endphp
                                        {{ $paymentMethode->designation }}
                                    </td>
                                    <td>
                                        @php
                                            $user = DB::table('users')->where('id', $expense->id_user)->first();
                                        @endphp
                                        {{ $user->name }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app_add_new_expense', [
                                            'group' => 'expense',
                                            'id' => $entreprise->id,
                                            'id2' => $functionalUnit->id,
                                            'ref_expense' => $expense->reference_exp ]) }}">
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

@endsection
