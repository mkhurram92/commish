<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Pipeline</title>
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .thead_style tr th {
            border-bottom: 1px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-weight: 800;
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
    </style>
</head>

<body style=" font-family: system-ui, system-ui, sans-serif;">
    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;">
                    <span style="font-size: 28px; font-weight: bold;">
                        Pipeline Report
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 16px; font-weight: bold;">
                    Report Period: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}
                </td>
            </tr>
            <tr>
                <td> <span style="width: 50%;font-weight: bold;">Grouped By : {{ $group_by }}</span></td>
            </tr>
        </tbody>
    </table>
    <?php $total_broker_est_loan_amt = 0;
    $total_broker_est_upfront = 0;
    $total_broker_est_brokerage = 0; ?>
    @if (count($deals) > 0)
        @foreach ($deals as $deal)
            <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 25%;background-color: #ffff99"> <span
                                style="font-weight: bold;"><?php
                                if ($group_by == 'Product') {
                                    echo $deal->product->name ?? '';
                                } elseif ($group_by == 'BrokerStaff') {
                                    echo ($deal->broker_staff->surname ?? '') . ', ' . ($deal->broker_staff->given_name ?? '');
                                } elseif ($group_by == 'Status') {
                                    echo $deal->deal_status->name;
                                } else {
                                    echo $deal->lender->name ?? '';
                                }
                                ?></span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <?php
            $deals_list = \App\Models\Deal::select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
            if ($group_by == 'Product') {
                $deals_list->where('product_id', $deal->product_id);
            } elseif ($group_by == 'BrokerStaff') {
                $deals_list->where('broker_staff_id', $deal->broker_staff_id);
            } elseif ($group_by == 'Status') {
                $deals_list->where('status', $deal->status);
            } else {
                $deals_list->where('lender_id', $deal->lender_id);
            }
            if (!empty($date_from)) {
                $deals_list->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')));
            }
            if (!empty($date_to)) {
                $deals_list->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($date_to . ' 23:59:59')));
            }
            $deals_list = $deals_list
                ->whereIn('status', [0, 1, 2, 3, 9, 11])
                ->orderby('status_date', 'asc')
                ->get();
            $actual_loan = 0;
            $broker_est_upfront = 0;
            $broker_est_brokerage = 0;
            ?>

            <table style="width: 100%;margin-top: 5px; text-align:center">
                <thead class="thead_style">
                    <tr style="background-color: #f2f2f2;">
                        <th style="width: 3%">Deal</th>
                        <th style="width: 19%">Client</th>
                        <th style="width: 7%">Lender</th>
                        <th style="width: 10%">Product</th>
                        <th style="width: 10%">Broker Staff</th>
                        <th style="width: 8%">Proposed Settlement</th>
                        <th style="width: 4%">Day</th>
                        <th style="width: 8%">Status</th>
                        <th style="width: 7%">Status Date</th>
                        <th style="width: 10%">Broker Est Loan Amount</th>
                        <th style="width: 7%">Broker Est Upfront</th>
                        <th style="width: 7%">Broker Est Brokerage</th>

                    </tr>
                </thead>
                <tbody class="body_class">
                    @foreach ($deals_list->sortBy(function ($client) {
                        if ($client->client) {
                            if ($client->client->individual == 1) {
                                return $client->client->surname;
                            } elseif ($client->client->individual == 2) {
                                return $client->client->trading;
                            }
                        }
                        return '';
                    }) as $deal_list)
                        <tr>
                            <td>{{ $deal_list->id }}</td>
                            <td>
                                @if ($deal_list->client)
                                    @if ($deal_list->client->individual == 1)
                                        {{ $deal_list->client->surname . ', ' . $deal_list->client->preferred_name }}
                                    @elseif ($deal_list->client->individual == 2)
                                        {{ $deal_list->client->trading }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $deal_list->lender->code }}</td>
                            <td>{{ $deal_list->product->name ?? $deal_list->product_id }}</td>
                            <td>{{ $deal_list->broker_staff ? $deal_list->broker_staff->surname . ', ' . $deal_list->broker_staff->given_name : '' }}
                            </td>
                            <td>{{ $deal_list->proposed_settlement != '' ? date('d/m/Y', strtotime($deal_list->proposed_settlement)) : '' }}
                            </td>
                            <td>
                                <?php
                                $earlier = new DateTime(date('Y-m-d', strtotime($deal_list->en_status_date)));
                                $later = new DateTime(date('Y-m-d', strtotime('now')));
                                
                                if ($deal_list->en_status_date != '') {
                                    echo $abs_diff = $later->diff($earlier)->format('%a');
                                } else {
                                    echo '0';
                                }
                                ?>

                            </td>
                            @if ($deal_list->deal_status->status_code < 10)
                                <td>{{ '0' . $deal_list->deal_status->status_code . ' ' . $deal_list->deal_status->name }}
                                </td>
                            @else
                                <td>{{ $deal_list->deal_status->status_code . ' ' . $deal_list->deal_status->name }}
                                </td>
                            @endif
                            <td>{{ $deal_list->status_date != '' ? date('d/m/Y', strtotime($deal_list->status_date)) : '' }}
                            </td>
                            <td>${{ number_format($deal_list->broker_est_loan_amt ?? '0', 2) }}</td>
                            <td>${{ number_format($deal_list->broker_est_upfront ?? '0', 2) }}</td>
                            <td>${{ number_format($deal_list->broker_est_brokerage ?? '0', 2) }}</td>
                        </tr>
                        <?php
                        $actual_loan += $deal_list->broker_est_loan_amt;
                        $broker_est_brokerage += $deal_list->broker_est_brokerage;
                        $broker_est_upfront += $deal_list->broker_est_upfront;
                        ?>
                    @endforeach
                </tbody>
                <thead class="subtotal">
                    <tr>
                        <th colspan="9"><?php
                        if ($group_by == 'Product') {
                            echo $deal->product->name . ' Subtotals';
                        } elseif ($group_by == 'BrokerStaff') {
                            echo ($deal->broker_staff->surname ?? '') . ', ' . ($deal->broker_staff->given_name ?? '') . ' Subtotals';
                        } elseif ($group_by == 'Status') {
                            echo $deal->deal_status->name . ' Subtotals';
                        } else {
                            echo $deal->lender->name . ' Subtotals';
                        }
                        ?></th>
                        <th style="text-align: left; width: 10%">${{ number_format($actual_loan, 2) }}</th>
                        <th style="text-align: left; width: 7%">${{ number_format($broker_est_upfront, 2) }}</th>
                        <th style="text-align: left; width: 7%">${{ number_format($broker_est_brokerage, 2) }}</th>

                    </tr>
                    <?php
                    $total_broker_est_loan_amt += $actual_loan;
                    $total_broker_est_brokerage += $broker_est_brokerage;
                    $total_broker_est_upfront += $broker_est_upfront;
                    ?>
                </thead>
            </table>
        @endforeach
    @else
        <table style="width: 100%;margin-top: 5px;">
            <thead class="thead_style">
                <tr style="background-color: #f2f2f2;">
                    <th style="width: 3%">Deal</th>
                    <th style="width: 19%">Client</th>
                    <th style="width: 7%">Lender</th>
                    <th style="width: 10%">Product</th>
                    <th style="width: 10%">Broker Staff</th>
                    <th style="width: 8%">Proposed Settlement</th>
                    <th style="width: 4%">Day</th>
                    <th style="width: 8%">Status</th>
                    <th style="width: 7%">Status Date</th>
                    <th style="width: 10%">Broker Est Loan Amount</th>
                    <th style="width: 7%">Broker Est Upfront</th>
                    <th style="width: 7%">Broker Est Brokerage</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif
    @if (count($deals) > 0)
        <table class="grand_total" style="width: 100%">
            <tr style="background-color: #f2f2f2;">
                <th colspan="9"><?php
                echo 'Grand Total';
                ?></th>
                <th style="text-align: left;width: 10%">${{ number_format($total_broker_est_loan_amt, 2) }}</th>
                <th style="text-align: left;width: 7%">${{ number_format($total_broker_est_upfront, 2) }}</th>
                <th style="text-align: left;width: 7%">${{ number_format($total_broker_est_brokerage, 2) }}</th>
            </tr>
        </table>
    @endif

</body>

</html>
