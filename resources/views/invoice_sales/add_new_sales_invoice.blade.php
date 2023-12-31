@extends('base')
@section('title', __('invoice.add_an_invoice'))
@section('content')

<div id="app">
    @include('menu.navigation-menu')

    @include('menu.login-nav')

    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">

            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>{{ __('invoice.add_an_invoice') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_sales_invoice', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.sales_invoice') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('invoice.add_an_invoice') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- On inlut les messages flash--}}
            @include('message.flash-message')

            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('app_save_sale_invoice') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_invoice" value="0">
                            <input type="hidden" name="ref_invoice" value="{{ $ref_invoice }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="client_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.customer') }}*</label>
                                <div class="col-sm-5">
                                    <select class="form-select" name="client_sales_invoice" id="client_sales_invoice">
                                        <option value="" selected>{{ __('invoice.select_a_customer') }}</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">
                                                @if ($client->entreprise_name_cl == "-" || $client->entreprise_name_cl == "")
                                                    {{ $client->contact_name_cl }}
                                                @else
                                                    {{ $client->entreprise_name_cl }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">@error('client_sales_invoice') {{ $message }} @enderror</small>
                                </div>
                                <div class="col-sm-3 d-grid gap-2">
                                    <a href="{{ route('app_add_new_client', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary" role="button">
                                        <i class="fa-solid fa-circle-plus"></i> 
                                        &nbsp;{{ __('auth.add') }}
                                    </a>
                                </div>
                            </div>

                            <table class="table border table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">Action</th>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('article.description') }}</th>
                                    <th scope="col" class="text-end">{{ __('invoice.quantity') }}</th>
                                    <th scope="col" class="text-end">{{ __('article.unit_price') }} {{ $deviseGest->iso_code }}</th>
                                    <th scope="col" class="text-end">{{ __('invoice.total_price') }} {{ $deviseGest->iso_code }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice_elements as $invoice_element)
                                        <tr>
                                            <td>
                                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $invoice_element->id }}', '{{ route('app_delete_invoice_element') }}', '{{ csrf_token() }}')">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                                @if ($invoice_element->is_an_article == 1)
                                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" onclick="updateArticleInvoice('{{ $invoice_element->id }}');" data-bs-target="#new_article_invoice">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $invoice_element->description_inv_elmnt }}</td>
                                            <td class="text-end">{{ $invoice_element->quantity }}</td>
                                            <td class="text-end">{{ number_format($invoice_element->unit_price_inv_elmnt, 2, '.', ' ') }}</td>
                                            <td class="text-end">{{ number_format($invoice_element->total_price_inv_elmnt, 2, '.', ' ') }}</td>
                                        </tr>
                                        <div>
                                            <input type="hidden" id="ref_article-{{ $invoice_element->id }}" value="{{ $invoice_element->ref_article }}">
                                            <input type="hidden" id="description_inv_elmnt-{{ $invoice_element->id }}" value="{{ $invoice_element->description_inv_elmnt }}">
                                            <input type="hidden" id="quantity-{{ $invoice_element->id }}" value="{{ $invoice_element->quantity }}">
                                            <input type="hidden" id="margin-{{ $invoice_element->id }}" value="{{ $invoice_margin->margin }}">
                                            <input type="hidden" id="purchase-price-{{ $invoice_element->id }}" value="{{ number_format($invoice_element->purshase_price_inv_elmnt, 2, '.', '') }}">
                                            <input type="hidden" id="sale-price-{{ $invoice_element->id }}" value="{{ number_format($invoice_element->unit_price_inv_elmnt, 2, '.', '') }}">
                                            <input type="hidden" id="total-price-{{ $invoice_element->id }}" value="{{ number_format($invoice_element->total_price_inv_elmnt, 2, '.', '') }}">
                                        </div>
                                    @endforeach
                                </tbody>
                                
                            </table>

                            <table class="table border mb-4">
                                <tbody>
                                    <tr>
                                        <td>
                                            <button class="btn btn-primary" type="button" onclick="modalInsertArticleInvoice();" data-bs-toggle="modal" data-bs-target="#new_article_invoice">
                                                <i class="fa-solid fa-circle-plus"></i> 
                                                &nbsp;{{ __('invoice.insert_an_article') }}
                                            </button>
                                            <button class="btn btn-primary" type="button" onclick="modalInsertServiceInvoice();" data-bs-toggle="modal" data-bs-target="#new_service_invoice">
                                                <i class="fa-solid fa-circle-plus"></i> 
                                                &nbsp;{{ __('invoice.insert_a_service') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mb-4 row">
                                <label for="discount_invoice" class="col-sm-4 col-form-label">{{ __('invoice.apply_discount') }}</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="discount_choise" id="discount-yes" onclick="choiseDiscount('yes');" value="yes">
                                        <label class="form-check-label" for="discount-yes">{{ __('invoice.yes') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="discount_choise" id="discount-no" onclick="choiseDiscount('no');" value="no" checked>
                                        <label class="form-check-label" for="discount-no">{{ __('invoice.no') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 row d-none discount-zone">
                                <label for="discount_invoice" class="col-sm-4 col-form-label">{{ __('invoice.discount') }}</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="discount_set" id="discount-pourcentage" onclick="changeDiscountSet('%');"  value="%" checked>
                                        <label class="form-check-label" for="discount-pourcentage">%</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="discount_set" id="discount-currency" onclick="changeDiscountSet('{{ $deviseGest->iso_code }}');" value="{{ $deviseGest->iso_code }}">
                                        <label class="form-check-label" for="discount-currency">{{ $deviseGest->iso_code }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 row d-none discount-zone">
                                <label for="discount_invoice" class="col-sm-4 col-form-label">{{ __('invoice.discount_value') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discount_value" onkeyup="changeDiscountValue('{{ route('app_change_discount_customer') }}', '{{ csrf_token() }}');" onchange="changeDiscountValue('{{ route('app_change_discount_customer') }}', '{{ csrf_token() }}');" name="discount_value" value="0">
                                        <span class="input-group-text" id="discount-type-label">%</span>
                                    </div>
                                </div>
                            </div>


                            <div class="mb-4 row">
                                <label for="date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.vat') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-3">
                                        <select class="form-select" name="vat-apply-change" id="vat-apply-change" onchange="changeVat('{{ route('app_change_vat') }}', '{{ csrf_token() }}');">
                                            <option value="0" selected>0</option>
                                            <option value="{{ $country->vat_rat }}">{{ $country->vat_rat }}</option>
                                        </select>
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                </div>
                            </div>

                            <table class="table border table-striped mb-4">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">{{ __('invoice.total_excl_tax') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            <span id="tot_excl_tax_td">{{ number_format($tot_excl_tax, 2, '.', ' ') }}</span>
                                        </td>
                                    </tr>
                                    <tr class="d-none discount-zone">
                                        <td class="fw-bold">{{ __('invoice.discount') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            <span id="discount_apply_td">0.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('invoice.vat') }} <span id="vat_apply_pur">0</span>%</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            <span id="vat_apply_td">0.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            <span id="tot_incl_tax_td">{{ number_format($tot_excl_tax, 2, '.', ' ') }} </span>
                                        </td>
                                    </tr>
                                    <div>
                                        <input type="hidden" name="tot_excl_tax" id="tot_excl_tax" value="{{ $tot_excl_tax }}">
                                        <input type="hidden" name="discount_apply_input" id="discount_apply_input" value="0">
                                        <input type="hidden" name="vat_apply_input" id="vat_apply_input" value="0">
                                        <input type="hidden" name="tot_incl_tax_input" id="tot_incl_tax_input" value="{{ $tot_excl_tax }}">
                                        <input type="hidden" name="amount_received" id="amount_received" value="0"> 
                                    </div>
                                </tbody>
                            </table>

                            <div class="mb-4 row">
                                <label for="date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="date_sales_invoice" name="date_sales_invoice"  value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="due_date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.due_date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="due_date_sales_invoice" name="due_date_sales_invoice"  value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                             {{-- button de sauvegarde --}}
                             @include('button.save-button')

                        </form>
                    </div>
                </div>
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>

        </div>
    </div>

</div>


<div class="modal fade" id="new_article_invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_insert_article_invoice" action="{{ route('app_insert_invoice_element') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-bank-account-modal">{{ __('invoice.insert_an_article') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest-article" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">
                <input type="hidden" name="descrption_saved_art" id="descrption_saved_art" value="">
                <input type="hidden" name="id_invoice_margin" id="id_invoice_margin" value="{{ $invoice_margin->id }}">
                <input type="hidden" name="id_invoice_element" id="id_invoice_element" value="0">
                <input type="hidden" name="is_an_article" id="is_an_article" value="1"> 

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
                        <input type="text" class="form-control" id="input-invoice-item" disabled>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_qty_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" name="article_qty_invoice" id="article_qty_invoice" class="form-control text-end" onkeyup="changeTotalPrice();" onchange="changeTotalPrice();" min="1" value="1">
                        <small class="text-danger" id="article_qty_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_margin_invoice" class="col-sm-4 col-form-label">{{ __('invoice.margin') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" name="article_margin_invoice" id="article_margin_invoice" onkeyup="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" onchange="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" url="{{ route('app_calculate_margin') }}" token="{{ csrf_token() }}" class="form-control text-end" min="0" value="{{ $invoice_margin->margin }}">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                        <small class="text-danger" id="article_margin_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_purchase_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.purchase_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_purchase_price_invoice" id="article_purchase_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_sale_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.sale_prise') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_sale_price_invoice" id="article_sale_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_total_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.total_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_total_price_invoice" id="article_total_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
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
                    <button class="btn btn-primary saveP" type="button" id="insert_article_invoice">
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
                <input type="hidden" name="modalRequest" id="modalRequest-service" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">
                <input type="hidden" name="descrption_saved_art" id="descrption_saved_serv" value="">
                <input type="hidden" name="id_invoice_margin" id="id_invoice_margin" value="{{ $invoice_margin->id }}">
                <input type="hidden" name="id_invoice_element" id="id_invoice_element" value="0">
                <input type="hidden" name="is_an_article" id="is_an_article" value="0"> 

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
                        <small class="text-danger" id="service_sales_invoice-error"></small>
                    </div>
                    <div class="col-sm-8 d-none input-invoice-item-zone">
                        <input type="text" class="form-control" id="input-invoice-item-service" disabled>
                    </div>
                </div>

                <div class="mb-4 row d-none">
                    <label for="article_qty_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" name="article_qty_invoice" id="article_qty_invoice" class="form-control text-end" onkeyup="changeTotalPrice();" onchange="changeTotalPrice();" min="1" value="1">
                        <small class="text-danger" id="article_qty_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row d-none">
                    <label for="article_margin_invoice" class="col-sm-4 col-form-label">{{ __('invoice.margin') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" name="article_margin_invoice" id="article_margin_invoice" onkeyup="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" onchange="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" url="{{ route('app_calculate_margin') }}" token="{{ csrf_token() }}" class="form-control text-end" min="0" value="{{ $invoice_margin->margin }}">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                        <small class="text-danger" id="article_margin_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row d-none">
                    <label for="article_purchase_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.purchase_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_purchase_price_invoice" id="article_purchase_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row d-none">
                    <label for="article_sale_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.sale_prise') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_sale_price_invoice" id="article_sale_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="service_total_price_invoice" class="col-sm-4 col-form-label">{{ __('service.unit_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_total_price_invoice" id="service_total_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
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
                    <button class="btn btn-primary saveP" type="button" id="insert_service_invoice">
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


<form action="#">
    <input type="hidden" id="article_sales_invoice-message" value="{{ __('invoice.select_an_article_please') }}">
    <input type="hidden" id="article_qty_invoice-message" value="{{ __('invoice.quantity_cannot_be_empty') }}"> 
    <input type="hidden" id="article_margin_invoice-message" value="{{ __('invoice.margin_cannot_be_empty') }}">
    <input type="hidden" id="service_sales_invoice-message" value="{{ __('invoice.select_a_service_please') }}">
</form>

@endsection