@extends('base')
@section('title', __('main.home'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.entreprises') }}</li>
        </ol>
    </nav>

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="card">
        <div class="card-body">
            @if(Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
            <div class="mb-3">
                <a href="{{ route('app_create_entreprise') }}" class="btn btn-primary" role="button"><i class="fa-solid fa-building-circle-check"></i>
                    &nbsp;{{ __('main.create_entreprise') }}
                </a>
            </div>
            <hr class="dropdown-divider">
            @endif

            <div class="p-4">
                <table class="table table-striped table-hover border bootstrap-datatable">
                    <thead>
                        <th>N°</th>
                        <th>{{ __('main.company_name') }}</th>
                        <th>RCCM</th>
                        <th>ID. NAT</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($entreprises as $entreprise)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ $entreprise->name }}</a></td>
                                <td>{{ $entreprise->rccm }}</td>
                                <td>{{ $entreprise->id_nat }}</td>
                                <td><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ __('main.show') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>

@endsection
