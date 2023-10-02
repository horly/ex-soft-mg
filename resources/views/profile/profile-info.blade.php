<div class="row">
    <div class="col-md-4">
        <div class="p-4">
            <div class="text-center mb-4 profile">
                <img src="{{ asset('assets/img/profile') }}/{{ Auth::user()->photo_profile_url }}.png" class="image rounded-circle img-fluid img-thumbnail" alt="...">
                <div class="middle">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#edit-photo">
                            <i class="fa-solid fa-pen-to-square"></i>
                            {{ __('entreprise.edit_logo') }}
                        </button>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <div class="col-md-8">
        
        <div class="border bg-body-tertiary p-4">
            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;&nbsp;{{ __('main.name') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ Auth::user()->name }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ __('auth.email') }}</div>
                <div class="col-md-8 text-primary fw-bold">
                    <span>{{ Auth::user()->email }}</span>
                    <span> | </span>
                    <span><a href="{{ route('app_change_email_address_request') }}"><i class="fa-solid fa-pen-to-square"></i> {{ __('entreprise.edit') }}</a></span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-lock"></i>&nbsp;&nbsp;&nbsp;{{ __('auth.password') }}</div>
                <div class="col-md-8 text-primary fw-bold">
                    <span>***********</span>
                    <span> | </span>
                    <span><a href="#"><i class="fa-solid fa-pen-to-square"></i> {{ __('entreprise.edit') }}</a></span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-universal-access"></i>&nbsp;&nbsp;&nbsp;{{ __('auth.role') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ __('profile.' . Auth::user()->role->name) }}</div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;&nbsp;{{ __('main.function') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ Auth::user()->grade->name }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-earth-africa"></i>&nbsp;&nbsp;&nbsp;{{ __('main.country') }}</div>
                <div class="col-md-8 text-primary fw-bold">
                    {{ Config::get('app.locale') == 'en' ? Auth::user()->country->name_gb : Auth::user()->country->name_fr }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-phone"></i>&nbsp;&nbsp;&nbsp;{{ __('main.phone_number') }}</div>
                <div class="col-md-8 text-primary fw-bold">+{{ Auth::user()->country->telephone_code }} {{ chunk_split(Auth::user()->phone_number, 3, ' ') }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-barcode"></i>&nbsp;&nbsp;&nbsp;{{ __('main.registration_number') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ Auth::user()->matricule }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;{{ __('main.address') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ Auth::user()->address }}</div>
            </div>

            <div class="d-grid gap-2">
                <a class="btn btn-primary" role="button" href="{{ route('app_edit_profile_info') }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    {{ __('entreprise.edit') }}
                </a>
            </div>

        </div>
    </div>
</div>

{{-- modal modifier la photo de profile --}}
@include('global.edit-photo-modal')
