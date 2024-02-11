<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>upfront Discrepancies</title>
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


<table style="margin-top: 5px;margin-bottom:5px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">FM Direct Upfront Discrepancies</span>
        </td>
        <td><span
                style="width: 60%;font-weight: bold;">Grouped By {{ $group_by }} For period from {{ $date_from.' to '.$date_to }}</span>
        </td>
    </tr>
    </tbody>
</table>
<?php $total_broker_est_loan_amt = 0;
$total_broker_est_upfront = 0;
$total_broker_est_brokerage = 0; ?>
@if(count($deals)>0)
    @foreach($deals as $key => $deal)
        <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;"><?php
                        if ($group_by == 'BrokerStaff') {
                            echo $key ?? '';
                        }
                        ?></span></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <?php
        $broker_est_loan_amt = 0;
        $total_upfront_amt = 0;
        $total_upfront_received = 0;
        $total_brokerage_amt = 0;
        $total_brokerage_received = 0;
        $total_diff = 0;

        ?>

        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
            <tr>
                <th style="width: 3%">Deal</th>
                <th style="width: 15%">Client</th>
                <th style="width: 15%">Lender</th>
                <th style="width: 10%">Broker Staff</th>
                <th style="width: 8%">Loan Amount</th>
                <th style="width: 7%">Date Settled</th>
                <th style="width: 7%">ABP Est. Upfront</th>
                <th style="width: 7%">ABP Est. Brokerage</th>
                <th style="width: 7%">Actual <span style="margin-right: 10px">Upfront</span></th>
                <th style="width: 7%">Actual Brokerage</th>
                <th style="width: 7%">Difference</th>
                <th style="width: 7%">Variance %</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($deal as $comm)

                <tr>
                    <td>{{ $comm['deal_id'] }}</td>
                    <td>{{ $comm['client']??'' }}</td>
                    <td>{{ $comm['lender']??'' }}</td>
                    <td>{{ $comm['staff']??'' }}</td>
                    <td>${{ $comm['loan']??0.00 }}</td>
                    <td>{{ $comm['date_settled'] }}</td>
                    {{--                        <td>{{ $comm['date_received']  }}</td>--}}
                    <td>${{ $comm['abp_upfront'] }}</td>
                    <td>${{ $comm['abp_brokerage']??0.00 }}</td>
                    <td>${{ $comm['receive_upfront'] }}</td>
                    <td>${{ $comm['brokerage']??0.00 }}</td>
                    <td>${{ $comm['receive_upfront'] - $comm['brokerage'] }}</td>
                    <td>{{ $comm['variance']??0.00 }}%</td>

                </tr>

            <?php
            $broker_est_loan_amt += $comm['loan'] ?? 0.00;
            $total_upfront_amt += $comm['abp_upfront'];
            $total_upfront_received += $comm['receive_upfront'];
            $total_brokerage_amt += $comm['abp_brokerage'];
            $total_brokerage_received += $comm['brokerage'];
            $total_diff += $comm['receive_upfront'] - $comm['brokerage'];
            ?>
            @endforeach
            <thead class="subtotal" style="margin-top: 10px;">
            <tr>
                <th style="width: 3%"></th>
                <th style="width: 15%">Sub Totals</th>
                <th style="width: 15%"></th>
                <th style="width: 10%"></th>
                <th style="width: 7%">${{ $broker_est_loan_amt??0.00}}</th>
                <th style="width: 4%"></th>
                <th style="width: 7%">${{$total_upfront_amt}}</th>
                <th style="width: 7%">${{$total_brokerage_amt}}</th>
                <th style="width: 7%">${{$total_upfront_received}}</th>
                <th style="width: 7%">${{$total_brokerage_received}}</th>
                <th style="width: 7%">${{$total_diff}}</th>
                <th style="width: 7%"></th>
            </tr>
            </thead>
            @endforeach
            </tbody>
        </table>
        @else
            <table style="width: 100%;margin-top: 5px">
                <thead class="thead_style">
                <tr>
                    <th style="width: 3%">Deal</th>
                    <th style="width: 15%">Client</th>
                    <th style="width: 15%">Lender</th>
                    <th style="width: 10%">Broker Staff</th>
                    <th style="width: 8%">Loan Amount</th>
                    <th style="width: 7%">Date Settled</th>
                    <th style="width: 7%">ABP Est. Upfront</th>
                    <th style="width: 7%">ABP Est. Brokerage</th>
                    <th style="width: 7%">Actual <span style="margin-right: 10px">Upfront</span></th>
                    <th style="width: 7%">Actual Brokerage</th>
                    <th style="width: 7%">Difference</th>
                    <th style="width: 7%">Variance %</th>
                </tr>
                </thead>
                <tbody class="body_class">
                </tbody>
            </table>
        @endif
        @if(count($deals)>0)

            {{--    <table class="grand_total"  style="width: 100%">--}}
            {{--        <tr>--}}
            {{--            <th colspan="9"><?php--}}
            {{--                echo 'Grand Total'--}}
            {{--                ?></th>--}}
            {{--            <th style="text-align: left;width: 7%">${{ $total_broker_est_loan_amt }}</th>--}}
            {{--            <th style="text-align: left;width: 7%">${{$total_broker_est_upfront}}</th>--}}
            {{--            <th style="text-align: left;width: 7%">${{ $total_broker_est_brokerage }}</th>--}}
            {{--        </tr>--}}
            {{--    </table>--}}

        @endif

</body>
</html>
