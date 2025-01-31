@extends('base')
@section('title', __('main.user_information'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_user_management') }}">{{ __('main.user_management') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.user_information') }}</li>
        </ol>
    </nav>

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="card">

        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="text-center mb-4">
                            @if (config('app.server') != "lws")
                                <img src="{{ asset('assets/img/profile') }}/{{ $user->photo_profile_url }}.png" class="image rounded-circle img-fluid img-thumbnail" alt="...">
                            @else
                                <img src="{{ asset('ex-soft-mg/public/assets/img/profile') }}/{{ $user->photo_profile_url }}.png" class="image rounded-circle img-fluid img-thumbnail" alt="...">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="border bg-body-tertiary p-4">
                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;&nbsp;{{ __('main.name') }}</div>
                            <div class="col-md-8 text-primary fw-bold">{{ $user->name }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ __('auth.email') }}</div>
                            <div class="col-md-8 text-primary fw-bold">
                                <div>{{ $user->email }}</div>
                            </div>
                        </div>

                        @php
                            $role = DB::table('roles')->where('id', $user->role_id)->first();
                            $grade = DB::table('grades')->where('id', $user->grade_id)->first();
                            $country = DB::table('countries')->where('id', $user->id_country)->first();
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-universal-access"></i>&nbsp;&nbsp;&nbsp;{{ __('auth.role') }}</div>
                            <div class="col-md-8 text-primary fw-bold">{{ __('profile.' . $role->name) }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;&nbsp;{{ __('main.function') }}</div>
                            <div class="col-md-8 text-primary fw-bold">{{ $user->grade }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-earth-africa"></i>&nbsp;&nbsp;&nbsp;{{ __('main.country') }}</div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ Config::get('app.locale') == 'en' ? $country->name_gb : $country->name_fr }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-phone"></i>&nbsp;&nbsp;&nbsp;{{ __('main.phone_number') }}</div>
                            <div class="col-md-8 text-primary fw-bold">+{{ $country->telephone_code }} {{ chunk_split($user->phone_number, 3, ' ') }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-barcode"></i>&nbsp;&nbsp;&nbsp;{{ __('main.registration_number') }}</div>
                            <div class="col-md-8 text-primary fw-bold">{{ $user->matricule }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;{{ __('main.address') }}</div>
                            <div class="col-md-8 text-primary fw-bold">{{ $user->address }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><i class="fa-solid fa-building-circle-check"></i>&nbsp;&nbsp;&nbsp;{{ __('main.company') }}</div>
                            <div class="col-sm-8">
                                @php
                                    $entrepriseMng = DB::table('entreprises')->where('id_user', $user->id)->first();
                                    $entrepriseMngs = DB::table('entreprises')->where('id_user', $user->id)->get();
                                @endphp

                                @if ($entrepriseMng)
                                    <ul>
                                        @foreach ($entrepriseMngs as $entreprisemng)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span><i class="fa-solid fa-building-columns"></i></span>&nbsp;&nbsp;
                                                    <span>{{ $entreprisemng->name }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <ul class="list-group list-group-flush border mb-3">
                                        @php
                                            $entreprises = DB::table('manages')
                                                            ->join('entreprises', 'manages.id_entreprise', '=', 'entreprises.id')
                                                            ->where('manages.id_user', $user->id)
                                                            ->get();
                                        @endphp
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ __('main.companies_managed') }}
                                            </li>
                                        @foreach ($entreprises as $entreprise)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span><i class="fa-solid fa-building-columns"></i></span>&nbsp;&nbsp;
                                                    <span>{{ $entreprise->name }}</span>
                                                </div>
                                                <div>
                                                    <a class="btn btn-success" role="button" href="{{ route('app_assign_functional_unit_to_user', ['id' => $entreprise->id, 'idUser' => $user->id ]) }}">
                                                        <i class="fa-solid fa-gear"></i>
                                                    </a>
                                                    <button class="btn btn-danger" type="button" onclick="deleteElementTwoVal('{{ $entreprise->id }}', '{{ $user->id }}', '{{ route('app_delete_management_entreprise') }}', '{{ csrf_token() }}');">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addEntrepriseToUser">
                                        <i class="fa-solid fa-circle-plus"></i>
                                        {{ __('auth.add') }}
                                    </button>
                                @endif

                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $user->id }}', '{{ route('app_delete_user') }}', '{{ csrf_token() }}');">
                                <i class="fa-solid fa-trash-can"></i>
                                {{ __('entreprise.delete') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="border-bottom mt-4 fw-bold">
            </div>

            <div class="p-4">
                <h5 class="mb-4">{{ __('main.login_history')}}</h5>

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

    </div>


   <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>

{{-- Modal new phone--}}
<div class="modal fade" id="addEntrepriseToUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-number-phone-modal"> {{ __('main.assign_a_company_to_the_user') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary  p-4">
                @php
                    $entreprises = null;

                    if(Auth::user()->role->name  == "superadmin")
                    {
                        $role = DB::table('roles')->where('name', "admin")->first();
                        $userAdmin = DB::table('users')->where(['sub_id' => $user->sub_id, 'role_id' => $role->id ])->first();

                        //dd($userAdmin);

                        $entreprises = DB::table('entreprises')->where('id_user', $userAdmin->id)->get();
                    }
                    else
                    {
                        $entreprises = DB::table('entreprises')->where('id_user', Auth::user()->id)->get();
                    }

                @endphp

                <table class="table table-striped table-hover border bootstrap-datatable-modal">
                    <thead>
                        <th>N°</th>
                        <th>{{ __('main.company_name') }}</th>
                        <th class="text-end">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($entreprises as $entreprise)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ $entreprise->name }}</a></td>
                                <td class="text-end">
                                    @php
                                        $manage = DB::table('manages')->where([
                                            'id_user' => $user->id,
                                            'id_entreprise' => $entreprise->id,
                                        ])->first();
                                    @endphp
                                    @if ($manage)
                                        <button class="btn btn-success" type="button" disabled>
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-primary" onclick="loadingBtnEntreManagement('{{ $entreprise->id }}', '{{ $user->id }}', '{{ csrf_token() }}', '{{ route('app_assign_entreprise_to_user') }}')" id="btn-{{ $entreprise->id }}"  type="button" title="{{ __('auth.add') }}">
                                            <i class="fa-solid fa-circle-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-loading d-none" id="loading-{{ $entreprise->id }}" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
