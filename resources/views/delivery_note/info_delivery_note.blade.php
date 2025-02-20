@extends('base')
@section('title', __('invoice.delivery_note_details'))
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
                    <h3>{{ __('invoice.delivery_note_details') }}</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{ route('app_delivery_note', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.delivery_note') }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">{{ __('invoice.delivery_note_details') }}</li>
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
                            {{ $contact->fullname_cl }}
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
                            {{ $invoice->reference_personalized }}
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

                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th class="text-start">REF/SKU</th>
                                <th scope="col">{{ __('article.description') }}</th>
                                <th scope="col">{{ __('invoice.serial_number')}}</th>
                                <th scope="col" class="text-end">{{ __('invoice.quantity') }}</th>
                              </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice_elements as $invoice_element)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $invoice_element->custom_reference }}</td>
                                    <td>{{ $invoice_element->description_inv_elmnt }}</td>
                                    <td>
                                        @php
                                            $serial_numbers = DB::table('serial_number_invoices')->where('id_invoice_element', $invoice_element->id)->get();
                                        @endphp
                                        <ul class="list-group mb-2">
                                            @foreach ($serial_numbers as $serial_number)
                                                <li class="list-group-item">
                                                    {{ $loop->iteration }}- {{ $serial_number->description }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end">{{ $invoice_element->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Note --}}
                    @include('invoice_sales.commun.note_document')

                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-primary" role="button" href="/delivery_note_pdf/{{ $entreprise->id }}/{{ $functionalUnit->id }}/{{ $invoice->reference_sales_invoice }}" target="_blank">
                                        <i class="fa-solid fa-print"></i>
                                        {{ __('invoice.print') }}
                                    </a>
                                </div>
                            </div>

                            @if (Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin" || Auth::user()->id == $invoice->id_user)
                                <div class="col-md-4 mb-3">
                                    @if ($paymentReceived != 0 )
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success" type="button" disabled>
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('entreprise.edit') }}
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-grid gap-2">
                                            <a class="btn btn-success" role="button" href="{{ route('app_add_new_delivery_note', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id, 'ref_invoice' => $ref_invoice]) }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('entreprise.edit') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
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
                            @endif
                        </div>
                    @endif


                </div>
            </div>
        </section>

        <div class="m-5">
            @include('menu.footer-global')
        </div>

    </div>

</div>

@endsection
