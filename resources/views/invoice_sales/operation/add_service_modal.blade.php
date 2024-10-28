
<div class="modal fade" id="new_service_invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_insert_service_invoice" action="{{ route('app_insert_invoice_element') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-bank-account-modal">{{ __('invoice.insert_a_service') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="modalRequest" class="modalRequest-article" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">
                <input type="hidden" name="descrption_saved_art" id="descrption_saved_serv" value="">
                <input type="hidden" name="id_invoice_margin" id="id_invoice_margin" value="{{ $invoice_margin->id }}">
                <input type="hidden" name="id_invoice_element" class="id_invoice_element" value="0">
                <input type="hidden" name="is_an_article" id="is_an_article" value="0">

                <input type="hidden" class="customer_session" name="id_customer_session_art" id="id_customer_session_art" value="0">
                <input type="hidden" class="contact_session" name="id_contact_session_art" id="id_contact_session_art" value="0">
                <input type="hidden" name="concerne_session" id="concerne_session_service" value="">

                @include('invoice_sales.commun.service_select')

                <div class="mb-4 row">
                    <label for="service_qty_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" name="article_qty_invoice" id="service_qty_invoice" class="form-control text-end" onkeyup="changeTotalPriceService('add');" onchange="changeTotalPriceService('add');" min="1" value="1">
                        <small class="text-danger" id="service_qty_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="service_unit_price_invoice" class="col-sm-4 col-form-label">{{ __('service.unit_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_sale_price_invoice" id="service_unit_price_invoice" class="form-control text-end" onkeyup="changeTotalPriceService('add');" onchange="changeTotalPriceService('add');" min="0" value="0">
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="service_total_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.total_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_total_price_invoice" id="service_total_price_invoice" class="form-control text-end" min="0" value="0.00">
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')

                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" onclick="insert_invoice_item_service('add')">
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
