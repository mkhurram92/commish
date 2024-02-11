<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Commission Monitoring</title>

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
    <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;"> <span style="font-size: 24px;font-weight: bold;">Broker
                        Commission Monitoring Report</span></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center;">From: {{ $date_from }} To: {{ $date_to }}</td>
            </tr>
        </tbody>

    </table>

    @if (count($brokers) > 0)
        <?php
        $broker_est_loan_amt = 0;
        $broker_est_upfront = 0;
        $totalDeals = count($brokers ?? []);
        $lastBroker = $broker_id ?? '';
        ?>
        @foreach ($brokers as $index => $deal)
            @if ($loop->first || $lastBroker !== $deal->broker->id)
                <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;">
                                    <?php
                                    echo $deal->broker->trading ?? '';
                                    $lastBroker = $deal->broker->id;
                                    ?>
                                </span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @if ($loop->first)
                <table style="width: 100%;margin-top: 5px">
                    <thead class="thead_style">
                        <tr>
                            <th style="width: 5%">Deal</th>
                            <th style="width: 12%">Client</th>
                            <th style="width: 8%">Lender</th>
                            <th style="width: 10%">Est. Loan Amount</th>
                            <th style="width: 10%">Actual</th>
                            <th style="width: 8%">Diff</th>
                            <th style="width: 10%">Est. Upfront</th>
                            <th style="width: 9%">Actual</th>
                            <th style="width: 6%">Diff</th>
                            <th style="width: 10%">Est. Trail</th>
                            <th style="width: 6%">Actual</th>
                            <th style="width: 6%">Diff</th>
                        </tr>
                    </thead>
                    <tbody class="body_class">
            @endif
            @if ($totalDeals > 0)
                <?php
                $deal_trail = $deal->deal_commission_trial->first();
                ?>
                <tr>
                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->client->first_name . ' ' . $deal->client->surname }}</td>
                    <td>{{ $deal->lender->code ?? '' }}</td>
                    <td>${{ $deal->broker_est_loan_amt ?? '' }}</td>
                    <td>${{ $deal->actual_loan ?? '' }}</td>
                    <td>${{ $deal->broker_est_loan_amt - $deal->actual_loan }}</td>
                    <td>{{ $deal->broker_est_upfront }}</td>
                    <td>0</td> <!-- Needs to calculate Actual upfront -->
                    <td>0</td> <!-- Needs to calculate Diff -->
                    <td>{{ $deal->broker_est_trail }}</td>
                    <td>0</td> <!-- Needs to calculate Actual trail -->
                    <td>0</td> <!-- Needs to calculate Diff -->
                </tr>
            @endif
        @endforeach
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                            <th style="width: 5%">Deal</th>
                            <th style="width: 14%">Client</th>
                            <th style="width: 5%">Lender</th>
                            <th style="width: 11%">Est. Loan Amount</th>
                            <th style="width: 7.14%">Actual</th>
                            <th style="width: 7.14%">Diff</th>
                            <th style="width: 12%">Est. Upfront</th>
                            <th style="width: 9%">Actual</th>
                            <th style="width: 5%">Diff</th>
                            <th style="width: 5%">Est. Trail</th>
                            <th style="width: 4%">Actual</th>
                            <th style="width: 5%">Diff</th>
                        </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif

</body>

</html>
