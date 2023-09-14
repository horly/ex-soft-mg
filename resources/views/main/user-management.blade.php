@extends('base')
@section('title', __('main.user_management'))
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
        
        @if(Auth::user()->role->name == "admin")
            <div class="border-bottom p-4">
                <a href="{{ route('app_add_user_page') }}" class="btn btn-primary" role="button"><i class="fa-solid fa-user-plus"></i> 
                    &nbsp;{{ __('main.add_user') }}
                </a>
            </div>
        @endif
        
        <div class="p-4">
            <table class="table table-striped table-hover border bootstrap-datatable">
                <thead>
                    <th>N°</th>
                    <th>{{ __('main.name') }}</th>
                    <th>{{ __('main.registration_number') }}</th>
                    <th>{{ __('auth.email') }}</th>
                    <th>{{ __('main.function') }}</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @php
                            $grade = DB::table('grades')->where('id', $user->grade_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="#">{{ $user->name }}</a></td>
                            <td>{{ $user->matricule }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $grade->name }}</td>
                            <td><a href="#">{{ __('main.show') }}</a></td>
                        </tr>     
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="m-5">
        @include('menu.footer-global')
      </div>
</div>

@endsection