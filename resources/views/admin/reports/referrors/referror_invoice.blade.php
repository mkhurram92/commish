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

        .thead_style tr {
            border-bottom: 1px solid grey;
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-weight: 800;
        }

        .gross_tota_trail tr {
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
        }

        .subtotal tr {
            border-top: 1px solid grey;
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
        }

        .grand_total tr {
            border-top: 2px solid grey;
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
        }

        .body_class tr {
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
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

        th:nth-child(1) {
            width: 8%;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }

        th:nth-child(3),
        th:nth-child(4),
        th:nth-child(5),
        th:nth-child(6),
        th:nth-child(7),
        th:nth-child(8),
        th:nth-child(9) {
            width: 10%;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }

        th:nth-child(2) {
            width: 22%;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }

        td:nth-child(1),
        td:nth-child(2),
        td:nth-child(3),
        td:nth-child(4),
        td:nth-child(5),
        td:nth-child(6),
        td:nth-child(7),
        td:nth-child(8),
        td:nth-child(9) {
            width: 10%;
            text-align: center;
            font-size: 14px;
        }

        .pagebreak {
            clear: both;
            page-break-after: always;
        }

        html {
            height: 0;
        }

        .grand-total-row {
            font-size: 14px;
        }
    </style>
    @php
        $grand_loanTotal = $grand_commissionTotal = $grand_aggTotal = $grand_brokerTotal = $grand_referrorTotal = 0;
    @endphp

    @if (count($distinctDisplayNames) > 0)
        @foreach ($distinctDisplayNames as $index => $dp)
            @php
                $filteredInvoices = $referrorInvoices->where('display_name', $dp->display_name);
                $grand_displayName_loanTotal = $grand_displayName_commissionTotal = $grand_displayName_aggTotal = $grand_displayName_brokerTotal = $grand_displayName_referrorTotal = 0;
            @endphp

            @if (isset($filteredInvoices) && count($filteredInvoices) > 0)
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
                            <td style="text-align: center; font-weight: bold; padding-top: 5px; font-size:16px;">
                                Level 5, 333 King William Street, Adelaide SA 5000
                                <br>
                                <?php
                                $abn = App\Models\Entity::first();
                                $abn_number = $abn->abn;
                                ?>
                                ABN {{ $abn_number }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; font-weight: bold; padding-top: 5px; font-size:14px;">
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
                            <td style="padding: 5px; text-align: center; font-size: 12px;background-color: #f8f8f8;">
                                {{ $dp->cd_unit ?? 'N/A' }} {{ $dp->cd_street_name }}
                                {{ $dp->cd_city }} {{ $dp->cd_state }} {{ $dp->cd_pincode }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; text-align: center; font-size: 11px;">ABN : </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tr class="grand-total-row">
                        <td><b>For Commission due for the period : {{ date('d/m/Y', strtotime($start_date)) }} to
                                {{ date('d/m/Y', strtotime($end_date)) }}</b></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 5px; border-top: 1px solid #ccc;">
                    <tbody>
                        @foreach ($distinctCommissionTypeNames as $commissionType)
                            @php
                                $commissionTypeInvoices = $filteredInvoices->where('name', $commissionType->name);
                                $loanTotal = $commissionTotal = $aggTotal = $brokerTotal = $referrorTotal = 0;
                            @endphp

                            @if (count($commissionTypeInvoices) > 0)
                                <table style="width: 100%; margin-top: 5px;">
                                    <thead class="thead_style">
                                        <tr style="background-color: #f2f2f2;">
                                            <th>Deal</th>
                                            <th>Client</th>
                                            <th>Institution</th>
                                            <th>Loan Amount</th>
                                            <th>Type</th>
                                            <th>Gross Commission</th>
                                            <th>AGG</th>
                                            <th>ABP</th>
                                            <th>Referror
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($commissionTypeInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->deal_id }}</td>
                                                <td style="white-space: nowrap;">
                                                    {{ \Illuminate\Support\Str::limit($invoice->cs_all_contact_name, $limit = 27, $end = '...') }}
                                                </td>
                                                <td>{{ $invoice->lender_code }}</td>
                                                <td>${{ number_format($invoice->actual_loan, 2) }}
                                                </td>
                                                <td>{{ $invoice->name }}</td>
                                                <td>
                                                    ${{ number_format($invoice->gross_commission, 2) }}</td>
                                                <td>${{ number_format($invoice->agg_amount, 2) }}
                                                </td>
                                                <td>${{ number_format($invoice->broker_amount, 2) }}
                                                </td>
                                                <td>
                                                    ${{ number_format($invoice->referror_amount, 2) }}</td>
                                            </tr>
                                            @php
                                                // Accumulate totals within the innermost loop
                                                $loanTotal += $invoice->actual_loan;
                                                $commissionTotal += $invoice->gross_commission;
                                                $aggTotal += $invoice->agg_amount;
                                                $brokerTotal += $invoice->broker_amount;
                                                $referrorTotal += $invoice->referror_amount;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <!-- Display totals after the inner loop -->
                                    <tr class="grand-total-row" style="background-color: #f2f2f2;">
                                        <td style="padding: 5px;" colspan="3"><b>Total
                                                {{ $commissionType->name }}</b></td>
                                        <td style="padding: 5px;">
                                            <b>${{ number_format($loanTotal, 2) }}</b>
                                        </td>
                                        <td style="padding: 5px;"></td>
                                        <td style="padding: 5px;">
                                            <b>${{ number_format($commissionTotal, 2) }}</b>
                                        </td>
                                        <td style="padding: 5px;">
                                            <b>${{ number_format($aggTotal, 2) }}</b>
                                        </td>
                                        <td style="padding: 5px;">
                                            <b>${{ number_format($brokerTotal, 2) }}</b>
                                        </td>
                                        <td style="padding: 5px;">
                                            <b>${{ number_format($referrorTotal, 2) }}</b>
                                        </td>
                                    </tr>


                                    <!-- Accumulate grand totals for the display name -->
                                    @php
                                        $grand_displayName_loanTotal += $loanTotal;
                                        $grand_displayName_commissionTotal += $commissionTotal;
                                        $grand_displayName_aggTotal += $aggTotal;
                                        $grand_displayName_brokerTotal += $brokerTotal;
                                        $grand_displayName_referrorTotal += $referrorTotal;
                                    @endphp
                            @endif
                        @endforeach
                        <tr class="grand-total-row" style="background-color: #f2f2f2;">
                            <td style="padding: 5px;" colspan="3"><b>Total Commission</b></td>
                            <td style="padding: 5px;">
                                <b>${{ number_format($grand_displayName_loanTotal, 2) }}</b>
                            </td>
                            <td style="padding: 5px;"></td>
                            <td style="padding: 5px;">
                                <b>${{ number_format($grand_displayName_commissionTotal, 2) }}</b>
                            </td>
                            <td style="padding: 5px;">
                                <b>${{ number_format($grand_displayName_aggTotal, 2) }}</b>
                            </td>
                            <td style="padding: 5px;">
                                <b>${{ number_format($grand_displayName_brokerTotal, 2) }}</b>
                            </td>
                            <td style="padding: 5px;">
                                <b>${{ number_format($grand_displayName_referrorTotal, 2) }}</b>
                            </td>

                        </tr>
                </table>
                </tbody>
                </table>

                <table
                    style="page-break-inside: avoid;border-collapse: collapse; width: 30%; margin-left: auto; margin-right: 0;">
                    <tbody>
                        <tr class="grand-total-row">
                            <td style="padding: 8px;background-color: #f8f8f8;"><b>GST</b>
                            </td>
                            <td style="padding: 8px; text-align: right;background-color: #f8f8f8;">
                                <b>${{ number_format($grand_displayName_referrorTotal * 0.1, 2) }} </b>
                            </td>
                        </tr>
                        <tr class="grand-total-row">
                            <td style="padding: 8px;background-color: #f8f8f8;"><b>Total Due</b>
                            </td>
                            <td style="padding: 8px; text-align: right; background-color: #f8f8f8;">
                                <b>${{ number_format($grand_displayName_referrorTotal + $grand_displayName_referrorTotal * 0.1, 2) }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                $entity = App\Models\Entity::first();
                $entity_name = $entity->contact_person;
                ?>
                <table style="page-break-inside: avoid;">
                    <tr class="grand-total-row">
                        <td style="padding: 8px;background-color: #f8f8f8;"><b>Please contact {{ $entity_name }} in
                                regard to any queries you may concering this invoice.</b>
                        </td>
                    </tr>
                </table>
            @endif
            <div class="pagebreak"></div>
        @endforeach
    @endif
</body>

</html>
