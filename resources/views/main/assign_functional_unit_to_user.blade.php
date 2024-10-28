@extends('base')
@section('title', __('entreprise.assign_a_functional_unit_to_user'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_user_management_info', ['id' => $user->id ]) }}">{{ __('main.user_information') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page"> {{ __('main.functional_unit_management') }}</li>
        </ol>
    </nav>


    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="card">
        <div class="card-body">
            @if(Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
            <div class="mb-3">
                <a href="{{ route('app_create_functional_unit', ['id' => $entreprise->id]) }}" class="btn btn-primary" role="button"><i class="fa-solid fa-building-circle-arrow-right"></i>
                    &nbsp;{{ __('entreprise.create_a_functional_unit') }}
                </a>
            </div>
            <hr class="dropdown-divider">
            @endif

            <div class="p-4">
                <table class="table table-striped table-hover border bootstrap-datatable">
                    <thead>
                        <th>N°</th>
                        <th>{{ __('main.company_name') }}</th>
                        <th>{{ __('main.user') }}</th>
                        <th class="text-end">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($functionalUnits as $functionalUnit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('app_modules', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}">{{ $functionalUnit->name }}</a></td>
                                <td>{{ $user->name }}</td>
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
                                        <a role="button" href="{{ route('app_permissions', ['id_user' => $user->id, 'id_fu' => $functionalUnit->id]) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-unlock-keyhole"></i>
                                        </a>
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

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="permissions-modals-fu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('app_save_permissions') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('main.permissions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf

                    <input type="hidden" name="id_fu" id="id_fu">
                    <input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}">

                    <div class="mb-4 row">
                        <div class="col-sm-6">{{ __('main.company') }}</div>
                        <div class="col-md-6 text-primary fw-bold">{{ $entreprise->name }}</div>
                    </div>

                    <div class="mb-4 row">
                        <div class="col-sm-6">{{ __('entreprise.functional_unit') }}</div>
                        <div class="col-md-6 text-primary fw-bold" id="fu-permissions"></div>
                    </div>

                    <div class="mb-4 row">
                        <div class="col-sm-6">{{ __('main.user') }}</div>
                        <div class="col-md-6 text-primary fw-bold">{{ $user->name }}</div>
                    </div>

                    <div class="border-bottom mt-4 mb-4 fw-bold">
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="full_dashboard_view" name="full_dashboard_view">
                        <label class="form-check-label" for="full_dashboard_view">{{ __('main.full_dashboard_view') }}</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="edit_delete_contents" name="edit_delete_contents">
                        <label class="form-check-label" for="edit_delete_contents">{{ __('main.edit_delete_contents') }}</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="billing" name="billing">
                        <label class="form-check-label" for="billing">{{ __('main.billing') }}</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="report_generation" name="report_generation">
                        <label class="form-check-label" for="report_generation">{{ __('main.report_generation') }}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- button de fermeture modale --}}
                    @include('button.close-button')

                    @include('button.save-button')
                </div>
            </form>
        </div>
    </div>

    <div class="m-5">
        @include('menu.footer-global')
    </div>

</div>


@endsection
