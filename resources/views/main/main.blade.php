@extends('base')
@section('title', __('main.home'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.entreprises') }}</li>
        </ol>
    </nav>

    <div class="border">
        <div class="border-bottom p-4">
            <a href="{{ route('app_create_entreprise') }}" class="btn btn-primary" role="button"><i class="fa-solid fa-briefcase"></i> 
                &nbsp;{{ __('main.create_entreprise') }}
            </a>
        </div>
        <div class="p-4">
            <table class="table table-striped table-hover border bootstrap-datatable">
                <thead>
                    <th>N°</th>
                    <th>Name</th>
                    <th>RCCM</th>
                    <th>ID. NAT</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($entreprises as $entreprise)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="#">{{ $entreprise->name }}</a></td>
                            <td>{{ $entreprise->rccm }}</td>
                            <td>{{ $entreprise->id_nat }}</td>
                            <td><a href="#">{{ __('main.show') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
