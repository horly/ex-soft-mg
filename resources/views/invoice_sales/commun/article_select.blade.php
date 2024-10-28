
<div class="mb-4 row">
    <label for="article_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.article') }}*</label>
    <div class="col-sm-8 select2-invoice-item-zone">
        <select class="form-select" name="article_sales_invoice" id="article_sales_invoice" onchange="getPriceArticleInvoice();">
            <option value="" selected>{{ __('invoice.select_an_article') }}</option>
            @foreach ($articles as $article)
                <option value="{{ $article->reference_art }}" purchase_price="{{ number_format($article->purchase_price, 2, '.', '') }}" sale_price="{{ number_format($article->sale_price, 2, '.', '') }}" description="{{ $article->description_art }}">
                    {{ $article->description_art }}
                </option>
            @endforeach
        </select>
        <small class="text-danger" id="article_sales_invoice-error"></small>
    </div>
    <div class="col-sm-8 d-none input-invoice-item-zone">
        <input type="text" class="form-control input-invoice-item-article" id="" disabled>
    </div>
</div>

<div class="mb-4 row">
    <label for="article_reference_invoice" class="col-sm-4 col-form-label">{{ __('client.reference') }}/SKU</label>
    <div class="col-sm-8">
        <input type="text" class="form-control custom_reference_article" id="article_reference_invoice" name="article_reference_invoice" placeholder="REF000000">
    </div>
</div>
