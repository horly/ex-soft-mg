@extends('base')
@section('title', __('invoice.seal'))
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
                    <h3>{{ __('invoice.invoice_settings') }}</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{ route('app_sales_invoice', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.sales_invoice') }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">{{ __('invoice.invoice_settings') }}</li>
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

                    @include('invoice_sales.signature-navigation')

                    <div class="tab-content" id="myTabContent">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>{{ __('invoice.seal') }}</span>
                                        <span>
                                            <button class="btn btn-primary" data-bs-toggle="modal" onclick="add_sign_seal('{{ __('invoice.seal') }}')" data-bs-target="#edit-photo">
                                                <i class="fa-solid fa-circle-plus"></i>
                                                &nbsp;{{ __('auth.add') }}
                                            </button>
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <div class="border-bottom mb-4 fw-bold">
                                            {{ __('expenses.preview') }}
                                        </div>

                                        <img src="{{ asset('assets/img/invoice/seal/SEAL_') }}{{ Auth::user()->seal_id }}.jpg" width="350" alt="" class="border img-fluid">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-4">

                                </div>
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

{{-- modal modifier la photo de profile --}}
@include('global.edit-photo-modal')


@endsection
