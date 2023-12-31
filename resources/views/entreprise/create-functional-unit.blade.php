@extends('base')
@section('title', __('entreprise.create_a_functional_unit'))
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ $entreprise->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('entreprise.create_a_functional_unit') }}</li>
        </ol>
    </nav>

    <form class="card" action="{{ route('app_save_functional_unit') }}" method="POST">
        @csrf

        <div class="card-body">
          <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
          <input type="hidden" name="id_fu" value="0">
          <input type="hidden" name="fuRequest" id="fuRequest" value="add"> {{-- Default is add but can be edit also --}}

          <div class="mb-4 row">
              <label for="unit_name" class="col-sm-4 col-form-label">{{ __('entreprise.functional_unit_name') }}*</label>
              <div class="col-sm-8">
                <input type="text" class="form-control @error('unit_name') is-invalid @enderror" id="unit_name" name="unit_name" placeholder="{{ __('entreprise.enter_your_functional_unit_name') }}" value="{{ old('unit_name') }}">
                <small class="text-danger">@error('unit_name') {{ $message }} @enderror</small>
              </div>
          </div>

          <div class="mb-4 row">
              <label for="currency_fu" class="col-sm-4 col-form-label">{{ __('entreprise.default_currency') }}*</label> 
              <div class="col-sm-8">
                <select name="currency_fu" id="currency_fu" class="form-select @error('currency_fu') is-invalid @enderror">
                    <option value="" selected>{{ __('entreprise.select_your_curreny') }}</option>
                    @if (Config::get('app.locale') == 'en')
                        @foreach ($devises as $devise)
                            <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto_en }}</option>
                        @endforeach
                    @else
                        @foreach ($devises as $devise)
                            <option value="{{ $devise->id }}">{{ $devise->iso_code }} - {{ $devise->motto }}</option>
                        @endforeach
                    @endif
                </select>
                <small class="text-danger">@error('currency_fu') {{ $message }} @enderror</small>
              </div>
          </div>
          
          <div class="mb-4 row">
              <label for="unit_address" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
              <div class="col-sm-8">
                <textarea class="form-control  @error('unit_address') is-invalid @enderror" name="unit_address" id="unit_address" rows="4" placeholder="{{ __('entreprise.enter_your_functional_unit_address') }}">{{ old('unit_address') }}</textarea>
                <small class="text-danger">@error('unit_address') {{ $message }} @enderror</small>
              </div>
          </div>

          @php
              $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
          @endphp

          <div class="mb-4 row">
            <label for="unit_phone" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
            <div class="col-sm-8">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">+{{ $country->telephone_code }}</span>
                  <input type="number" class="form-control @error('unit_phone') is-invalid @enderror" id="unit_phone" name="unit_phone" placeholder="{{ __('entreprise.enter_your_functional_unit_phone_number') }}" value="{{ old('unit_name') }}">
                </div>
                <small class="text-danger">@error('unit_phone') {{ $message }} @enderror</small>
            </div>
          </div>

          <div class="mb-4 row">
            <label for="unit_email" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
            <div class="col-sm-8">
                <input type="email" class="form-control @error('unit_email') is-invalid @enderror" id="unit_email" name="unit_email" placeholder="{{ __('entreprise.enter_your_functional_unit_email_address') }}" value="{{ old('unit_name') }}">
                <small class="text-danger">@error('unit_email') {{ $message }} @enderror</small>
            </div>
          </div>

          {{-- button de sauvegarde --}}
          @include('button.save-button')
        </div>

    </form>

    <div class="m-5">
      @include('menu.footer-global')
    </div>
    
</div>


@endsection
