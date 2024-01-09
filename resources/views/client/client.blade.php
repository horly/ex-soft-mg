@extends('base')
@section('title', __('dashboard.customer'))
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
                        <h3>{{ __('client.customers') }}</h3>
                        <p class="text-subtitle text-muted">{{ __('client.customer_list') }}</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_dashboard', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ $functionalUnit->name }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.customer') }}</li>
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
                        <a href="{{ route('app_add_new_client', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary mb-3" role="button">
                            <i class="fa-solid fa-circle-plus"></i> 
                            &nbsp;{{ __('auth.add') }}
                        </a>
                        
                        <table class="table table-striped table-hover border bootstrap-datatable">
                            <thead>
                                <th>N°</th>
                                <th>{{ __('client.reference') }}</th>
                                <th>{{ __('client.customer_type') }}</th>
                                <th>{{ __('main.company_name') }}</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('app_info_customer', [
                                                'id' => $entreprise->id,
                                                'id2' => $functionalUnit->id,
                                                'id3' => $client->id,
                                            ]) }}">
                                                {{ $client->reference_cl }}
                                            </a>
                                        </td>
                                        <td>{{ __('client.' .$client->type) }}</td>
                                        <td>
                                            @if ($client->type == "particular")
                                                @php
                                                    $contact = DB::table('customer_contacts')->where('id_client', $client->id)->first();
                                                @endphp
                                                {{ $contact->fullname_cl }}
                                            @else
                                                {{ $client->entreprise_name_cl }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('app_info_customer', [
                                                'id' => $entreprise->id,
                                                'id2' => $functionalUnit->id,
                                                'id3' => $client->id,
                                            ]) }}">
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