@extends('base')
@section('title', __('entreprise.assign_a_functional_unit_to_user'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_user_management_info', ['id' => $user->id ]) }}">{{ __('main.user_information') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page"> {{ __('entreprise.assign_user_to_entreprise_fu', ['name' => $user->name, 'entreprise' => $entreprise->name]) }}</li>
        </ol>
    </nav>


    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="p-4 border">
        @if(Auth::user()->role->name == "admin")
            <div class="border-bottom p-3 mb-3">
                <a href="{{ route('app_create_functional_unit', ['id' => $entreprise->id]) }}" class="btn btn-primary" role="button"><i class="fa-solid fa-building-circle-arrow-right"></i> 
                    &nbsp;{{ __('entreprise.create_a_functional_unit') }}
                </a>
            </div>
        @endif

        <table class="table table-striped table-hover border bootstrap-datatable">
            <thead>
                <th>N°</th>
                <th>{{ __('main.company_name') }}</th>
                <th class="text-end">Action</th>
            </thead>
            <tbody>
                @foreach ($functionalUnits as $functionalUnit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('app_modules', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}">{{ $functionalUnit->name }}</a></td>
                        <td class="text-end">
                            @php
                                $manage = DB::table('manage_f_u_s')->where([
                                    'id_user' => $user->id,
                                    'id_entreprise' => $entreprise->id,
                                    'id_fu' => $functionalUnit->id,
                                ])->first();
                            @endphp
                            @if ($manage)
                                <button class="btn btn-success" type="button" disabled>
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                                <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $entreprise->id }}', '{{ $user->id }}', '{{ $functionalUnit->id }}', '{{ route('app_delete_management_fu') }}', '{{ csrf_token() }}');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @else
                                <button class="btn btn-primary" onclick="loadingBtnEntreManagementUF('{{ $functionalUnit->id }}', '{{ $entreprise->id }}', '{{ $user->id }}', '{{ csrf_token() }}', '{{ route('app_assign_fu_to_user') }}')" id="btn-{{ $functionalUnit->id }}"  type="button" title="{{ __('auth.add') }}">
                                    <i class="fa-solid fa-circle-plus"></i>
                                </button>
                                <button class="btn btn-primary btn-loading d-none" id="loading-{{ $functionalUnit->id }}" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class="m-5">
        @include('menu.footer-global')
    </div>

</div>

@endsection