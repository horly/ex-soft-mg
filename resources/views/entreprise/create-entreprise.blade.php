@extends('base')
@section('title', __('main.create_entreprise'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_main') }}">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.create_entreprise') }}</li>
        </ol>
    </nav>

    <form class="border bg-body-tertiary p-4" action="" method="POST">
        <div class="mb-4 row">
            <label for="name-entreprise" class="col-sm-4 col-form-label">{{ __('main.company_name') }}*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="name-entreprise" name="name-entreprise" placeholder="{{ __('main.enter_company_name') }}" >
              <small id="name-entreprise-error" class="text-danger"></small>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="slogan-entreprise" class="col-sm-4 col-form-label">Slogan</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="slogan-entreprise" name="slogan-entreprise" placeholder="{{ __('main.enter_your_company_slogan') }}" >
            </div>
        </div>
        <div class="mb-4 row">
            <label for="rccm-entreprise" class="col-sm-4 col-form-label">RCCM*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="rccm-entreprise" name="rccm-entreprise" placeholder="{{ __('main.enter_your_companys_rccm') }}" >
            </div>
        </div>
        <div class="mb-4 row">
            <label for="idnat-entreprise" class="col-sm-4 col-form-label">ID NAT*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="idnat-entreprise" name="idnat-entreprise" placeholder="{{ __('main.enter_your_companys_national_identification') }}" >
            </div>
        </div>
        <div class="mb-4 row">
            <label for="nif-entreprise" class="col-sm-4 col-form-label">NIF*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="nif-entreprise" name="nif-entreprise" placeholder="{{ __('main.enter_your_companys_tax_id_number') }}" >
            </div>
        </div>
        <div class="mb-4 row">
            <label for="address-entreprise" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="address-entreprise" id="address-entreprise" rows="4" placeholder="{{ __('main.enter_your_company_address') }}"></textarea>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="phone1-entreprise" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="phone1-entreprise" name="phone1-entreprise" placeholder="{{ __('main.enter_your_business_phone_number') }}" >
            </div>
        </div>
        {{--
        <div class="mb-4 row">
            <label class="col-sm-4 col-form-label"></label>
            <div class="col-sm-8">
                <a class="link-underline-light" href="#"><i class="fa-solid fa-circle-plus"></i> {{ __('main.add_another_phone_number') }}</a>
            </div>
        </div>
        --}}
        <div class="mb-4 row">
            <label for="email-entreprise" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="email-entreprise" name="email-entreprise" placeholder="{{ __('main.enter_your_company_email_address') }}" >
            </div>
        </div>
        <div class="mb-4 row">
          <label for="website-entreprise" class="col-sm-4 col-form-label">{{ __('main.website') }}</label>
          <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1">https://</span>
                <input type="text" class="form-control" id="website-entreprise" name="website-entreprise" placeholder="{{ __('main.enter_your_company_website') }}" >
              </div>
          </div>
      </div>
      <div class="d-grid gap-2">
        {{--
          <button class="btn btn-primary" type="button" id="save-entreprise">
          {{ __('main.save') }}
        </button> 
        --}}
        <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>
        
      </div>
      
    </form>

    <form>
      <input type="hidden" id="#name-entreprise-error-message" value="{{ __('main.enter_your_company_name_please') }}">
    </form>

    

    <div class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Hello, world! This is a toast message.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>

    <div class="m-5">
      @include('menu.footer-global')
    </div>
</div>
@endsection
