@extends('base')
@section('title', __('main.my_login_history'))
@section('content')

@include('menu.login-nav')


<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_main') }}">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.user_management') }}</li>
        </ol>
    </nav>

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="border">
        
        <div class="p-4">
            <table class="table table-striped table-hover border bootstrap-datatable">
                <thead>
                    <th>N°</th>
                    <th>{{ __('auth.device') }}</th>
                    <th>IP</th>
                    <th>Date</th>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $history->browser }} {{ __('auth.on') }} {{ $history->platform }}</td>
                            <td>{{ $history->ip }}</td>
                            <td>{{ $history->created_at->format("Y-m-d H:i:s") }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="m-5 fixed-bottom">
        @include('menu.footer-global')
      </div>
</div>


@endsection
