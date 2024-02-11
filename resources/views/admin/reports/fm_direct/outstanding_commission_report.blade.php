<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Commission Outstanding</title>

</head>

<body style=" font-family: system-ui, system-ui, sans-serif;">
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
            font-size: 14px;
            letter-spacing: 1px;
        }

        .subtotal tr th {
            border-top: 1px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .grand_total tr th {
            border-top: 2px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .body_class tr td {
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 13px;
            letter-spacing: 1px;
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

    <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
        <tbody>
            <tr>
                <td style="width: 30%"> <span style="font-size: 18px;font-weight: bold;">FM Direct Commission
                        Outstanding</span></td>
                <td> <span style="width: 60%;">Grouped By {{ $group_by }} For period from
                        {{ $date_from . ' to ' . $date_to }}</span></td>
            </tr>
        </tbody>
    </table>
    <?php
    $total_actual_loan_amt = 0;
    $total_ABP_est_upfront_amt = 0;
    $total_ABP_actual_upfront_amt = 0;
    $total_ABP_upfront_diff = 0;
    $total_ABP_est_brokerage = 0;
    $total_ABP_actual_brokerage = 0;
    $total_brokerage_dif = 0;
    $total_est_trail = 0;
    $total_actual_trail = 0;
    $total_tra_dif = 0;
    ?>
    @if (count($deals) > 0)
        <?php
        // $deals_list = \App\Models\Deal::with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
        //     ->whereStatus(4)
        //     ->where(function ($query) {
        //         $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
        //     })
        //     ->whereHas('deal_commission', function ($query) {
        //         $query
        //             ->selectRaw('deal_commissions.deal_id, SUM(deal_commissions.broker_amount) as total_broker_amount')
        //             ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
        //             ->where('deal_commissions.type', 13)
        //             ->groupBy('deal_commissions.deal_id')
        //             ->havingRaw('deals.broker_est_upfront - total_broker_amount > 0')
        //             ->orHavingRaw('deals.broker_est_brokerage - deals.agg_est_brokerage > 0');
        //     })
        //     ->when(!empty($date_from), function ($q) use ($date_from) {
        //         $q->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($date_from)));
        //     })
        //     ->when(!empty($date_to), function ($q) use ($date_to) {
        //         $q->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($date_to)));
        //     })
        //     ->with('deal_commission');
        // // if ($group_by == 'Product') {
        // //     $deals_list->where('product_id', $deal->product_id);
        // // } elseif ($group_by == 'BrokerStaff') {
        // //     $deals_list->where('broker_staff_id', $deal->broker_staff_id);
        // // } elseif ($group_by == 'Status') {
        // //     $deals_list->where('status', $deal->status);
        // // } else {
        // //     $deals_list->where('lender_id', $deal->lender_id);
        // // }
        $actual_loan_amt = 0;
        $ABP_est_upfront_amt = 0;
        $ABP_actual_upfront_amt = 0;
        $ABP_upfront_diff = 0;
        $ABP_est_brokerage = 0;
        $ABP_actual_brokerage = 0;
        $brokerage_dif = 0;
        $est_trail = 0;
        $actual_trail = 0;
        $tra_dif = 0;
        ?>
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th>Deal</th>
                    <th style="padding-left:12px;">Client</th>
                    <th>Lender</th>
                    <th>Status</th>
                    <th>Broker <br>Staff</th>
                    <th style="padding-left:12px;">Loan <br>Amount</th>
                    <th>ABP Est. <br>Upfront</th>
                    <th>Actual <br>Upfront</th>
                    <th>Upfront <br>Difference</th>
                    <th>ABP Est. <br>Brokerage</th>
                    <th>Actual <br>Brokerage</th>
                    <th>Brokerage <br>Difference</th>
                </tr>
            </thead>
            <tbody class="body_class">
                @foreach ($deals as $k => $deal_list)
                    @if ($k == 0)
                        <tr>
                            <td colspan="12" style="width: 25%;background-color: #ffff99"> <span
                                    style="font-weight: bold;"><?php
                                    if ($group_by == 'Product') {
                                        echo $deal_list->product->name ?? '';
                                    } elseif ($group_by == 'BrokerStaff') {
                                        echo $deal_list->broker_staff->surname ?? '';
                                    } elseif ($group_by == 'Status') {
                                        echo $deal_list->deal_status->name;
                                    } else {
                                        echo $deal_list->lender->name ?? '';
                                    }
                                    ?></span></td>
                            <td></td>
                        </tr>
                    @endif
                    <?php
                    
                    $upfront = \App\Models\DealCommission::whereDealId($deal_list->id)
                        ->where('type', 13)
                        ->sum('broker_amount');
                    $brokerrage = \App\Models\DealCommission::whereDealId($deal_list->id)
                        ->where('type', 4)
                        ->sum('broker_amount');
                    
                    ?>
                    <tr>
                        <td>{{ $deal_list->id }}</td>
                        <td style="padding-left:12px;">
                            {{ $deal_list->client ? ($deal_list->client->given_name != '' ? $deal_list->client->given_name : $deal_list->client->preferred_name) . ' ' . $deal_list->client->surname : '' }}
                        </td>
                        <td>{{ $deal_list->lender->code }}</td>
                        <td>{{ $deal_list->deal_status->name }}</td>
                        <td>{{ $deal_list->broker_staff ? $deal_list->broker_staff->given_name . ' ' . $deal_list->broker_staff->surname : ' ' }}
                        </td>
                        <td style="padding-left:12px;">{{ number_format($deal_list->actual_loan, 2) }}</td>
                        <td>{{ $deal_list->broker_est_upfront }}</td>
                        <td>{{ number_format($upfront, 2) }}</td>

                        <td>{{ $up_diff = $deal_list->broker_est_upfront - $upfront }}</td>
                        <td>{{ number_format($deal_list->broker_est_brokerage ?? 0, 2) }}</td>
                        <td>{{ number_format($brokerrage ?? 0, 2) }}</td>
                        <td>{{ number_format($br_dif = $deal_list->broker_est_brokerage - $brokerrage, 2) }}
                        </td>
                    </tr>

                    <?php
                    $actual_loan_amt += $deal_list->actual_loan;
                    $ABP_est_upfront_amt += $deal_list->broker_est_upfront;
                    $ABP_actual_upfront_amt += $upfront;
                    $ABP_upfront_diff += $up_diff;
                    $ABP_est_brokerage += $deal_list->broker_est_brokerage;
                    $ABP_actual_brokerage += $brokerrage;
                    $ABP_actual_brokerage += $brokerrage;
                    $ABP_actual_brokerage += $brokerrage;
                    $brokerage_dif += $br_dif;
                    ?>
                @endforeach
            </tbody>

            <thead class="subtotal">
                <tr>
                    <th colspan="2"><?php
                    if ($group_by == 'BrokerStaff') {
                        echo $deal->broker_staff->surname ?? '' . ' Subtotals';
                    } elseif ($group_by == 'Status') {
                        echo $deal->deal_status->name . ' Subtotals';
                    } else {
                        echo $deal->lender->name . ' Subtotals';
                    }
                    ?>
                    </th>
                    <th style="text-align: left;width: 7%"></th>
                    <th colspan="2">{{ 'Rows: ' . count($deals) }}</th>
                    <th style="text-align: left;width: 7%">${{ $actual_loan_amt }}</th>
                    <th style="text-align: left;width: 7%">${{ $ABP_est_upfront_amt }}</th>
                    <th style="text-align: left;width: 7%">${{ $ABP_actual_upfront_amt }}</th>
                    <th style="text-align: left;width: 7%">${{ $ABP_upfront_diff }}</th>
                    <th style="text-align: left;width: 7%">${{ $ABP_est_brokerage }}</th>
                    <th style="text-align: left;width: 7%">${{ $ABP_actual_brokerage }}</th>
                    <th style="text-align: left;width: 7%">${{ $brokerage_dif }}</th>
                </tr>
                <?php
                $total_actual_loan_amt += $actual_loan_amt;
                $total_ABP_est_upfront_amt += $ABP_est_upfront_amt;
                $total_ABP_actual_upfront_amt += $ABP_actual_upfront_amt;
                $total_ABP_upfront_diff += $ABP_upfront_diff;
                $total_ABP_est_brokerage += $ABP_est_brokerage;
                $total_ABP_actual_brokerage += $ABP_actual_brokerage;
                $total_brokerage_dif += $brokerage_dif;
                ?>
            </thead>
        </table>
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th>Deal</th>
                    <th>Client</th>
                    <th>Lender</th>
                    <th>Broker Staff</th>
                    <th>Loan Amount</th>
                    <th>ABP Est. Upfront</th>
                    <th>Actual Upfront</th>
                    <th>Upfront Difference</th>
                    <th>ABP Est. Brokerage</th>
                    <th>Actual Brokerage</th>
                    <th>Brokerage Difference</th>
                    <th>ABP Est. Trail</th>
                    <th>Actual <span style="margin-right: 10px">Trail</span></th>
                    <th>Trail Difference</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif
    @if (count($deals) > 0)
        <table class="grand_total" style="width: 100%">
            <tr>
                <th colspan="3"><?php
                echo 'Grand Total';
                ?></th>
                <th style="text-align: left;width: 7%">${{ $total_actual_loan_amt }}</th>
                <th style="text-align: left;width: 7%">${{ $total_ABP_est_upfront_amt }}</th>
                <th style="text-align: left;width: 7%">${{ $total_ABP_actual_upfront_amt }}</th>
                <th style="text-align: left;width: 7%">${{ $total_ABP_upfront_diff }}</th>
                <th style="text-align: left;width: 7%">${{ $total_ABP_est_brokerage }}</th>
                <th style="text-align: left;width: 7%">${{ $total_ABP_actual_brokerage }}</th>
                <th style="text-align: left;width: 7%">${{ $total_brokerage_dif }}</th>
            </tr>
        </table>
    @endif

</body>

</html>
