@extends('base')
@section('title', $entreprise->name)
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_main') }}">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $entreprise->name }}</li>
        </ol>
    </nav>

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="accordion mb-4" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
              {{ __('entreprise.company_information') }}
            </button>
          </h2>
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/img/logo/entreprise')}}/{{ $entreprise->url_logo }}.png" class="rounded-circle img-fluid img-thumbnail" alt="...">
                            </div>

                            @if (Auth::user()->role->name == "admin")
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit_logo') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
            
                    <div class="col-md-8">
                        
                        <div class="border bg-body-tertiary p-4">
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-building"></i>&nbsp;&nbsp;&nbsp;{{ __('main.company_name') }}</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->name }}</div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-italic"></i>&nbsp;&nbsp;&nbsp;Slogan</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->slogan }}</div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-file-lines"></i>&nbsp;&nbsp;&nbsp;RCCM</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->rccm }}</div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-earth-africa"></i>&nbsp;&nbsp;&nbsp;ID NAT</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->id_nat }}</div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-sack-dollar"></i>&nbsp;&nbsp;&nbsp;NIF</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->nif }}</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;{{ __('main.address') }}</div>
                                <div class="col-md-6 text-primary fw-bold">{{ $entreprise->address }}</div>
                            </div>
            
                            @php
                                $phones = DB::table('business_contacts')->where('id_entreprise', $entreprise->id)->get();
                                $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
                                $emailBs = DB::table('business_emails')->where('id_entreprise', $entreprise->id)->get();
                                $bankAc = DB::table('bank_accounts')->where('id_entreprise', $entreprise->id)->get();
                            @endphp
                            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-building-columns"></i>&nbsp;&nbsp;&nbsp;{{ __('main.bank_account') }}</div>
                                <div class="col-md-6 text-primary fw-bold">
                                    @foreach ($bankAc as $bank)
                                        <span>{{ $bank->account_title }} </span> - 
                                    @endforeach
                                </div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-phone"></i>&nbsp;&nbsp;&nbsp;{{ __('main.phone_number') }}</div>
                                <div class="col-md-6 text-primary fw-bold">
                                    {{ "(+" . $country->telephone_code . ")" }}
                                    @foreach ($phones as $phone)
                                        <span>{{ chunk_split($phone->phone_number, 3, ' ') }} </span> - 
                                    @endforeach
                                </div>
                            </div>
            
                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ __('main.email_address') }}</div>
                                <div class="col-md-6 text-primary fw-bold">
                                    @foreach ($emailBs as $email)
                                        <span>{{ $email->email }} </span> - 
                                    @endforeach
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6"><i class="fa-solid fa-globe"></i>&nbsp;&nbsp;&nbsp;{{ __('main.website') }}</div>
                                <a href="https://{{ $entreprise->website }}" target="_blank" class="col-md-6 text-primary fw-bold">https://{{ $entreprise->website }}</a>
                            </div>

                            @if (Auth::user()->role->name == "admin")
                                <div class="d-grid gap-2">
                                    <a class="btn btn-primary" role="button" href="{{ route('app_update_entreprise', ['id' => $entreprise->id]) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </a>
                                </div>
                            @endif
            
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
    
    <div class="border">
        <div class="border-bottom p-4 bg-primary-subtle text-primary-emphasis">
            {{ __('entreprise.functional_unit') }}
        </div>

        @if(Auth::user()->role->name == "admin")
            <div class="border-bottom p-4">
                <a href="{{ route('app_create_functional_unit', ['id' => $entreprise->id]) }}" class="btn btn-primary" role="button"><i class="fa-solid fa-building-circle-arrow-right"></i> 
                    &nbsp;{{ __('entreprise.create_a_functional_unit') }}
                </a>
            </div>
        @endif
        
        <div class="p-4">
            <table class="table table-striped table-hover border bootstrap-datatable">
                <thead>
                    <th>N°</th>
                    <th>{{ __('entreprise.unit_name') }}</th>
                    <th>{{ __('main.address') }}</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($functionalUnits as $functionalUnit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $functionalUnit->name }}</td>
                            <td>{{ $functionalUnit->address }}</td>
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