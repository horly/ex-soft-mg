@extends('base')
@section('title', __('service.add_a_new_category'))
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
                        <h3>{{ __('service.add_a_new_category') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_category_service', ['group' => 'service', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('service.service_category') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('service.add_a_new_category') }}</li>
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
                        <form action="{{ route('app_create_category_service') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_cat_serv" value="0">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="name_cat" class="col-sm-4 col-form-label">{{ __('service.category_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('name_cat') is-invalid @enderror" id="name_cat" name="name_cat" placeholder="{{ __('service.enter_the_category_name') }}" value="{{ old('name_cat') }}">
                                    <small class="text-danger">@error('name_cat') {{ $message }} @enderror</small>
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
