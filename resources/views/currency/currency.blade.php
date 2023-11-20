@extends('base')
@section('title', __('dashboard.currencies'))
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
                        <h3>{{ __('dashboard.currencies') }}</h3>
                        <p class="text-subtitle text-muted">{{ __('dashboard.currencies_list') }}</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_dashboard', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.dashboard') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.currencies') }}</li>
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
                        <a href="{{ route('app_create_currency', ['id' => $entreprise->id, $functionalUnit->id]) }}" class="btn btn-primary mb-3" role="button">
                            <i class="fa-solid fa-money-bill-trend-up"></i> 
                            &nbsp;{{ __('dashboard.add_currency') }}
                        </a>

                        <form class="row mb-3" action="#" method="POST">
                            <label for="main_currency" class="col-sm-3 col-form-label">{{ __('dashboard.main_currency') }}*</label> 
                            <div class="col-sm-6 mb-3">
                                <select name="main_currency" id="main_currency" class="form-select @error('main_currency') is-invalid @enderror">
                                    @if (Config::get('app.locale') == 'en')
                                        @foreach ($deviseFU as $devise)
                                            <option value="{{ $deviseDefault->id }}" selected>{{ $deviseDefault->iso_code }} - {{ $deviseDefault->motto_en }}</option>
                                            @if ($devise->id != $deviseDefault->id)
                                                <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($deviseFU as $devise)
                                            <option value="{{ $deviseDefault->id }}" selected>{{ $deviseDefault->iso_code }} - {{ $deviseDefault->motto }}</option>
                                            @if ($devise->id != $deviseDefault->id)
                                                <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <small class="text-danger">@error('main_currency') {{ $message }} @enderror</small>
                            </div>
                            <div class="col-sm-3">
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
                            </div>
                        </form>

                        <table class="table table-striped table-hover border bootstrap-datatable">
                            <thead>
                                <th>N°</th>
                                <th>{{ __('main.name') }}</th>
                                <th>{{ __('rate') }}</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($deviseFU as $devise)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if (Config::get('app.locale') == 'en')
                                                <a href="#">
                                                    {{ $devise->iso_code }} - {{ $devise->motto_en }}
                                                </a>
                                            @else
                                                <a href="#">{{ $devise->iso_code }} - {{ $devise->motto }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $devise->taux }}</td>
                                        <td>
                                            <a href="#">
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