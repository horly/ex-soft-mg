@extends('base')
@section('title', __('dashboard.currency_information'))
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
                            <h3>{{ __('dashboard.currency_information') }}</h3>
                            <p class="text-subtitle text-muted"></p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="{{ route('app_currency', ['group' => 'currency', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.currencies') }}</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.currency_information') }}</li>
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
                                <div class="col-md-4"><i class="fa-solid fa-money-bill-trend-up"></i> &nbsp;&nbsp;&nbsp;{{ __('main.name') }}</div>
                                <div class="col-md-8 text-primary fw-bold">
                                    @if (Config::get('app.locale') == 'en')
                                        {{ $devise->iso_code }} - {{ $devise->motto_en }}
                                    @else
                                        {{ $devise->iso_code }} - {{ $devise->motto }}
                                    @endif

                                    @if ($deviseDefault->id == $devise->id)
                                        ({{ __('dashboard.default_currency')}})
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4"><i class="fa-solid fa-money-bill-transfer"></i> &nbsp;&nbsp;&nbsp;{{ __('dashboard.rate') }} {{ $deviseDefault->taux }} {{ $deviseDefault->iso_code }}</div>
                                <div class="col-md-8 text-primary fw-bold">
                                    {{ $devise->taux }} {{ $devise->iso_code }}
                                </div>
                            </div>

                            <div class="row">
                                @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                    <div class="col-md-6 mb-3">
                                        @if ($deviseDefault->id != $devise->id)
                                            <div class="d-grid gap-2">
                                                <a class="btn btn-success" role="button" href="{{ route('app_update_currency', ['group' => 'currency', 'id' => $entreprise->id, 'id2' => $functionalUnit->id, 'id3' => $devise->id]) }}">
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
                                        @if ($deviseDefault->id != $devise->id)
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $devise->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_currency') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
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
