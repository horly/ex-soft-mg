
<div class="row">
    <div class="col-md-6">
        <div class="p-4 border rounded" style="height: 100%">
            <div class="border-bottom mb-4 fw-bold">
                {{ __('expenses.purchase_details') }}
            </div>


            <form action="{{ route('app_save_purchase') }}" method="POST">
                @csrf

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="id_purchase" value="{{ $purchases ? $purchases->id : "0" }}">
                <input type="hidden" name="reference_purch" value="{{ $ref_purchase }}">
                <input type="hidden" name="customerRequest" id="customerRequest" value="{{ $purchases ? "edit" : "add" }}">

                <div class="mb-4 row">
                    <label for="supplier_purchase" class="col-sm-4 col-form-label">{{ __('supplier.supplier') }}*</label>
                    <div class="col-sm-8">
                        <select class="form-select @error('supplier_purchase') is-invalid @enderror" name="supplier_purchase" id="supplier_purchase">
                            @if ($purchases)
                                    @php
                                        $supplier = DB::table('suppliers')->where('id', $purchases->id_supplier)->first();
                                    @endphp
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->type_sup == "particular" ? $supplier->contact_name_sup : $supplier->entreprise_name_sup }}
                                </option>
                            @else
                                <option value="">
                                    {{ __('supplier.select_a_supplier') }}
                                </option>
                            @endif

                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    @if ($supplier->type_sup == "particular")
                                        {{ $supplier->contact_name_sup }}
                                    @else
                                        {{ $supplier->entreprise_name_sup }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-danger">@error('supplier_purchase') {{ $message }} @enderror</small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="designation_purchase" class="col-sm-4 col-form-label">{{ __('dashboard.designation') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('designation_purchase') is-invalid @enderror" name="designation_purchase" id="designation_purchase" placeholder="{{ __('expenses.enter_the_purchase_name') }}" value="{{ $purchases ? $purchases->designation : "" }}">
                        <small class="text-danger">@error('designation_purchase') {{ $message }} @enderror</small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="amount_purchase" class="col-sm-4 col-form-label">{{ __('dashboard.amount') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.00" class="form-control text-end @error('amount_purchase') is-invalid @enderror" name="amount_purchase" id="amount_purchase" placeholder="0.00" value="{{ $purchases ? $purchases->amount : "" }}">
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger">@error('amount_purchase') {{ $message }} @enderror</small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="date_purchase" class="col-sm-4 col-form-label">{{ __('invoice.date') }}*</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="date_purchase" name="date_purchase"  value="{{ $purchases ? date('Y-m-d', strtotime($purchases->created_at)) : date('Y-m-d') }}">
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="due_date_purchase" class="col-sm-4 col-form-label">{{ __('invoice.due_date') }}*</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="due_date_purchase" name="due_date_purchase"  value="{{ $purchases ? date('Y-m-d', strtotime($purchases->due_date)) : date('Y-m-d') }}">
                    </div>
                </div>

                @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                    {{-- button de sauvegarde --}}
                    @include('button.save-button')

                    <br>

                    @if ($purchases)

                        @if ($paymentReceived != 0 )
                            <div class="d-grid gap-2">
                                <button class="btn btn-danger" type="button" disabled>
                                    <i class="fa-solid fa-trash-can"></i>
                                    {{ __('entreprise.delete') }}
                                </button>
                            </div>
                        @else
                            <div class="d-grid gap-2">
                                <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $purchases->reference_purch }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_purchase') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                    {{ __('entreprise.delete') }}
                                </button>
                            </div>
                        @endif

                    @endif

                    <br>

                    @if ($purchases)

                        @if ($paymentReceived == $purchases->amount && $remainingBalance == 0 )
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="button" disabled>
                                    <i class="fa-solid fa-coins"></i>
                                    {{ __('expenses.record_a_payment') }}
                                </button>
                            </div>
                        @else
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#cash-in">
                                    <i class="fa-solid fa-coins"></i>
                                    {{ __('expenses.record_a_payment') }}
                                </button>
                            </div>
                        @endif

                    @endif
                @endif
            </form>


        </div>
    </div>
    <div class="col-md-6">
        <div class="p-4 border rounded">

            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")

                <form class="mb-3 row" id="purchase_upload_pdf_form" method="POST" action="{{ route('app_upload_purchase_pdf') }}" token="{{ csrf_token() }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ref_purchase" value="{{ $ref_purchase }}">
                    <input type="hidden" name="file_purchase-message" id="file_purchase-message" value="{{ __('expenses.please_select_a_file') }}">
                    <input type="hidden" name="file_purchase-size" id="file_purchase-size" value="{{ __('expenses.file_must_not_exceed') }}">

                    <label for="file_purchase" class="col-sm-3 col-form-label">{{ __('expenses.add_a_file') }}</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input class="form-control" type="file" id="file_purchase" name="file_purchase" accept=".pdf">
                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                &nbsp;{{ __('main.save') }}
                            </button>
                        </div>
                        <small class="text-danger" id="file_purchase-error"></small>

                        <div class="progress mt-3" id="zone-progress-bar-purchase" hidden>
                            <div class="progress-bar bg-success" role="progressbar" id="progress-bar-purchase" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </form>

                <div class="border-bottom mb-4 fw-bold">
                    {{ __('expenses.preview') }}
                </div>
                <object data="{{ asset('assets/img/purchase') }}/{{ $ref_purchase }}.pdf" type="application/pdf" width="100%" height="500px">
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="fa-regular fa-file"></i> {{ __('expenses.no_preview_available') }}
                    </div>
                </object>
            @endif

        </div>
    </div>
</div>

{{-- Pour le decaissement et l'encaissement --}}
@include('global.modale-payment')

