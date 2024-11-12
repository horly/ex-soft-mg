@extends('base')
@section('title', __('super_admin.subscriptions'))
@section('content')


<div id="app">

    {{-- Navigation menu here --}}

    @include('super_admin.navigation-menu-super-admin')

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
                        <h3> {{ __('super_admin.subscriptions') }} </h3>
                        <p class="text-subtitle text-muted">{{ __('super_admin.subscription_list') }}</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_super_admin_dashboard') }}">{{ __('super_admin.super_admin_dashboard') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('super_admin.subscriptions') }}</li>
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
                        <a href="{{ route('app_add_subscription', ['id' => 0]) }}" class="btn btn-primary mb-3" role="button">
                            <i class="fa-solid fa-circle-plus"></i>
                            &nbsp;{{ __('auth.add') }}
                        </a>

                        <table class="table table-striped table-hover border bootstrap-datatable">
                            <thead>
                                <th>N°</th>
                                <th>{{ __('client.reference') }}</th>
                                <th>Type</th>
                                <th>{{ __('article.description') }}</th>
                                <th>{{ __('super_admin.start_date') }}</th>
                                <th>{{ __('super_admin.end_date') }}</th>
                                <th>{{ __('super_admin.state') }}</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('app_add_subscription', [
                                            'id' => $subscription->id
                                            ]) }}">
                                                {{ $subscription->reference }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $type = null;

                                                if($subscription->type == 1)
                                                {
                                                    $type = "Business";
                                                }
                                                else if($subscription->type == 2)
                                                {
                                                    $type = "Prenium";
                                                }
                                                else
                                                {
                                                    $type = "Startup";
                                                }
                                            @endphp
                                            {{ $type }}
                                        </td>
                                        <td>{{ $subscription->description }}</td>
                                        <td>{{ date('Y-m-d', strtotime($subscription->start_date)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($subscription->end_date)) }}</td>
                                        <td>
                                            @php
                                                /**
                                                 * installation :
                                                 * composer require nesbot/carbon
                                                 * */
                                                //use Carbon\Carbon;
                                                //@inject('carbon', 'Carbon\Carbon');

                                                $subscriptionEndDate = Carbon\Carbon::parse(date('Y-m-d', strtotime($subscription->end_date))); // Example end date
                                                $currentDate = Carbon\Carbon::now();

                                                $state = null;
                                                $text = null;

                                                if($currentDate->lessThanOrEqualTo($subscriptionEndDate)) {
                                                    $state = __('super_admin.active');
                                                    $text = "success";
                                                }
                                                else {
                                                    $state = __('super_admin.expired');
                                                    $text = "danger";
                                                }
                                            @endphp

                                            <span class="badge bg-{{ $text }}">{{ $state }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('app_add_subscription', [
                                                'id' => $subscription->id
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
