@extends('base')
@section('title', __('entreprise.create_a_functional_unit'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ $entreprise->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('entreprise.create_a_functional_unit') }}</li>
        </ol>
    </nav>

    <form class="border bg-body-tertiary p-4" action="{{ route('app_save_functional_unit') }}" method="POST">
        @csrf

        <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">

        <div class="mb-4 row">
            <label for="unit_name" class="col-sm-4 col-form-label">{{ __('entreprise.unit_name') }}*</label>
            <div class="col-sm-8">
              <input type="text" class="form-control @error('unit_name') is-invalid @enderror" id="unit_name" name="unit_name" placeholder="{{ __('entreprise.enter_your_functional_unit_name') }}" value="{{ old('unit_name') }}">
              <small class="text-danger">@error('unit_name') {{ $message }} @enderror</small>
            </div>
        </div>
        
        <div class="mb-4 row">
            <label for="unit_address" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
            <div class="col-sm-8">
              <textarea class="form-control  @error('unit_address') is-invalid @enderror" name="unit_address" id="unit_address" rows="4" placeholder="{{ __('entreprise.enter_your_functional_unit_address') }}">{{ old('unit_address') }}</textarea>
              <small class="text-danger">@error('unit_address') {{ $message }} @enderror</small>
            </div>
        </div>

        {{-- button de sauvegarde --}}
        @include('button.save-button')

    </form>

    <div class="m-5 fixed-bottom">
      @include('menu.footer-global')
    </div>
    
</div>


@endsection
