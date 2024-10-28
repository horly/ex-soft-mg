<div class="row">
    <div class="col-md-4">
        <div class="p-4">
            <div class="text-center mb-4 profile">
                <img src="{{ asset('assets/img/logo/entreprise')}}/{{ $entreprise->url_logo }}.png" class="image rounded-circle img-fluid img-thumbnail" alt="...">
                <div class="middle">
                    @if (Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#edit-photo">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('entreprise.edit_logo') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>

    <div class="col-md-8">

        <div class="border bg-body-tertiary p-4">
            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-building"></i>&nbsp;&nbsp;&nbsp;{{ __('main.company_name') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->name }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-italic"></i>&nbsp;&nbsp;&nbsp;Slogan</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->slogan }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-file-lines"></i>&nbsp;&nbsp;&nbsp;RCCM</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->rccm }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-earth-africa"></i>&nbsp;&nbsp;&nbsp;ID NAT</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->id_nat }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-sack-dollar"></i>&nbsp;&nbsp;&nbsp;NIF</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->nif }}</div>
            </div>

            {{--
            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;{{ __('main.address') }}</div>
                <div class="col-md-8 text-primary fw-bold">{{ $entreprise->address }}</div>
            </div>

            @php
                $phones = DB::table('business_contacts')->where('id_entreprise', $entreprise->id)->get();
                $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
                $emailBs = DB::table('business_emails')->where('id_entreprise', $entreprise->id)->get();
                $bankAc = DB::table('bank_accounts')->where('id_entreprise', $entreprise->id)->get();
            @endphp

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-phone"></i>&nbsp;&nbsp;&nbsp;{{ __('main.phone_number') }}</div>
                <div class="col-md-8 text-primary fw-bold">

                    @foreach ($phones as $phone)
                        <div>
                            <i class="fa-solid fa-phone"></i>
                            {{ "(+" . $country->telephone_code . ")" }}
                            {{ chunk_split($phone->phone_number, 3, ' ') }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ __('main.email_address') }}</div>
                <div class="col-md-8 text-primary fw-bold">
                    @foreach ($emailBs as $email)
                        <div>
                            <i class="fa-solid fa-envelope"></i>
                            {{ $email->email }}
                        </div>
                    @endforeach
                </div>
            </div>
            --}}

            @php
                $bankAc = DB::table('bank_accounts')->where('id_entreprise', $entreprise->id)->get();
            @endphp

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-building-columns"></i>&nbsp;&nbsp;&nbsp;{{ __('main.bank_account') }}</div>
                <div class="col-md-8 text-primary fw-bold">
                    @foreach ($bankAc as $bank)
                        @php
                            $devise = DB::table('devises')->where('id', $bank->id_devise)->first();
                        @endphp
                        <div>
                            <i class="fa-solid fa-building-columns"></i>
                            {{ $bank->bank_name }} /
                            {{ $bank->account_number }} -
                            {{ $devise->iso_code }} -
                            {{ $bank->account_title }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4"><i class="fa-solid fa-globe"></i>&nbsp;&nbsp;&nbsp;{{ __('main.website') }}</div>
                <a href="https://{{ $entreprise->website }}" target="_blank" class="col-md-8 text-primary fw-bold">https://{{ $entreprise->website }}</a>
            </div>

            @if (Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-grid gap-2">
                            <a class="btn btn-success" role="button" href="{{ route('app_update_entreprise', ['id' => $entreprise->id]) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('entreprise.edit') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid gap-2">
                            <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $entreprise->id }}', '{{ route('app_delete_entreprise') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                <i class="fa-solid fa-trash-can"></i>
                                {{ __('entreprise.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>


{{-- modal modifier la photo de profile --}}
@include('global.edit-photo-modal')
