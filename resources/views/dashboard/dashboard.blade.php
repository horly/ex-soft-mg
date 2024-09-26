@extends('base')
@section('title', __('dashboard.dashboard'))
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
            <h3> {{ __('dashboard.dashboard') }} </h3>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <p>
                            <i class="fa-solid fa-building"></i>&nbsp;&nbsp;&nbsp;{{ __('main.company_name') }}
                        </p>
                        <p class="fw-bold h6">{{ $entreprise->name }}</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <p>
                            <i class="fa-solid fa-building-circle-arrow-right"></i>&nbsp;&nbsp;&nbsp;{{ __('entreprise.functional_unit_name') }}
                        </p>
                        <p class="fw-bold h6">{{ $functionalUnit->name }}</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <p>
                            <i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;&nbsp;{{ __('dashboard.period') }}
                        </p>
                        <p class="fw-bold h6">
                            {{ __('dashboard.from') }} {{ $first_day_this_month }} {{ __('dashboard.to') }} {{ $last_day_this_month }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">
                            <i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;&nbsp;{{ __('dashboard.currency') }} :
                        </label>
                        <select class="form-select" name="devise-global" onchange="changeDeviseDashboard();" id="devise-global" token="{{ csrf_token() }}" url="{{ route('app_change_devise_view_global') }}">
                            @if (Config::get('app.locale') == 'en')
                                <option value="{{ $deviseGest->id }}" selected>{{ $deviseGest->iso_code }} - {{ $deviseGest->motto_en }}</option>
                                @foreach ($deviseGestAll as $devise)
                                    <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                                @endforeach
                            @else
                                <option value="{{ $deviseGest->id }}" selected>{{ $deviseGest->iso_code }} - {{ $deviseGest->motto_en }}</option>
                                @foreach ($deviseGestAll as $devise)
                                    <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                                @endforeach
                            @endif
                            <option value="{{ $deviseGest->id }}"></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="page-content">

            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">{{ __('dashboard.customer') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $totalClient }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="fa-solid fa-box-archive"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">{{ __('dashboard.articles') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $totalArticle }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">{{ __('dashboard.recipes') }}</h6>
                                    <h6 class="font-extrabold mb-0" id="recettes-global">{{ $recettes }} {{ $deviseGest->iso_code }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">{{ __('dashboard.expenses') }}</h6>
                                    <h6 class="font-extrabold mb-0" id="depenses-global">{{ $depenses }} {{ $deviseGest->iso_code }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-3">{{ __('dashboard.evolution_of_income_and_expenses_for_the_year')}} <span id="evolution-year">{{ $year }}</span></h4>

                    <div class="row">
                        <div class="col-md-3">
                            <form class="input-group" action="{{ route('app_set_year_evolution') }}" method="POST">
                                @csrf
                                <span class="input-group-text">{{ __('dashboard.year') }}</span>
                                <input type="number" class="form-control text-end" id="year-evolution" name="year-evolution" aria-label="Recipient's username" aria-describedby="button-addon2" value="{{ $year }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div id="chart-evolution-income" id_fu="{{ $functionalUnit->id }}" id_devise="{{ $deviseGest->id }}" token="{{ csrf_token() }}" url="{{ route('app_income_global') }}" year="{{ $year }}" recipes="{{ __('dashboard.recipes') }}" expenses="{{ __('dashboard.expenses') }}" results="{{ __('dashboard.results') }}" iscode="{{ $deviseGest->iso_code }}"></div>
                </div>
            </div>

            @php
                $devise_gest = DB::table('devises')
                            ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                            ->where([
                                'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                                'devise_gestion_ufs.default_cur_manage' => 1,
                        ])->first();
            @endphp

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h4>{{ __('dashboard.timeline')}}</h4>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <th>{{ __('dashboard.designation') }}</th>
                                    <th class="text-end">{{ __('dashboard.amount') }} {{ $devise_gest->iso_code }}</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('dashboard.my_customers_owe_me') }}</td>
                                        <td class="text-end">{{ number_format($amount_from_client_to_be_paied, 2, '.', ' ') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('dashboard.i_owe_my_suppliers') }}</td>
                                        <td class="text-end">{{ number_format($amount_from_me_to_be_paied, 2, '.', ' ') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="m-5">
            @include('menu.footer-global')
        </div>

    </div>
</div>

@endsection
