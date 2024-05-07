<html lang="en">

<head>
    <title>Deals Settled</title>

</head>

<body style="font-family: system-ui, system-ui, verdana;">
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .thead_style tr th {
            border-bottom: 1px solid grey;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-weight: 800;
            font-size: 12px;
        }

        .subtotal tr th {
            border-top: 1px solid grey;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 11px;
        }

        .grand_total tr th {
            border-top: 2px solid grey;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 11px;
        }

        .body_class tr td {
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
            font-size: 11;
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
                <td style="width: 100%; text-align: center;">
                    <span style="font-size: 20px;font-weight: bold;">Deals Settled Report</span>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 16px; font-weight: bold;">
                    Report Period: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 14px; font-weight: bold;">
                    Group By: {{ $group_by }}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: left; font-size: 16px; font-weight: bold;">
                    Broker : {{ $group_by }}
                </td>
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
                                    //echo $deal->broker_staff->surname??'' . $deal->broker_staff->given_name??'';
                                    echo ($deal->broker_staff->surname ?? '') . ' ' . ($deal->broker_staff->given_name ?? '');
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
            $deals_list = \App\Models\Deal::select('deals.*')
                ->with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
                ->whereIn('status', [4])
                ->orderBy('status_date', 'asc');
            
            if (!empty($date_from)) {
                $deals_list->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')));
            }
            if (!empty($date_to)) {
                $deals_list->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($date_to . ' 23:59:59')));
            }
            
            if ($group_by == 'Product') {
                $deals_list->where('product_id', $deal->product_id);
            } elseif ($group_by == 'BrokerStaff') {
                $deals_list->where('broker_staff_id', $deal->broker_staff_id);
            } elseif ($group_by == 'Status') {
                $deals_list->where('status', $deal->status);
            } else {
                $deals_list->where('lender_id', $deal->lender_id);
            }
            
            $deals_list = $deals_list->get();
            $broker_est_loan_amt = 0;
            $broker_est_upfront = 0;
            $broker_est_brokerage = 0;
            ?>

            <table style="width: 100%;margin-top: 5px">
                <thead class="thead_style">
                    <tr>
                        <th style="width: 3%">Deal</th>
                        <th style="width: 15%">Client</th>
                        <th style="width: 10%">Lender</th>
                        <th style="width: 10%">Product</th>
                        <th style="width: 12%">Broker Staff</th>
                        <th style="width: 8%">Date</th>
                        <th style="width: 7%">Status</th>
                        <th style="width: 7%">Age (Days)</th>
                        <th style="width: 10%">Broker Est <br />Loan Amount</th>
                        <th style="width: 7%">Broker Est <br />Upfront</th>
                        <th style="width: 7%">Broker Est <br />Brokerage</th>
                    </tr>
                </thead>
                <tbody class="body_class">

                    @foreach ($deals_list as $deal_list)
                        <tr>
                            <td>{{ $deal_list->id }}</td>
                            <td>
                                @if ($deal_list->client)
                                    @if ($deal_list->client->individual == 1)
                                        {{ $deal_list->client->surname . ' ' . $deal_list->client->preferred_name }}
                                    @elseif ($deal_list->client->individual == 2)
                                        {{ $deal_list->client->trading }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $deal_list->lender->code }}</td>
                            <td>{{ $deal_list->product->name }}</td>
                            <td>{{ $deal_list->broker_staff ? $deal_list->broker_staff->surname . ' ' . $deal_list->broker_staff->given_name : ' ' }}
                            </td>
                            <td>
                                {{ $deal_list->status_date ? \Carbon\Carbon::parse($deal_list->status_date)->format('d/m/Y') : '' }}
                            </td>
                            <td>{{ $deal_list->deal_status->name }}</td>
                            <td><?php
                            $earlier = new DateTime(date('Y-m-d', strtotime($deal_list->en_status_date)));
                            
                            $later = new DateTime(date('Y-m-d', strtotime($deal_list->status_date)));
                            echo $abs_diff = $later->diff($earlier)->days;
                            
                            ?></td>
                            <!--<td>{{ $deal_list->status_date != '' ? date('m/d/Y', strtotime($deal_list->status_date)) : '' }}</td>-->
                            <td>${{ number_format($deal_list->broker_est_loan_amt ?? 0, 2, '.', ',') }}</td>
                            <td>${{ number_format($deal_list->broker_est_upfront ?? 0, 2, '.', ',') }}</td>
                            <td>${{ number_format($deal_list->broker_est_brokerage ?? 0, 2, '.', ',') }}</td>
                        </tr>
                        <?php
                        $broker_est_loan_amt += $deal_list->broker_est_loan_amt;
                        $broker_est_brokerage += $deal_list->broker_est_brokerage;
                        $broker_est_upfront += $deal_list->broker_est_upfront;
                        ?>
                    @endforeach
                </tbody>
                <thead class="subtotal">
                    <tr>
                        <th colspan="8"><?php
                        if ($group_by == 'Product') {
                            echo $deal->product->name . ' Subtotals';
                        } elseif ($group_by == 'BrokerStaff') {
                            echo ($deal->broker_staff->surname ?? '') . ' ' . ($deal->broker_staff->given_name ?? '') . ' Subtotals';
                        } elseif ($group_by == 'Status') {
                            echo $deal->deal_status->name . ' Subtotals';
                        } else {
                            echo $deal->lender->name . ' Subtotals';
                        }
                        ?></th>
                        <th style="text-align: left;width: 7%">
                            ${{ number_format($broker_est_loan_amt ?? 0, 2, '.', ',') }}</th>
                        <th style="text-align: left;width: 7%">
                            ${{ number_format($broker_est_upfront ?? 0, 2, '.', ',') }}</th>
                        <th style="text-align: left;width: 7%">
                            ${{ number_format($broker_est_brokerage ?? 0, 2, '.', ',') }}</th>
                    </tr>
                    <?php
                    $total_broker_est_loan_amt += $broker_est_loan_amt;
                    $total_broker_est_brokerage += $broker_est_brokerage;
                    $total_broker_est_upfront += $broker_est_upfront;
                    ?>
                </thead>
            </table>
        @endforeach
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th style="width: 3%">Deal</th>
                    <th style="width: 15%">Client</th>
                    <th style="width: 15%">Lender</th>
                    <th style="width: 10%">Product</th>
                    <th style="width: 12%">Broker Staff</th>
                    <th style="width: 8%">Settled</th>
                    <th style="width: 7%">Status</th>
                    <th style="width: 7%">Age (Days)</th>
                    <th style="width: 10%">Broker Est <br />Loan Amount</th>
                    <th style="width: 7%">Broker Est <br />Upfront</th>
                    <th style="width: 7%">Broker Est Brokerage</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif

    @if (count($deals) > 0)
        <table style="width: 100%;margin-top: 5px">
            <tr>
                <td>
                    <table class="grand_total" style="width: 100%">
                        <tr>
                            <th colspan="8"><?php
                            echo 'Grand Total';
                            ?></th>
                            <th style="text-align: left;width: 12%">
                                ${{ number_format($total_broker_est_loan_amt ?? 0, 2, '.', ',') }}</th>
                            <th style="text-align: left;width: 7%">
                                ${{ number_format($total_broker_est_upfront ?? 0, 2, '.', ',') }}</th>
                            <th style="text-align: left;width: 7%">
                                ${{ number_format($total_broker_est_brokerage ?? 0, 2, '.', ',') }}</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif

</body>

</html>
