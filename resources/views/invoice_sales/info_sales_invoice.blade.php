@extends('base')
@section('title', __('invoice.invoice_details'))
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

        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ __('invoice.invoice_details') }}</h3>
                    <p class="text-subtitle text-muted"></p> 
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{ route('app_sales_invoice', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.sales_invoice') }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">{{ __('invoice.invoice_details') }}</li>
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

                    <div class="border-bottom mb-4 fw-bold">
                        {{ __('invoice.customer') }}
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('client.reference') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->reference_cl }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('main.company') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->entreprise_name_cl }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('client.contact_name') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->contact_name_cl }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('main.phone_number') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->phone_number_cl }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('main.email_address') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->email_adress_cl }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('main.address') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $customer->address_cl }}
                        </div>
                    </div>

                    <div class="border-bottom mb-4 fw-bold">
                        {{ __('invoice.invoice_content') }}
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('client.reference') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ $invoice->reference_sales_invoice }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('invoice.date') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ date('Y-m-d', strtotime($invoice->created_at)) }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            {{ __('invoice.due_date') }}
                        </div>
                        <div class="col-md-8 text-primary fw-bold">
                            {{ date('Y-m-d', strtotime($invoice->due_date)) }}
                        </div>
                    </div>

                    <table class="table table-striped border">
                        <thead>
                            <tr>
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $invoice_element->description_inv_elmnt }}</td>
                                    <td class="text-end">{{ $invoice_element->quantity }}</td>
                                    <td class="text-end">{{ number_format($invoice_element->unit_price_inv_elmnt, 2, '.', ' ') }}</td>
                                    <td class="text-end">{{ number_format($invoice_element->total_price_inv_elmnt, 2, '.', ' ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <table class="table border table-striped mb-4">
                        <tbody>
                            <tr>
                                <td class="fw-bold">{{ __('invoice.total_excl_tax') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-end fw-bold">
                                    {{ number_format($tot_excl_tax, 2, '.', ' ') }}
                                </td>
                            </tr>

                            @if ($invoice->discount_choice != 0)
                                <tr>
                                    <td class="fw-bold">
                                        {{ __('invoice.discount') }}
                                        (-{{ $invoice->discount_value }} {{ $invoice->discount_type }})
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($invoice->discount_apply_amount, 2, '.', ' ') }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="fw-bold">{{ __('invoice.vat') }} {{ $invoice->vat }} %</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-end fw-bold">
                                    {{ number_format($invoice->vat_amount, 2, '.', ' ') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-end fw-bold">
                                    {{ number_format($invoice->total, 2, '.', ' ') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="border-bottom mb-4 fw-bold">
                        {{ __('invoice.payment_details') }}
                    </div>

                    <table class="table table-striped border mb-4">
                        <thead>
                            <th>Date</th>
                            <th>{{ __('payment_methods.collections') }}</th>
                            <th>{{ __('dashboard.payment_methods') }}</th>
                            <th>{{ __('client.manager') }}</th>
                            <th class="text-end">{{ __('dashboard.amount') }} {{ $deviseGest->iso_code }}</th>
                        </thead>
                        <tbody>
                            @foreach ($encaissements as $encaissement)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($encaissement->created_at)) }}</td>
                                <td>{{ __($encaissement->description) }}</td>
                                <td>
                                    @if ($encaissement->default == 1)
                                        {{ __('payment_methods.' . $encaissement->designation) }} ({{ $encaissement->iso_code }})
                                    @else
                                        {{ $encaissement->designation }} ({{ $encaissement->iso_code }})
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $user = DB::table('users')->where('id', $encaissement->id_user)->first();
                                    @endphp
                                    {{ $user->name }}
                                </td>
                                <td class="text-end">{{ number_format($encaissement->amount, 2, '.', ' ') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="fw-bold">{{ __('invoice.payment_received') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold text-end">{{ number_format($paymentReceived, 2, '.', ' ') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('invoice.remaining_balance') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold text-end">{{ number_format($remainingBalance, 2, '.', ' ') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            
                            @if ($paymentReceived == $invoice->total && $remainingBalance == 0 )
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="button" disabled>
                                        <i class="fa-solid fa-coins"></i>
                                        {{ __('invoice.cash_in') }}
                                    </button>
                                </div>
                            @else
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#cash-in">
                                        <i class="fa-solid fa-coins"></i>
                                        {{ __('invoice.cash_in') }}
                                    </button>
                                </div>
                            @endif
                            
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="d-grid gap-2">
                                <a class="btn btn-primary" role="button" href="/invoice_pdf/{{ $entreprise->id }}/{{ $functionalUnit->id }}/{{ $invoice->reference_sales_invoice }}" target="_blank">
                                    <i class="fa-solid fa-print"></i>
                                    {{ __('invoice.print') }}
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            @if ($paymentReceived != 0 )
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" type="button" disabled>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </button>
                                </div>
                            @else
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success" role="button" href="{{ route('app_add_new_sales_invoice', ['id' => $entreprise->id, 'id2' => $functionalUnit->id, 'ref_invoice' => $ref_invoice]) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-3 mb-3">
                            @if ($paymentReceived != 0 )
                                <div class="d-grid gap-2">
                                    <button class="btn btn-danger" type="button" disabled>
                                        <i class="fa-solid fa-trash-can"></i>
                                        {{ __('entreprise.delete') }}
                                    </button>
                                </div>
                            @else
                                <div class="d-grid gap-2">
                                    <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $invoice->reference_sales_invoice }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_sales_invoice') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                        {{ __('entreprise.delete') }}
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>


                </div>
            </div>
        </section>

        <div class="m-5">
            @include('menu.footer-global')
        </div>

    </div>
</div>

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
            <input type="hidden" name="ref_invoice" id="ref_invoice" value="{{ $ref_invoice }}">

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

@endsection