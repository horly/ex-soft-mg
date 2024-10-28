@extends('base')
@section('title', __('service.add_a_new_service'))
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
                        <h3>{{ __('service.add_a_new_service') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_service', ['group' => 'service', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.services') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('service.add_a_new_service') }}</li>
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
                        <form action="{{ route('app_create_service') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_serv" value="0">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="description_serv" class="col-sm-4 col-form-label">{{ __('service.description') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('description_serv') is-invalid @enderror" id="description_serv" name="description_serv" placeholder="{{ __('service.enter_the_service_description') }}" value="{{ old('description_serv') }}">
                                    <small class="text-danger">@error('description_serv') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="cat_serv" class="col-sm-4 col-form-label">{{ __('service.service_category') }}*</label>
                                <div class="col-sm-5">
                                  <select name="cat_serv" id="cat_serv" class="form-select type_contact @error('cat_serv') is-invalid @enderror">
                                      <option value="" selected>{{ __('service.select_a_category') }}</option>

                                      @foreach ($category_services as $category_service)
                                        <option value="{{ $category_service->id }}">{{ $category_service->name_cat_serv }}</option>
                                      @endforeach

                                  </select>
                                  <small class="text-danger">@error('cat_serv') {{ $message }} @enderror</small>
                                </div>
                                <div class="col-sm-3 d-grid gap-2">
                                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                        <a href="{{ route('app_add_new_category_service', ['group' => 'service', 'id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary mb-3" role="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            &nbsp;{{ __('auth.add') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="unit_price_serv" class="col-sm-4 col-form-label">{{ __('service.unit_price') }}*</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control text-end @error('unit_price_serv') is-invalid @enderror" id="unit_price_serv" name="unit_price_serv" placeholder="{{ __('service.enter_the_service_unit_price') }}" value="{{ old('unit_price_serv') }}">
                                        <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                                    </div>
                                    <small class="text-danger">@error('unit_price_serv') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
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
