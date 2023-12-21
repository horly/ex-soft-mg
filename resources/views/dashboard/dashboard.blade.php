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
                        <select class="form-select" name="" id="">
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
                                    <h6 class="font-extrabold mb-0">0</h6>
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
                                    <h6 class="font-extrabold mb-0">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>{{ __('dashboard.evolution_of_income_and_expenses_over_the_last_12_months')}}</h4>
                </div>
                <div class="card-body">
                    <div id="chart-evolution-income"></div>
                </div>
            </div>

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
                                    <th class="text-end">{{ __('dashboard.number') }}</th>
                                    <th class="text-end">{{ __('dashboard.amount') }}</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('dashboard.my_customers_owe_me') }}</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('dashboard.i_owe_my_suppliers') }}</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('dashboard.customers_to_follow_up') }}</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('dashboard.supplier_invoices_due') }}</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
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