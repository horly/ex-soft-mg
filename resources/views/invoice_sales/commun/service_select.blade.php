<div class="mb-4 row">
    <label for="service_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.service') }}*</label>
    <div class="col-sm-8 select2-invoice-item-zone">
        <select class="form-select" name="article_sales_invoice" id="service_sales_invoice" onchange="getPriceServiceInvoice();">
            <option value="" selected>{{ __('invoice.select_a_service') }}</option>
            @foreach ($services as $service)
                <option value="{{ $service->reference_serv }}" unit_price="{{ number_format($service->unit_price, 2, '.', '') }}" description="{{ $service->description_serv }}">
                    {{ $service->description_serv }}
                </option>
            @endforeach
        </select>
        <small class="text-danger" id="service_sales_invoice-error"></small><br>
        <a href="#" id="add_manually_service" class="service_sales_invoice_default_zone">{{ __('invoice.add_manually') }}</a>

        <div class="input-group d-none service_sales_invoice_manual_zone mt-3">
            <input type="text" class="form-control" id="service_sales_invoice_manual" name="article_sales_invoice_manual" placeholder="{{ __('invoice.service_description') }}" value="default">
            <button class="btn btn-danger" type="button" id="cancel_btn_service"><i class="fa-solid fa-circle-xmark"></i> {{ __('invoice.cancel') }}</button>
        </div>
        <small class="text-danger d-none service_sales_invoice_manual_zone" id="service_sales_invoice_manual-error"></small>
    </div>

    <div class="col-sm-8 d-none input-invoice-item-zone">
        <input type="text" class="form-control input-invoice-item-service" disabled>
    </div>
</div>

<div class="mb-4 row">
    <label for="service_reference_invoice" class="col-sm-4 col-form-label">{{ __('client.reference') }}/SKU</label>
    <div class="col-sm-8">
        <input type="text" class="form-control custom_reference_service" id="service_reference_invoice" name="article_reference_invoice" placeholder="REF000000">
    </div>
</div>
