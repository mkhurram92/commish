<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Referror Invoices</title>
</head>

<body style="font-family: system-ui, sans-serif;">
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .thead_style tr th {
            border-bottom: 1px solid grey;
            font-family: system-ui, system-ui, Arial;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-weight: 800;
            font-size: 12px;
        }

        .gross_tota_trail tr th {
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 12px;
        }

        .subtotal tr th {
            border-top: 1px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 12px;
        }

        .grand_total tr th {
            border-top: 2px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 14px;
        }

        .body_class tr td {
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 12px;
        }

        .body_class tbody {
            border-collapse: separate;
            border-spacing: 5px 5px;
            border-bottom: 1px solid;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .pagebreak {
            clear: both;
            page-break-after: always;
        }
    </style>

    @php

        $grand_loanTotal = 0;
        $grand_commissionTotal = 0;
        $grand_aggTotal = 0;
        $grand_brokerTotal = 0;
        $grand_referrorTotal = 0;
    @endphp

    @if (count($distinctDisplayNames) > 0)
        @foreach ($distinctDisplayNames as $index => $dp)
            @php

                $filteredInvoices = $referrorInvoices->where('display_name', $dp->display_name);

                $loanTotal = 0;
                $commissionTotal = 0;
                $aggTotal = 0;
                $brokerTotal = 0;
                $referrorTotal = 0;

            @endphp

            @if (isset($filteredInvoices) && count($filteredInvoices) > 0)
                @foreach ($distinctCommissionTypeNames as $commissionType)
                    @php
                        $commissionTypeInvoices = $filteredInvoices->where('name', $commissionType->name);
                    @endphp
                    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 100%; text-align: center;">
                                    <?php
                                    // Fetching and embedding the logo
                                    $avatarUrl = url('assets/images/logo.jpeg');
                                    $arrContextOptions = [
                                        'ssl' => [
                                            'verify_peer' => false,
                                            'verify_peer_name' => false,
                                        ],
                                    ];
                                    $type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
                                    $avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
                                    $avatarBase64Data = base64_encode($avatarData);
                                    $imageData = 'data:image/' . $type . ';base64,' . $avatarBase64Data;
                                    ?>
                                    <img src="{{ $imageData }}" style="width: 600px;">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-weight: bold; padding-top: 5px;">
                                    Level 5, 333 King William Street, Adelaide SA 5000
                                    <br>
                                    ABN 32 513 077 269
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-weight: bold; padding-top: 5px;">
                                    Recipient Created: <span style="text-decoration: underline;">Tax Invoice</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table style="border-collapse: collapse; width: 33%; border: 1px solid #ddd;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 10px; text-align:center;">{{ $dp->display_name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td
                                    style="padding: 5px; text-align: center; font-size: 12px;background-color: #f8f8f8;">
                                    Address : </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px; text-align: center; font-size: 11px;">ABN : </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <tr>
                        <td><b>For Commission due for the period : {{ date('d/m/Y', strtotime($start_date)) }} to
                                {{ date('d/m/Y', strtotime($end_date)) }}</b></td>
                    </tr>
                    <br>
                    <table style="width: 100%;text-align: center;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th>Deal</th>
                                <th>Client</th>
                                <th>Institution</th>
                                <th>Loan Amount</th>
                                <th>Type</th>
                                <th>Gross Commission</th>
                                <th>AGG</th>
                                <th>ABP</th>
                                <th>Referror</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredInvoices as $invoice)
                                <tr>
                                    <td style="width: 4%;">{{ $invoice->deal_id }}</td>
                                    <td style="width: 30%;">
                                        {{ \Illuminate\Support\Str::limit($invoice->cs_all_contact_name, $limit = 27, $end = '...') }}
                                    </td>
                                    <td style="width: 10%;">{{ $invoice->lender_code }}</td>
                                    <td style="width: 11%;">${{ number_format($invoice->actual_loan, 2) }}</td>
                                    <td style="width: 5%;">{{ $invoice->name }}</td>
                                    <td style="width: 11%;">${{ number_format($invoice->gross_commission, 2) }}</td>
                                    <td style="width: 9%;">${{ number_format($invoice->agg_amount, 2) }}</td>
                                    <td style="width: 9%;">${{ number_format($invoice->broker_amount, 2) }}</td>
                                    <td style="width: 9%;">${{ number_format($invoice->referror_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @foreach ($filteredInvoices as $invoice)
                        @php
                            $loanTotal += $invoice->actual_loan;
                            $commissionTotal += $invoice->gross_commission;
                            $aggTotal += $invoice->agg_amount;
                            $brokerTotal += $invoice->broker_amount;
                            $referrorTotal += $invoice->referror_amount;

                            $grand_loanTotal += $invoice->actual_loan;
                            $grand_commissionTotal += $invoice->gross_commission;
                            $grand_aggTotal += $invoice->agg_amount;
                            $grand_brokerTotal += $invoice->broker_amount;
                            $grand_referrorTotal += $invoice->referror_amount;

                        @endphp
                    @endforeach

                    <table style="width: 100%;">
                        <tr></tr>
                        <tr style="background-color: #f2f2f2; text-align:center">
                            <td style="width: 4%;"></td>
                            <td style="width: 30%;"><b>Total</b></td>
                            <td style="width: 10%;"></td>
                            <td style="width: 11%;"><b>${{ number_format($loanTotal, 2) }}</b></td>
                            <td style="width: 5%;"></td>
                            <td style="width: 11%;"><b>${{ number_format($commissionTotal, 2) }}</b></td>
                            <td style="width: 9%;"><b>${{ number_format($aggTotal, 2) }}</b></td>
                            <td style="width: 9%;"><b>${{ number_format($brokerTotal, 2) }}</b></td>
                            <td style="width: 9%;"><b>${{ number_format($referrorTotal, 2) }}</b></td>
                        </tr>
                    </table>
                @endif
                <div class="pagebreak">
                </div>
            @endforeach
            <table style="width: 100%;">
                <thead>
                    <tr style="background-color: #f2f2f2;text-align:center;">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Loan Amount</th>
                        <th></th>
                        <th>Gross Commission</th>
                        <th>AGG</th>
                        <th>ABP</th>
                        <th>Referror</th>
                    </tr>
                </thead>
                <tr style="background-color: #f2f2f2; text-align:center;">
                    <td colspan=2 style="width: 20%;padding:10px;"><b>Grand Total</b></td>
                    <td style="width: 10%;padding:10px;"></td>
                    <td style="width: 21%;padding:10px;"><b>${{ number_format($grand_loanTotal, 2) }}</b></td>
                    <td colspan=2 style="width: 11%;padding:10px;">
                        <b>${{ number_format($grand_commissionTotal, 2) }}</b></td>
                    <td style="width: 9%;padding:10px;"><b>${{ number_format($grand_aggTotal, 2) }}</b></td>
                    <td style="width: 9%;padding:10px;"><b>${{ number_format($grand_brokerTotal, 2) }}</b></td>
                    <td style="width: 9%;padding:10px;"><b>${{ number_format($grand_referrorTotal, 2) }}</b></td>
                </tr>
            </table>
        @endif
</body>

</html>
