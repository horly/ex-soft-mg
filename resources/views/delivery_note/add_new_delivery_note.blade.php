@extends('base')
@section('title', __('invoice.add_a_delivery_note'))
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
                        <h3>{{ __('invoice.add_a_delivery_note') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_delivery_note', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.delivery_note') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('invoice.add_a_delivery_note') }}</li>
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
                            <input type="hidden" name="is_proforma" value="{{ $invoice_margin->is_proforma }}">
                            <input type="hidden" name="is_delivery_note_marge" value="{{ $invoice_margin->is_delivery_note_marge }}">
                            <input type="hidden" name="is_simple_invoice_inv" value="{{ $invoice_margin->is_simple_invoice_inv }}">

                            <div class="mb-4 row">
                                <label for="ref_personalized" class="col-sm-2 col-form-label">{{ __('client.reference') }}</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="ref_personalized" id="ref_personalized" value="{{ $reference_personalized }}" readonly>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="client_sales_invoice" class="col-sm-2 col-form-label">{{ __('invoice.customer') }}*</label>
                                <div class="col-sm-4">
                                    <select class="form-select @error('client_sales_invoice') is-invalid @enderror" name="client_sales_invoice" id="client_sales_invoice" url="{{ route('app_get_contact_client_invoice') }}" token="{{ csrf_token() }}">
                                        <option value="@if(Session::has('id_client')){{ Session::get('id_client') }}@endif" selected>
                                            @if(Session::has('entreprise_client'))
                                                {{ Session::get('entreprise_client') }}
                                            @else
                                                {{ __('invoice.select_a_customer') }}
                                            @endif
                                        </option>

                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">
                                                @if ($client->type == "particular")
                                                    @php
                                                        $contact = DB::table('customer_contacts')->where('id_client', $client->id)->first();
                                                    @endphp
                                                    {{ $contact->fullname_cl }}
                                                @else
                                                    {{ $client->entreprise_name_cl }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">@error('client_sales_invoice') {{ $message }} @enderror</small>
                                </div>
                                <label for="client_contact_sales_invoice" class="col-sm-2 col-form-label">Contact*</label>
                                <div class="col-sm-4">
                                    <select class="form-select" name="client_contact_sales_invoice" id="client_contact_sales_invoice">
                                        <option value="@if(Session::has('id_contact')){{ Session::get('id_contact') }}@endif" selected>
                                            @if(Session::has('fullname_contact'))
                                                {{ Session::get('fullname_contact') }}
                                            @else
                                                {{ __('client.select_a_contact') }}
                                            @endif
                                        </option>
                                    </select>
                                    <input type="hidden" id="client_select_a_contact" name="client_select_a_contact" value="{{ __('client.select_a_contact') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="" class="col-sm-2 col-form-label">{{ __('invoice.concern') }}*</label>
                                <div class="col-sm-10">
                                    <input type="text" name="invoice_concern_sales" id="invoice_concern_sales" class="form-control @error('invoice_concern_sales') is-invalid @enderror" placeholder="{{ __('invoice.enter_the_invoice_subject') }}">
                                    <small class="text-danger">@error('invoice_concern_sales') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <table class="table border table-striped">
                                <thead>
                                    <tr>
                                      <th scope="col">Action</th>
                                      <th scope="col">#</th>
                                      <th scope="col">REF/SKU</th>
                                      <th scope="col">{{ __('article.description') }}</th>
                                      <th scope="col">{{ __('invoice.serial_number')}}</th>
                                      <th scope="col" class="text-end">{{ __('invoice.quantity') }}</th>
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
                                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" onclick="updateArticleInvoice('{{ $invoice_element->id }}', 'article');" data-bs-target="#update_article_invoice">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $invoice_element->custom_reference }}</td>
                                            <td>{{ $invoice_element->description_inv_elmnt }}</td>
                                            <td>
                                                @php
                                                    $serial_numbers = DB::table('serial_number_invoices')->where('id_invoice_element', $invoice_element->id)->get();
                                                    $count_serial_numbers = DB::table('serial_number_invoices')->where('id_invoice_element', $invoice_element->id)->count();
                                                @endphp

                                                <ul class="list-group mb-2">
                                                    @foreach ($serial_numbers as $serial_number)
                                                        <li class="list-group-item">
                                                            <span>
                                                                {{ $loop->iteration }}- {{ $serial_number->description }}
                                                            </span>
                                                            <span class="float-end">
                                                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $serial_number->id }}', '{{ route('app_delete_serial_number_invoice') }}', '{{ csrf_token() }}')">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" onclick="modalUpdateSerialNumberInvoice('{{ $invoice_element->id }}', '{{ $invoice_element->description_inv_elmnt }}', '{{ $invoice_element->quantity }}', '{{ $serial_number->id }}', '{{ $serial_number->description }}', '{{ $loop->iteration }}');" data-bs-toggle="modal" data-bs-target="#insert_serial_number_invoice">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </button>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                @if ($count_serial_numbers < $invoice_element->quantity)
                                                    <button class="btn btn-primary" type="button" id="add-serial-number-invoice" onclick="modalInsertSerialNumberInvoice('{{ $invoice_element->id }}', '{{ $invoice_element->description_inv_elmnt }}', '{{ $invoice_element->quantity }}');" data-bs-toggle="modal" data-bs-target="#insert_serial_number_invoice">
                                                        <i class="fa-solid fa-circle-plus"></i>
                                                        &nbsp;{{ __('auth.add') }}
                                                    </button>
                                                @endif

                                            </td>
                                            <td class="text-end">{{ $invoice_element->quantity }}</td>
                                        </tr>
                                        <div>
                                            <input type="hidden" id="ref_article-{{ $invoice_element->id }}" value="{{ $invoice_element->ref_article }}">
                                            <input type="hidden" id="custom_reference-{{ $invoice_element->id }}" value="{{ $invoice_element->custom_reference }}">
                                            <input type="hidden" id="description_inv_elmnt-{{ $invoice_element->id }}" value="{{ $invoice_element->description_inv_elmnt }}">
                                            <input type="hidden" id="quantity-{{ $invoice_element->id }}" value="{{ $invoice_element->quantity }}">
                                            <input type="hidden" id="margin-{{ $invoice_element->id }}" value="{{ $invoice_margin->margin }}">
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
                                            {{--
                                            <button class="btn btn-primary" type="button" onclick="modalInsertServiceInvoice();" data-bs-toggle="modal" data-bs-target="#new_service_invoice">
                                                <i class="fa-solid fa-circle-plus"></i>
                                                &nbsp;{{ __('invoice.insert_a_service') }}
                                            </button>
                                            --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <input type="hidden" name="tot_excl_tax" id="tot_excl_tax" value="{{ $tot_excl_tax }}">
                            <input type="hidden" name="discount_apply_input" id="discount_apply_input" value="0">
                            <input type="hidden" name="vat_apply_input" id="vat_apply_input" value="0">
                            <input type="hidden" name="tot_incl_tax_input" id="tot_incl_tax_input" value="{{ $tot_excl_tax }}">
                            <input type="hidden" name="amount_received" id="amount_received" value="0">

                            @php
                                $invoice = DB::table('sales_invoices')->where('reference_sales_invoice', $ref_invoice)->first();
                            @endphp

                            @if ($invoice)
                                <input type="hidden" class="form-control" name="discount_value" value="{{ $invoice->discount_value }}">
                                <input type="hidden" class="form-control" name="discount_set" value="{{ $invoice->discount_type }}">
                                <input type="hidden" class="form-control" name="vat-apply-change" value="{{ $country->vat_rat }}">
                            @else
                                <input type="hidden" class="form-control" name="discount_value" value="0">
                                <input type="hidden" class="form-control" name="discount_set" value="%">
                                <input type="hidden" class="form-control" name="vat-apply-change" value="0">
                            @endif

                            <div class="mb-4 row">
                                <label for="date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="date_sales_invoice" name="date_sales_invoice"  value="@if(Session::has('date_sales_invoice')){{ Session::get('date_sales_invoice') }}@else{{ date('Y-m-d') }}@endif">
                                </div>
                            </div>

                            <div class="mb-4 row" hidden>
                                <label for="due_date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.due_date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="due_date_sales_invoice" name="due_date_sales_invoice"  value="@if(Session::has('due_date_sales_invoice')){{ Session::get('due_date_sales_invoice') }}@else{{ date('Y-m-d') }}@endif">
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
                            @endif

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

{{-- start add article --}}
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

                <input type="hidden" class="customer_session" name="id_customer_session_art" id="id_customer_session_art" value="0">
                <input type="hidden" class="contact_session" name="id_contact_session_art" id="id_contact_session_art" value="0">
                <input type="hidden" name="concerne_session" id="concerne_session" value="">

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
                        <input type="text" class="form-control input-invoice-item-article" disabled>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_reference_invoice" class="col-sm-4 col-form-label">{{ __('client.reference') }}/SKU</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom_reference_article" id="article_reference_invoice" name="article_reference_invoice" placeholder="REF000000">
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_qty_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" name="article_qty_invoice" id="article_qty_invoice" class="form-control text-end" onkeyup="changeTotalPrice();" onchange="changeTotalPrice();" min="1" value="1">
                        <small class="text-danger" id="article_qty_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_margin_invoice" class="col-sm-4 col-form-label">{{ __('invoice.margin') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" name="article_margin_invoice" id="article_margin_invoice" onkeyup="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" onchange="calculateMargin('{{ route('app_calculate_margin') }}', '{{ csrf_token() }}');" url="{{ route('app_calculate_margin') }}" token="{{ csrf_token() }}" class="form-control text-end" min="0" value="{{ $invoice_margin->margin }}">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                        <small class="text-danger" id="article_margin_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_purchase_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.purchase_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_purchase_price_invoice" id="article_purchase_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_sale_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.sale_prise') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_sale_price_invoice" id="article_sale_price_invoice" class="form-control text-end" min="0" value="0.00" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
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
                    <button class="btn btn-primary saveP" type="button" id="insert_article_invoice" onclick="insert_invoice_item('add');">
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
{{-- end add article --}}

{{-- start update article --}}
<div class="modal fade" id="update_article_invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_insert_article_invoice_updt" action="{{ route('app_insert_invoice_element') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-bank-account-modal">{{ __('invoice.insert_an_article') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest-article" value="edit"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">
                <input type="hidden" name="id_invoice_margin" id="id_invoice_margin" value="{{ $invoice_margin->id }}">
                <input type="hidden" name="id_invoice_element" class="id_invoice_element" value="0">
                <input type="hidden" name="is_an_article" id="is_an_article" value="1">

                <input type="hidden" class="customer_session" name="id_customer_session_art" id="id_customer_session_art" value="0">
                <input type="hidden" class="contact_session" name="id_contact_session_art" id="id_contact_session_art" value="0">
                <input type="hidden" name="concerne_session" id="concerne_session" value="">

                <div class="mb-4 row">
                    <label for="article_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.article') }}*</label>
                    <div class="col-sm-8 d-none input-invoice-item-zone">
                        <input type="text" class="form-control input-invoice-item-article" disabled>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_reference_invoice" class="col-sm-4 col-form-label">{{ __('client.reference') }}/SKU</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control custom_reference_article" id="article_reference_invoice" name="article_reference_invoice" placeholder="REF000000">
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="article_qty_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" name="article_qty_invoice" id="article_qty_invoice_updt" class="form-control text-end" min="1" value="1">
                        <small class="text-danger" id="article_qty_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_margin_invoice" class="col-sm-4 col-form-label">{{ __('invoice.margin') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" name="article_margin_invoice" id="article_margin_invoice" class="form-control text-end" min="0" value="{{ $invoice_margin->margin }}">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                        <small class="text-danger" id="article_margin_invoice-error"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_purchase_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.purchase_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_purchase_price_invoice" id="article_purchase_price_invoice" class="form-control text-end" min="0" value="0" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_sale_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.sale_prise') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_sale_price_invoice" id="article_sale_price_invoice" class="form-control text-end" min="0" value="0" readonly>
                            <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                        </div>
                        <small class="text-danger"></small>
                    </div>
                </div>

                <div class="mb-4 row" hidden>
                    <label for="article_total_price_invoice" class="col-sm-4 col-form-label">{{ __('invoice.total_price') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" step="0.01" name="article_total_price_invoice" id="article_total_price_invoice" class="form-control text-end" min="0" value="0" readonly>
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
                    <button class="btn btn-primary saveP" type="button" id="insert_article_invoice" onclick="insert_invoice_item('update');">
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
{{-- end update article --}}



<div class="modal fade" id="insert_serial_number_invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form_serial_number_invoice" action="{{ route('app_add_serial_number_invoice') }}" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="insert_serial_number_invoice-modal-title">{{ __('invoice.insert_an_article') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body-tertiary p-4">
                @csrf

                <input type="hidden" name="serial_number_invoice-message" id="serial_number_invoice-message" value="{{ __('invoice.enter_a_serial_number_please') }}">
                <input type="hidden" name="id_invoice_element_sn" id="id_invoice_element_sn">
                <input type="hidden" name="id_serial_number_invoice" id="id_serial_number_invoice" value="0">
                <input type="hidden" name="modalRequest" id="modalRequest-serial_number_invoice" value="add">

                <input type="hidden" name="add_serial_number_title" id="add_serial_number_title" value="{{ __('invoice.add_serial_number') }}">
                <input type="hidden" name="update_serial_number_title" id="update_serial_number_title" value="{{ __('invoice.update_serial_number') }}">

                <div class="mb-4 row">
                    <label for="article_serial_number_invoice" class="col-sm-4 col-form-label">{{ __('invoice.article') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="article_serial_number_invoice" name="article_serial_number_invoice" readonly>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="quantity_serial_number_invoice" class="col-sm-4 col-form-label">{{ __('invoice.quantity') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="quantity_serial_number_invoice" name="quantity_serial_number_invoice" readonly>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="serial_number_invoice" class="col-sm-4 col-form-label">{{ __('invoice.serial_number') }}*</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text" id="serial_number_invoice-iteration">#-</span>
                            <input type="text" class="form-control" id="serial_number_invoice" name="serial_number_invoice" placeholder="{{ __('invoice.enter_a_serial_number') }}">
                        </div>
                        <small class="text-danger" id="serial_number_invoice-error"></small>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')

                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="insert-serial-number-invoice">
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
