<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $invoice->reference_sales_invoice }}</title>
</head>
<body>
    <style>
        
        .small-text {
            font-size: 13px;
        }

        .small-text-sm {
            font-size: 10px;
        }

        .invoice-title {
            color: #0d3d9e;
            border-bottom: 2px solid #0d3d9e;
        }

        .border-bottom-1 {
            border-bottom: 2px solid #bfcce6;
        }

        .border-bottom-2 {
            border-bottom: 2px solid #0d3d9e;
        }

        .box div {
            display: inline-block;
        }

        .box .key {
            width: 150px;
        }

        .box .right {
            position: absolute;
            right: 0;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #bfcce6;
        }
        
        .table th, td {
            border: 1px solid #bfcce6;
            padding: 5px;
        }

        .table th {
            background-color: #2652a8;
            color: #fff;
        }

        .table tr:nth-child(even) {
            background-color: #99add6;
        }

        .mb-3 {
            margin-bottom: 30px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 2cm;

            border-top: 2px solid #0d3d9e;
        }

        .citation {
            color: #0d3d9e;
            font-style: italic;
        }
    </style>

    <div class="header">
        @if ($logo != 0 || $logo != '0')
        <img src="{{ $logo }}" alt="" width="150">
        @else
            <h1>LOGO</h1>
        @endif
    </div>
    <div>
        <h2 class="invoice-title">
            @if ($invoice->is_proforma_inv == 0)
                {{ __('invoice.invoice_MAJ') }} N° {{ $invoice->reference_sales_invoice }}
            @else
                {{ __('invoice.proforma_invoice_MAJ') }} N° {{ $invoice->reference_sales_invoice }}
            @endif
        </h2>
    </div>

    <div class="box small-text">
        <div class="key">Date : </div>
        <div class="content">{{ date('Y-m-d', strtotime($invoice->created_at)) }}</div>
        <div class="right">{{ __('invoice.customer') }} : </div>
    </div>

    <div class="box small-text">
        <div class="key">{{ __('invoice.due_date') }} : </div>
        <div class="content">{{ date('Y-m-d', strtotime($invoice->due_date)) }}</div>
        <div class="right">{{ __('client.reference') }} {{ $customer->reference_cl }}</div>
    </div>

    <div class="box small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right">
            @if ($customer->entreprise_name_cl == "-" || $customer->entreprise_name_cl == "")
                {{ $customer->contact_name_cl }}
            @else
                {{ $customer->entreprise_name_cl }}
            @endif
        </div>
    </div>

    <div class="box mb-3 small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right">{{ $customer->address_cl }}</div>
    </div>

    <table class="table mb-3 small-text">
        <thead>
            <tr>
                <th class="text-start">#</th>
                <th class="text-start">{{ __('article.description') }}</th>
                <th class="text-center">{{ __('invoice.quantity') }}</th>
                <th class="text-end">{{ __('article.unit_price') }} {{ $deviseGest->iso_code }}</th>
                <th class="text-end">{{ __('invoice.total_price') }} {{ $deviseGest->iso_code }}</th>
              </tr>
        </thead>
        <tbody>
            @foreach ($invoice_elements as $invoice_element)
                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td class="text-start">{{ $invoice_element->description_inv_elmnt }}</td>
                    <td class="text-center">{{ $invoice_element->quantity }}</td>
                    <td class="text-end">{{ number_format($invoice_element->unit_price_inv_elmnt, 2, '.', ' ') }}</td>
                    <td class="text-end">{{ number_format($invoice_element->total_price_inv_elmnt, 2, '.', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="box small-text">
        <div class="key fw-bold">{{ __('invoice.total_excl_tax') }} {{ $deviseGest->iso_code }}</div>
        <div class="content"></div>
        <div class="right fw-bold">{{ number_format($tot_excl_tax, 2, '.', ' ') }}</div>
    </div>

    @if ($invoice->discount_choice != 0)
        <div class="box small-text">
            <div class="key fw-bold">{{ __('invoice.discount') }}
                (-{{ $invoice->discount_value }} {{ $invoice->discount_type }})
            </div>
            <div class="content"></div>
            <div class="right fw-bold">{{ number_format($invoice->discount_apply_amount, 2, '.', ' ') }}</div>
        </div>
    @endif

    <div class="box border-bottom-1 mb-3 small-text">
        <div class="key fw-bold">{{ __('invoice.vat') }} {{ $invoice->vat }} %</div>
        <div class="content"></div>
        <div class="right fw-bold">{{ number_format($invoice->vat_amount, 2, '.', ' ') }}</div>
    </div>

    <div class="box border-bottom-2 mb-3 small-text">
        <div class="key fw-bold">{{ __('invoice.total_incl_tax') }} {{ $deviseGest->iso_code }}</div>
        <div class="content"></div>
        <div class="right fw-bold">{{ number_format($invoice->total, 2, '.', ' ') }}</div>
    </div>

    @php
        $user = DB::table('countries')
                ->join('users', 'users.id_country', '=', 'countries.id')
                ->where('users.id', $invoice->id_user)->first();
        $grade = DB::table('grades')->where('id', $user->grade_id)->first();
        $country = DB::table('countries')->where('id', $user->id_country)->first();
    @endphp

    <div class="box small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right">{{ $user->name }}</div>
    </div>

    <div class="box small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right fw-bold">
            {{ $grade->name }}
        </div>
    </div>

    <div class="box small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right">
            +{{ $country->telephone_code }} {{ $user->phone_number }}
        </div>
    </div>

    <div class="box small-text">
        <div class="key"></div>
        <div class="content"></div>
        <div class="right">
            {{ $user->email }}
        </div>
    </div>

    <div class="footer small-text-sm">
        @php
            $phones = DB::table('functional_unit_phones')->where('id_func_unit', $functionalUnit->id)->get();
            $emails = DB::table('functionalunit_emails')->where('id_func_unit', $functionalUnit->id)->get();
            $bankAc = DB::table('bank_accounts')->where('id_entreprise', $entreprise->id)->get();
        @endphp
        <div class="fw-bold">
            {{ $entreprise->name }} {{ $entreprise->slogan }}
        </div>
        <div>RCCM : {{ $entreprise->rccm }} - IDNAT : {{ $entreprise->id_nat }} - IDNAT : {{ $entreprise->nif }}</div>
        <div>Contact : 
            @foreach ($phones as $phone)
                {{ '+' . $country->telephone_code }}
                {{ chunk_split($phone->phone_number, 3, ' ') }}
            @endforeach
        </div>
        <div>Email :
            @foreach ($emails as $email)
                {{ $email->email }}
            @endforeach
            - Web : <a href="https://{{ $entreprise->website }}" target="_blank">{{ $entreprise->website }}</a>
        </div>
        <div>
            {{ __('main.address') }} : {{ $functionalUnit->address }} - 
            {{ Config::get('app.locale') == 'en' ? $user->name_gb : $user->name_fr }}
        </div>
        <div>
            @foreach ($bankAc as $bank)
                @php
                    $devise = DB::table('devises')->where('id', $bank->id_devise)->first();
                @endphp
                <div>
                    {{ $bank->bank_name }} /
                    {{ $bank->account_number }} - 
                    {{ $devise->iso_code }} - 
                    {{ $bank->account_title }}
                </div>
            @endforeach
        </div>

        <div class="citation">
            {{ __('invoice.invoice_generated_by_EXADERP') }}
        </div>
        
    </div>
    
</body>
</html>