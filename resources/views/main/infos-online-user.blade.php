@extends('base')
@section('title', __('main.online_service_card'))
@section('content')

<div class="vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-7 mx-auto">
                <div class="card">
                    <div class="card-header bg-blue-theme" style="height: 30px"></div>
                    <div class="card-body p-5">
                        <a href="https://exadgroup.org" target="_blank">
                            <img class="rounded mx-auto d-block mb-5" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="200">
                        </a>
                        
                        <img src="{{ asset('assets/img/profile/card') }}/{{ $user->matricule }}.png" class="rounded-circle mx-auto d-block mb-4" alt="..." width="250">

                        <div class="mb-5">
                            <h1 class="text-center text-blue-theme" id="name-contact">{{ $user->name }}</h1>
                            <p class="text-center text-muted">{{ $grade->name }}</p>
                        </div>


                        <div class="row align-items-start mt-5">
                            <h6 class="col">Matricule</h6>
                            <h6 class="col text-blue-theme text-end">{{ $user->matricule }}</h6>
                        </div>

                        <div class="row align-items-start">
                            <h6 class="col">Téléphone</h6>
                            <h6 class="col text-blue-theme text-end" id="phone-contact">{{ $user->phone_number }}</h6>
                        </div>

                        <div class="row align-items-start">
                            <h6 class="col">Email</h6>
                            <h6 class="col text-blue-theme text-end" id="email-contact">{{ $user->email }}</h6>
                        </div>

                        <div class="row align-items-start">
                            <h6 class="col">Site web</h6>
                            <a href="https://www.exadgroup.org" target="_blank" class="col text-blue-theme text-end h6">www.exadgroup.org</a>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary" type="button" id="save-btn"><i class="fa-solid fa-address-card"></i> Enregistrer le contact</button>
                        </div>
                    </div> 
                    <div class="card-footer bg-blue-theme" style="height: 30px"></div>   
                </div>
            </div>
        </div>
    </div>
</div>

@endsection