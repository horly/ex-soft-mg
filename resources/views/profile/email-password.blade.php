@extends('base')
@section('title', __('profile.profile'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
   @include('profile.nav-profile')

   {{-- On inlut les messages flash--}}
   @include('message.flash-message')

   <div class="border">
       <div class="border-bottom p-4">
           @include('profile.profile-tab')
       </div>

       <div class="p-5" id="myTabContent">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <form class="border bg-body-tertiary" action="{{ route('app_change_email_address') }}" method="POST">
                        <div class="border-bottom fw-bold text-muted p-3">{{ __('auth.change_email_address') }}</div>
                        @csrf

                        <div class="p-5">
                            <div class="mb-4">
                                <label for="current_email" class="col-form-label">{{ __('profile.current_email_address') }}*</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control @error('current_email') is-invalid @enderror" name="current_email" id="current_email" placeholder="{{ __('profile.enter_the_current_email_address') }}" value="{{ old('current_email') }}">
                                </div>
                                <small class="text-danger">@error('current_email'){{ $message }}@enderror</small>
                            </div>

                            <div class="mb-4">
                                <label for="new_email" class="col-form-label">{{ __('profile.new_email_address') }}*</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control @error('new_email') is-invalid @enderror" name="new_email" id="new_email" placeholder="{{ __('profile.enter_the_new_email_address') }}" value="{{ old('new_email') }}">
                                </div>
                                <small class="text-danger">@error('new_email'){{ $message }}@enderror</small>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_new_email" class="col-form-label">{{ __('profile.confirm_the_new_email_address') }}*</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control @error('confirm_new_email') is-invalid @enderror" name="confirm_new_email" id="confirm_new_email" placeholder="{{ __('profile.enter_the_new_email_address') }}" value="{{ old('confirm_new_email') }}">
                                </div>
                                <small class="text-danger">@error('confirm_new_email'){{ $message }}@enderror</small>
                            </div>

                            @include('button.save-button')

                        </div>

                    </form>
                </div>
                <div class="col-md-6 mb-3">
                    <form class="border bg-body-tertiary" action="">
                        <div class="border-bottom fw-bold text-muted p-3">{{ __('auth.change_password') }}</div>
                    </form>
                </div>
            </div>
       </div>
   </div>    

   <div class="m-5">
       @include('menu.footer-global')
   </div>
</div>


@endsection