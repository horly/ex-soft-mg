@extends('base')
@section('title', __('main.user_information'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_user_management') }}">{{ __('main.user_management') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.user_information') }}</li>
        </ol>
    </nav>

    <div class="border">

        <div class="p-4" id="myTabContent">
              
            <div class="row">
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/img/profile') }}/{{ $user->photo_profile_url }}.png" class="image rounded-circle img-fluid img-thumbnail" alt="...">
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
                            <div class="col-md-8 text-primary fw-bold">{{ $grade->name }}</div>
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
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $user->id }}', '{{ route('app_delete_user') }}', '{{ csrf_token() }}');">
                                <i class="fa-solid fa-trash-can"></i>
                                {{ __('entreprise.delete') }}
                            </button>
                        </div>
            
                    </div>
                </div>
            </div>
               
       </div>
    
    </div>


   <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>

@endsection