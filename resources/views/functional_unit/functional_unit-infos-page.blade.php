<div class="alert alert-primary" role="alert">
    <span>
        <i class="fa-solid fa-building"></i>&nbsp;&nbsp;&nbsp;{{ __('main.company_name') }} :
    </span>
    <span class="fw-bold">{{ $entreprise->name }}</span>
</div>

<div class="border bg-body-tertiary p-4">
    <div class="row mb-4">
        <div class="col-md-4"><i class="fa-solid fa-building-circle-arrow-right"></i>&nbsp;&nbsp;&nbsp;{{ __('entreprise.functional_unit_name') }}</div>
        <div class="col-md-8 text-primary fw-bold">{{ $functionalUnit->name }}</div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><i class="fa-solid fa-money-bill-trend-up"></i>&nbsp;&nbsp;&nbsp;{{ __('entreprise.default_currency') }}</div>
        <div class="col-md-8 text-primary fw-bold">{{ $deviseDefault->iso_code }}</div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;{{ __('main.address') }}</div>
        <div class="col-md-8 text-primary fw-bold">{{ $functionalUnit->address }}</div>
    </div>

    @php
        $phones = DB::table('functional_unit_phones')->where('id_func_unit', $functionalUnit->id)->get();
        $emails = DB::table('functionalunit_emails')->where('id_func_unit', $functionalUnit->id)->get();
        $country = DB::table('countries')->where('id', $entreprise->id_country)->first();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4"><i class="fa-solid fa-phone"></i>&nbsp;&nbsp;&nbsp;{{ __('main.phone_number') }}</div>
        <div class="col-md-8 text-primary fw-bold">
            @foreach ($phones as $phone)
                <div>
                    <i class="fa-solid fa-phone"></i>
                    {{ '+' . $country->telephone_code }}
                    {{ chunk_split($phone->phone_number, 3, ' ') }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ __('main.email_address') }}</div>
        <div class="col-md-8 text-primary fw-bold">
            @foreach ($emails as $email)
                <div>
                    <i class="fa-solid fa-envelope"></i>
                    {{ $email->email }}
                </div>
            @endforeach
        </div>
    </div>

    @if (Auth::user()->role->name == "admin")
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-grid gap-2">
                    <a class="btn btn-success" role="button" href="{{ route('app_update_page_fu', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        {{ __('entreprise.edit') }}
                    </a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-grid gap-2">
                    <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $functionalUnit->id }}', '{{ route('app_delete_functional_unit') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                        <i class="fa-solid fa-trash-can"></i>
                        {{ __('entreprise.delete') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>