{{-- start Modal --}}
<div class="modal fade" id="send-mail-invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="send_email_invoice_form" action="{{ route('app_send_email_invoice') }}" method="POST">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('invoice.send_by_email') }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @csrf

            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
            <input type="hidden" name="id_fu" id="id_fu" value="{{ $functionalUnit->id }}">
            <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $invoice->reference_sales_invoice }}">

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">{{ __('invoice.from') }} :</span>
                    <input type="email" class="form-control" id="from-email" name="from-email" value="{{ Auth::user()->email }}" readonly>
                </div>
                <small class="text-danger"></small>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">{{ __('invoice.to') }} :</span>
                    <input type="email" class="form-control" id="to-email" name="to-email" placeholder="{{ __('invoice.enter_the_recipients_email_address') }}">
                </div>
                <small class="text-danger" id="to-email-error" message="{{ __('invoice.please_enter_the_recipients_email_address') }}"></small>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">{{ __('invoice.concern') }} :</span>
                    <input type="text" class="form-control" id="concern-email" name="concern-email" value="{{ $invoice->concern_invoice }}" readonly>
                </div>
                <small class="text-danger"></small>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <select class="form-select" id="greeting" name="greeting">
                        <option value="{{ __('invoice.good_morning') }}">{{ __('invoice.good_morning') }}</option>
                        <option value="{{ __('invoice.good_evening') }}">{{ __('invoice.good_evening') }}</option>
                        <option value="{{ __('invoice.hi') }}">{{ __('invoice.hi') }}</option>
                        <option value="{{ __('invoice.sir') }}">{{ __('invoice.sir') }}</option>
                        <option value="{{ __('invoice.madam') }}">{{ __('invoice.madam') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <input type="text" class="form-control" id="recipient_name" name="recipient_name" placeholder="{{ __('invoice.recipient_name') }}">
                    <small class="text-danger" id="recipient_name-error" message="{{ __('invoice.please_enter_the_recipients_name') }}"></small>
                </div>
            </div>

            <div class="mb-4">
                <label for="exampleFormControlTextarea1" class="form-label">Message</label>
                <textarea class="form-control" id="message-email" name="message-email" placeholder="{{ __('message') }}" rows="3"></textarea>
                <small class="text-danger" id="message-email-error" message="{{ __('invoice.please_enter_your_message') }}"></small>
            </div>


        </div>
        <div class="modal-footer">
           {{-- button de fermeture modale --}}
           @include('button.close-button')

            <div class="d-grid gap-2">
                <button class="btn btn-primary saveP" type="button" id="send_email_invoice">
                    <i class="fa-solid fa-paper-plane"></i>
                    {{ __('invoice.send') }}
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
