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
                        <input type="text" name="invoice_concern_sales" id="invoice_concern_sales" class="form-control @error('invoice_concern_sales') is-invalid @enderror" placeholder="{{ __('invoice.enter_the_invoice_subject') }}" value="@if(Session::has('invoice_concern_sales')){{ Session::get('invoice_concern_sales') }}@endif">
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
                                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" onclick="updateArticleInvoice('{{ $invoice_element->id }}', 'article');" data-bs-target="#update_article_invoice">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" onclick="updateArticleInvoice('{{ $invoice_element->id }}', 'service');" data-bs-target="#update_service_invoice">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice_element->custom_reference }}</td>
                                <td>{{ $invoice_element->description_inv_elmnt }}</td>
                                <td class="text-end">{{ $invoice_element->quantity }}</td>
                                <td class="text-end">{{ number_format($invoice_element->unit_price_inv_elmnt, 2, '.', ' ') }}</td>
                                <td class="text-end">{{ number_format($invoice_element->total_price_inv_elmnt, 2, '.', ' ') }}</td>
                            </tr>
                            <div>
                                <input type="hidden" id="ref_article-{{ $invoice_element->id }}" value="{{ $invoice_element->id }}">
                                <input type="hidden" id="custom_reference-{{ $invoice_element->id }}" value="{{ $invoice_element->custom_reference }}">
                                <input type="hidden" id="description_inv_elmnt-{{ $invoice_element->id }}" value="{{ $invoice_element->description_inv_elmnt }}">
                                <input type="hidden" id="quantity-{{ $invoice_element->id }}" value="{{ $invoice_element->quantity }}">
                                <input type="hidden" id="margin-{{ $invoice_element->id }}" value="{{ $invoice_element->margin }}">
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
                    <label for="discount-yes" class="col-sm-4 col-form-label">{{ __('invoice.apply_discount') }}</label>
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
                    <label for="discount-pourcentage" class="col-sm-4 col-form-label">{{ __('invoice.discount') }}</label>
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
                    <label for="discount_value" class="col-sm-4 col-form-label">{{ __('invoice.discount_value') }}</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount_value" onkeyup="changeDiscountValue('{{ route('app_change_discount_customer') }}', '{{ csrf_token() }}');" onchange="changeDiscountValue('{{ route('app_change_discount_customer') }}', '{{ csrf_token() }}');" name="discount_value" value="0">
                            <span class="input-group-text" id="discount-type-label">%</span>
                        </div>
                    </div>
                </div>


                <div class="mb-4 row">
                    <label for="vat-apply-change" class="col-sm-4 col-form-label">{{ __('invoice.vat') }}</label>
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
                        <input type="date" class="form-control" id="date_sales_invoice" name="date_sales_invoice"  value="@if(Session::has('date_sales_invoice')){{ Session::get('date_sales_invoice') }}@else{{ date('Y-m-d') }}@endif">
                    </div>
                </div>

                {{--
                <div class="mb-4 row">
                    <label for="due_date_sales_invoice" class="col-sm-4 col-form-label">{{ __('invoice.due_date') }}*</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="due_date_sales_invoice" name="due_date_sales_invoice"  value="@if(Session::has('due_date_sales_invoice')){{ Session::get('due_date_sales_invoice') }}@else{{ date('Y-m-d') }}@endif">
                    </div>
                </div>
                --}}

                @if (Request::route()->getName() == "app_add_new_proforma")
                    <div class="mb-4 row">
                        <label for="validity_of_the_offer" class="col-sm-4 col-form-label">{{ __('invoice.validity_of_the_offer') }}*</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control text-end" id="validity_of_the_offer" name="validity_of_the_offer" min="15" max="90"  value="{{ $invoice ? $invoice->validity_of_the_offer_day : "15" }}">
                                <span class="input-group-text" id="basic-addon2">{{ __('invoice.days') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="payment_terms" class="col-sm-4 col-form-label">{{ __('invoice.payment_terms') }}*</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <select class="form-select" name="payment_terms" id="payment_terms" onclick="payment_terms_select();">
                                    @php
                                        $payment_terms = DB::table('payment_terms_proformas')->get();
                                    @endphp

                                    @if ($payment_terms_proforma)
                                        <option value="{{ $payment_terms_proforma->description }}" selected>{{ __('invoice.' . $payment_terms_proforma->description) }}</option>
                                    @endif

                                    @foreach ($payment_terms as $payment_term)
                                        <option value="{{ $payment_term->description }}">{{ __('invoice.' . $payment_term->description) }}</option>
                                    @endforeach
                                </select>

                                <input type="number" class="form-control text-end" id="payment_terms_percentage" name="payment_terms_percentage" min="1" max="100"  value="{{ $payment_terms_assign ? $payment_terms_assign->purcentage : "10" }}">
                                <span class="input-group-text" id="basic-addon2">%</span>

                                @if ($payment_terms_proforma)
                                    <input type="number" class="form-control text-end @if($payment_terms_proforma->description == "to_order") d-none @endif after_delivery_zone" id="after_delivery_days" name="after_delivery_days" min="15" max="90"  value="{{ $payment_terms_assign ? $payment_terms_assign->day_number : "15" }}">
                                    <span class="input-group-text @if($payment_terms_proforma->description == "to_order") d-none @endif after_delivery_zone" id="basic-addon2">{{ __('invoice.days') }}</span>
                                @else
                                    <input type="number" class="form-control text-end d-none after_delivery_zone" id="after_delivery_days" name="after_delivery_days" min="15" max="90"  value="{{ $payment_terms_assign ? $payment_terms_assign->day_number : "15" }}">
                                    <span class="input-group-text d-none after_delivery_zone" id="basic-addon2">{{ __('invoice.days') }}</span>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif

                @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                    {{-- button de sauvegarde --}}
                    <div class="mb-4">
                        <div class="col-sm-4">
                            @include('button.save-button')
                        </div>
                    </div>
                @endif

            </form>
        </div>
    </div>
</section>

@include('invoice_sales.operation.add_article_modal')
@include('invoice_sales.operation.update_article_modal')

@include('invoice_sales.operation.add_service_modal')
@include('invoice_sales.operation.update_service_modal')

<form action="#">
    <input type="hidden" id="article_sales_invoice-message" value="{{ __('invoice.select_an_article_please') }}">
    <input type="hidden" id="article_qty_invoice-message" value="{{ __('invoice.quantity_cannot_be_empty') }}">
    <input type="hidden" id="article_margin_invoice-message" value="{{ __('invoice.margin_cannot_be_empty') }}">
    <input type="hidden" id="service_sales_invoice-message" value="{{ __('invoice.select_a_service_please') }}">
</form>
