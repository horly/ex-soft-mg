@extends('base')
@section('title', __('expenses.add_new_purchase'))
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
                        <h3>{{ __('expenses.add_new_purchase') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_purchases', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.purchases') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('expenses.add_new_purchase') }}</li>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-4 border rounded">
                                    <div class="border-bottom mb-4 fw-bold">
                                        {{ __('expenses.preview') }}
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded">

                                    <form class="mb-3 row" id="purchase_upload_pdf_form" method="POST" action="{{ route('app_upload_purchase_pdf') }}" token="{{ csrf_token() }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="ref_purchase" value="{{ $ref_purchase }}">
                                        <input type="hidden" name="file_purchase-message" id="file_purchase-message" value="{{ __('expenses.please_select_a_file') }}">
                                        <input type="hidden" name="file_purchase-size" id="file_purchase-size" value="{{ __('expenses.file_must_not_exceed') }}">

                                        <label for="file_purchase" class="col-sm-3 col-form-label">{{ __('expenses.add_a_file') }}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control" type="file" id="file_purchase" name="file_purchase" accept=".pdf">
                                                <button class="btn btn-primary" type="submit" id="button-addon2">
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                    &nbsp;{{ __('main.save') }}
                                                </button>
                                            </div>
                                            <small class="text-danger" id="file_purchase-error"></small>

                                            <div class="progress mt-3" id="zone-progress-bar-purchase" hidden>
                                                <div class="progress-bar bg-success" role="progressbar" id="progress-bar-purchase" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="border-bottom mb-4 fw-bold">
                                        {{ __('expenses.preview') }}
                                    </div>
                                    <object data="{{ asset('assets/img/purchase') }}/{{ $ref_purchase }}.pdf" type="application/pdf" width="100%" height="500px">
                                        <div class="alert alert-warning text-center" role="alert">
                                            <i class="fa-regular fa-file"></i> {{ __('expenses.no_preview_available') }}
                                        </div>
                                    </object> 
                                </div> 
                            </div>
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