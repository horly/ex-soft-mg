{{-- start Modal --}}
<div class="modal fade" id="cash-in" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="record_payment_invoice_form" action="{{ route('app_save_record_payment') }}" method="POST">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('invoice.record_a_payment') }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @csrf

            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
            <input type="hidden" name="id_fu" id="id_fu" value="{{ $functionalUnit->id }}">

            @if ($purchases)
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_purchase }}">
                <input type="hidden" name="type_record" id="type_record" value="cash_out">
            @else
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">
                <input type="hidden" name="type_record" id="type_record" value="cash_in">
            @endif

            <div class="mb-4 row">
                <label for="payment_methods_invoice_record" class="col-sm-4 col-form-label">{{ __('dashboard.payment_methods') }}*</label>
                <div class="col-sm-8">
                    <select class="form-select" name="payment_methods_invoice_record" id="payment_methods_invoice_record">
                        <option value="">{{ __('invoice.select_a_payment_method') }}</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">
                                @if ($paymentMethod->default == 1)
                                    {{ __('payment_methods.' . $paymentMethod->designation) }} ({{ $paymentMethod->iso_code }})
                                @else
                                    {{ $paymentMethod->designation }} ({{ $paymentMethod->iso_code }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-danger" id="payment_methods_invoice_record-error"></small>
                    <input type="hidden" id="payment_methods_invoice_record-error-message" name="payment_methods_invoice_record-error-message" value="{{ __('invoice.select_a_payment_method_please') }}">
                </div>
            </div>

            <div class="mb-4 row">
                <label for="amount_invoice_record" class="col-sm-4 col-form-label">{{ __('dashboard.amount') }}*</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="number" step="0.01" name="amount_invoice_record" id="amount_invoice_record" class="form-control text-end" min="0" placeholder="0.00">
                        <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                    </div>
                    <small class="text-danger" id="amount_invoice_record-error"></small>
                    <input type="hidden" id="amount_invoice_record-error-message" name="amount_invoice_record-error-message" value="{{ __('invoice.the_amount_to_be_collected_cannot_be_greater_than_the_remaining_balance') }}">
                    <input type="hidden" id="amount_invoice_record-error-message-empty" name="amount_invoice_record-error-message-empty" value="{{ __('invoice.amount_cannot_be_empty') }}">
                </div>
            </div>

        </div>
        <div class="modal-footer">
           {{-- button de fermeture modale --}}
           @include('button.close-button')

            <div class="d-grid gap-2">
                <button class="btn btn-primary saveP" type="button" id="record_payment_invoice" url="{{ route('app_check_records_amount_invoice') }}" token="{{ csrf_token() }}">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ __('main.save') }}
                </button>
                <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('auth.loading') }}
                </button>
            </div>
        </div>
    </form>
    </div>
</div>
{{-- and Modal --}}
