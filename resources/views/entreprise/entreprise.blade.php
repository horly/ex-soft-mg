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

    <div class="border">
        <div class="border-bottom p-4">
            <ul class="nav nav-pills" id="entrepriseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" aria-current="page" href="#" id="functional-unit-tab" data-bs-toggle="tab" data-bs-target="#functional-unit-tab-pane" role="tab" aria-controls="functional-unit-tab-pane" aria-selected="true">{{ __('entreprise.functional_unit')}}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#" id="entreprise-info-tab" data-bs-toggle="tab" data-bs-target="#entreprise-info-tab-pane" role="tab" aria-controls="entreprise-info-tab-pane" aria-selected="false">{{ __('entreprise.company_information')}}</a>
                </li>
            </ul>
        </div>

        <div class="tab-content p-4" id="myTabContent">
            <div class="tab-pane fade show active" id="functional-unit-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
               
                @include('entreprise.functional-unite')

            </div>
            <div class="tab-pane fade" id="entreprise-info-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                
                @include('entreprise.entreprise-info')
                
            </div>
        </div>
    </div>    

    <div class="m-5">
        @include('menu.footer-global')
      </div>
</div>

@endsection