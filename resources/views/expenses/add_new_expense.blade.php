@extends('base')
@section('title', __('expenses.record_an_expense'))
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
                        <h3>{{ __('expenses.record_an_expense') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_expenses', ['group' => 'expense', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.expenses') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('expenses.record_an_expense') }}</li>
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

                        <form method="POST" id="save-expense-form" action="{{ route('app_save_expense') }}">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="reference_exp" value="{{ $expense ? $expense->reference_exp : $ref_expense }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="{{ $expense ? "edit" : "add" }}">

                            <div class="mb-4 row">
                                <label class="col-sm-4 col-form-label">{{ __('client.reference') }}</label>
                                <label class="col-sm-8 col-form-label">{{ $ref_expense }}</label>
                            </div>

                            <div class="mb-4 row">
                                <label for="description_exp" class="col-sm-4 col-form-label">{{ __('article.description') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="description_exp" name="description_exp" placeholder="{{ __('expenses.enter_the_expense_description') }}" value="{{ $expense ? $expense->description : "" }}">
                                    <small class="text-danger d-none" id="description_exp-error">{{ __('expenses.enter_the_expense_description_please') }}</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="currency_exp" class="col-sm-4 col-form-label">{{ __('dashboard.currency') }}*</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="currency_exp" name="currency_exp" aria-label="Default select example" onchange="changeCurexpLabel()">

                                        @foreach ($deviseGestUfs as $deviseGestUf)
                                            <option value="{{ $deviseGestUf->id }}" iso_code="{{ $deviseGestUf->iso_code }}">{{ $deviseGestUf->iso_code }}</option>
                                        @endforeach

                                    </select>
                                    <small class="text-danger"></small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="amount_expense" class="col-sm-4 col-form-label">{{ __('dashboard.amount') }}*</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.00" class="form-control text-end" name="amount_expense" id="amount_expense" placeholder="0.00" value="{{ $expense ? $expense->amount : "" }}">
                                        <span class="input-group-text" id="current-exp-selected">
                                            @if ($decaissement)
                                                {{ $paymentMeth->iso_code }}
                                            @else
                                                {{ $deviseGest->iso_code }}
                                            @endif
                                        </span>
                                    </div>
                                    <small class="text-danger d-none" id="amount_expense-error">{{ __('expenses.please_enter_the_expense_amount') }}</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="pay_method_exp" class="col-sm-4 col-form-label">{{ __('dashboard.payment_methods') }}*</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="pay_method_exp" name="pay_method_exp">

                                        @if ($decaissement)
                                            <option value="{{ $paymentMeth->id }}" iso_code="{{ $paymentMeth->iso_code }}" selected>
                                                @if ($paymentMeth->default == 1)
                                                    {{ __('payment_methods.' . $paymentMeth->designation) }} ({{ $paymentMeth->iso_code }})
                                                @else
                                                    {{ $paymentMeth->designation }} ({{ $paymentMeth->iso_code }})
                                                @endif
                                            </option>
                                        @endif

                                        @foreach ($paymentMethods as $paymentMethod)
                                            <option value="{{ $paymentMethod->id }}" iso_code="{{ $paymentMethod->iso_code }}">
                                                @if ($paymentMethod->default == 1)
                                                    {{ __('payment_methods.' . $paymentMethod->designation) }} ({{ $paymentMethod->iso_code }})
                                                @else
                                                    {{ $paymentMethod->designation }} ({{ $paymentMethod->iso_code }})
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>
                                    <small class="text-danger d-none" id="pay_method_exp-error">{{ __('expenses.the_chosen_currency_must_match_the_currency_of_the_payment_method') }}</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="date_expense" class="col-sm-4 col-form-label">{{ __('invoice.date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="date_expense" name="date_expense"  value="{{ $expense ? date('Y-m-d', strtotime($expense->created_at)) : date('Y-m-d') }}">
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary saveP" id="save-expense-btn" type="button">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                    {{ __('main.save') }}
                                    </button>
                                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ __('auth.loading') }}
                                    </button>
                                </div>
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
