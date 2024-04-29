<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Broker Invoices</title>
</head>

<body style=" font-family: system-ui, sans-serif;">
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .thead_style tr th {
            border-bottom: 1px solid grey;
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-weight: 800;
            font-size: 12px;
        }

        .gross_tota_trail tr th {
            font-family: system-ui, sans-serif;
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
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 14px;
        }

        .body_class tr td {
            font-family: system-ui, sans-serif;
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

        th:nth-child(1),
        td:nth-child(1) {
            width: 25%;
            text-align: center;
        }

        th:nth-child(2),
        td:nth-child(2),
        th:nth-child(3),
        td:nth-child(3),
        th:nth-child(5),
        td:nth-child(5),
        th:nth-child(6),
        td:nth-child(6),
        th:nth-child(7),
        td:nth-child(7),
        th:nth-child(8),
        td:nth-child(8),
        th:nth-child(9),
        td:nth-child(9),
        th:nth-child(10),
        td:nth-child(10) {
            width: 10%;
            text-align: center;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 5%;
            text-align: center;
        }

        .pagebreak {
            clear: both;
            page-break-after: always;
        }

        html {
            height: 0;
        }
    </style>

    @if (count($brokers) > 0)
        @foreach ($brokers as $broker)
            <?php
            $grand_total_actual = 0;
            $grand_total_total = 0;
            $grand_total_agg = 0;
            $grand_total_referror = 0;
            $grand_total_broker = 0;
            ?>
            <table style="margin-top: 5px;margin-bottom:20px;width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 100%; text-align: center;">
                            <?php
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
                            <img src="{{ $imageData }}" style="width:600px;">
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
                        @if ($broker->deal->broker->is_individual == 1)
                            <th style="padding: 10px; text-align:center;">
                                {{ $broker->deal->broker->surname . ' ' . $broker->deal->broker->given_name }}</th>
                        @elseif($broker->deal->broker->is_individual == 2)
                            <th style="padding: 10px; text-align: center;">{{ $broker->deal->broker->trading ?? '' }}
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px; text-align: center; font-size: 12px;background-color: #f8f8f8;">
                            {{ $broker->deal->broker->business ?? '' }} {{ $broker->deal->broker->city ?? '' }}
                            {{ $broker->deal->broker->pincode ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; text-align: center; font-size: 11px;">ABN :
                            {{ $broker->deal->broker->abn ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table>
                <tr class="grand-total-row">
                    <td><b>For Commission due for the period : {{ date('d/m/Y', strtotime($date_from)) }} to
                            {{ date('d/m/Y', strtotime($date_to)) }}</b></td>
                </tr>
            </table>
            <?php
            $broker_staffs = $broker_staffs_array[$broker->deal->broker_id];
            ?>

            <table style="width: 100%; margin-top: 5px; border-top: 1px solid #ccc;">
                <thead class="thead_style">
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding:10px">Client</th>
                        <th style="padding:10px">Institution</th>
                        <th style="padding:10px">Loan Amount</th>
                        <th style="padding:10px">Deal Id</th>
                        <th style="padding:10px">Model</th>
                        <th style="padding:10px">%</th>
                        <th style="padding:10px">Amount</th>
                        <th style="padding:10px">FMA</th>
                        <th style="padding:10px">Referrer</th>
                        <th style="padding:10px">Broker</th>
                    </tr>
                </thead>

                <tbody class="body_class">
                    @php
                        $total_actual_loan = 0;
                        $total_agg_amount = 0;
                        $total_total_amount = 0;
                        $total_broker_amount = 0;
                        $total_referror_amount = 0;

                        $older_broker_name = null;
                    @endphp

                    @foreach ($broker_staffs as $index => $broker_staff_obj)
                        <?php
                        $type_name = DB::table('commission_types')
                            ->where('id', $broker_staff_obj->type)
                            ->value('name');
                        
                        $nameeee = $broker_staff_obj?->deal?->broker_staff ? $broker_staff_obj->deal->broker_staff->surname . ' ' . $broker_staff_obj->deal->broker_staff->given_name . ' - ' . $type_name : '';
                        
                        ?>
                        @if ($nameeee != $older_broker_name)
                            <!-- Code inside this block will be executed when $nameeee is different from $older_broker_name -->
                            @if ($older_broker_name !== null)
                                <tr style="background-color: #f2f2f2;">
                                    <td colspan="2" style="text-align: center"><b>Total</b></td>
                                    <td><b>${{ number_format((float) $total_actual_loan, 2) }}</b></td>
                                    <td colspan="3"></td>
                                    <td><b>${{ number_format((float) $total_total_amount, 2) }}</b></td>
                                    <td><b>${{ number_format((float) $total_agg_amount, 2) }}</b></td>
                                    <td><b>${{ number_format((float) $total_referror_amount, 2) }}</b></td>
                                    <td><b>${{ number_format((float) $total_broker_amount, 2) }}</b></td>
                                </tr>
                            @endif
                            @php
                                // Reset totals for the new broker
                                $total_actual_loan = 0;
                                $total_agg_amount = 0;
                                $total_total_amount = 0;
                                $total_broker_amount = 0;
                                $total_referror_amount = 0;

                                $older_broker_name = $nameeee;
                            @endphp
                            <tr style="border-bottom: 1px solid; padding-bottom: 5px;">
                                <td style="border-bottom: 1px solid; padding-bottom: 5px;" colspan="2"><b>Broker
                                        Staff</b></td>
                                <td style="border-bottom: 1px solid; padding-bottom: 5px;" colspan="8">
                                    <span style="background-color: #ffff99;">{{ $nameeee }}</span>
                                </td>
                            </tr>
                        @endif

                        <!-- Accumulate Totals for Each Broker Staff -->
                        @php
                            $total_actual_loan += $broker_staff_obj->deal->actual_loan;
                            $total_agg_amount += $broker_staff_obj->agg_amount;
                            $total_total_amount += $broker_staff_obj->total_amount;
                            $total_broker_amount += $broker_staff_obj->broker_amount;
                            $total_referror_amount += $broker_staff_obj->referror_amount;

                            $grand_total_actual += $broker_staff_obj->deal->actual_loan;
                            $grand_total_total += $broker_staff_obj->total_amount;
                            $grand_total_agg += $broker_staff_obj->agg_amount;
                            $grand_total_referror += $broker_staff_obj->referror_amount;
                            $grand_total_broker += $broker_staff_obj->broker_amount;
                        @endphp

                        <tr>
                            <td>
                                @if ($broker_staff_obj->deal->client->individual == 1)
                                    {{ $broker_staff_obj->deal->client ? $broker_staff_obj->deal->client->surname . ' ' . $broker_staff_obj->deal->client->preferred_name : '' }}
                                @elseif($broker_staff_obj->deal->client->individual == 2)
                                    {{ $broker_staff_obj->deal->client->trading }}
                                @endif
                            </td>
                            <td>{{ $broker_staff_obj->deal->lender->code ?? '' }}</td>
                            <td>${{ number_format($broker_staff_obj->deal->actual_loan, 2) }}</td>
                            <td>{{ $broker_staff_obj->deal->id }}</td>
                            <td>
                                @if ($broker_staff_obj->deal->commission_model == 1)
                                    Fixed Rate
                                @elseif($broker_staff_obj->deal->commission_model == 2)
                                    Flat Rate
                                @elseif($broker_staff_obj->deal->commission_model == 3)
                                    Variable Rate
                                @else
                                    <!-- Add a default value or leave it empty based on your requirement -->
                                @endif
                            </td>
                            <td>
                                ${{ number_format($broker_staff_obj->deal->broker_split_agg_brk_sp_trail, 2) }}
                            </td>
                            <td>${{ number_format($broker_staff_obj->total_amount, 2) }}</td>
                            <td>${{ number_format($broker_staff_obj->agg_amount, 2) }}</td>
                            <td>${{ number_format($broker_staff_obj->referror_amount, 2) }}</td>
                            <td>${{ number_format($broker_staff_obj->broker_amount, 2) }}</td>
                        </tr>
                        <?php
                        //$grand_total_actual += $total_actual_loan;
                        //$grand_total_total += $total_total_amount;
                        //$grand_total_agg += $total_agg_amount;
                        //$grand_total_referror += $total_referror_amount;
                        //$grand_total_broker += $total_broker_amount;
                        ?>
                    @endforeach

                    <!-- Display Totals for the last broker -->
                    <tr style="background-color: #f2f2f2;">
                        <td colspan="2" style="text-align: center;margin-top: 10px"><b>Total</b></td>
                        <td><b>${{ number_format((float) $total_actual_loan, 2) }}</b></td>
                        <td colspan="3"></td>
                        <td><b>${{ number_format((float) $total_total_amount, 2) }}</b></td>
                        <td><b>${{ number_format((float) $total_agg_amount, 2) }}</b></td>
                        <td><b>${{ number_format((float) $total_referror_amount, 2) }}</b></td>
                        <td><b>${{ number_format((float) $total_broker_amount, 2) }}</b></td>
                    </tr>

                    <!-- Grand Total Row -->
                    <tr style="background-color: #f2f2f2;">
                        <td colspan="2" style="text-align: center;margin-top: 10px"><b>Grand Total</b></td>
                        <td><b>${{ number_format($grand_total_actual, 2) }}</b></td>
                        <td colspan="3"></td>
                        <td><b>${{ number_format($grand_total_total, 2) }}</b></td>
                        <td><b>${{ number_format($grand_total_agg, 2) }}</b></td>
                        <td><b>${{ number_format($grand_total_referror, 2) }}</b></td>
                        <td><b>${{ number_format($grand_total_broker, 2) }}</b></td>
                    </tr>
                </tbody>
            </table>
            <div class="pagebreak"> </div>
        @endforeach
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th>Client</th>
                    <th>Institute</th>
                    <th>Loan Amount</th>
                    <th>Deal Id</th>
                    <th>Model</th>
                    <th>%</th>
                    <th>Amount</th>
                    <th>FMA</th>
                    <th>Broker</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif
</body>

</html>
