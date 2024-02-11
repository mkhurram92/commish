<html lang="en">

<head>
    <title>Referrer Commission Rating Summary</title>
</head>

<body style=" font-family: system-ui, system-ui, sans-serif;">
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        tbody tr td {
            letter-spacing: 1px;
        }

        .thead_style {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .thead_style th {
            background-color: #f2f2f2;
            color: #333;
            /* Adjust the color as needed */
            font-weight: 700;
            /* Use 700 instead of 800 for a slightly less bold look */
            letter-spacing: 1px;
            /* Reduce the letter-spacing for a cleaner look */
            font-size: 14px;
            /* Slightly increase the font size for better readability */
            padding: 10px;
            /* Add padding for better spacing */
            text-align: center;
        }

        .thead_style th:nth-child(1) {
            width: 25%;
        }

        .thead_style th:nth-child(2) {
            width: 10%;
        }

        .thead_style th:nth-child(3) {
            width: 10%;
        }

        .thead_style th:nth-child(4) {
            width: 10%;
        }

        .thead_style th:nth-child(5) {
            width: 10%;
        }

        .thead_style th:nth-child(6) {
            width: 10%;
        }

        .thead_style th:nth-child(7) {
            width: 10%;
        }

        .thead_style th:nth-child(8) {
            width: 25%;
        }

        .subtotal tr th {
            border-top: 1px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 1px 1px;
            text-align: left;
            font-size: 12px;
        }

        .grand_total tr th {
            border-top: 1px solid grey;
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: center;
            font-size: 12px;
        }

        .body_class tr td {
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: center;
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

        tr {
            page-break-inside: avoid;
        }

        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            width: 100%;
        }
    </style>

    <table style="width: 100%;text-align:center">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;">
                    <span style="font-size: 28px; font-weight: bold;">
                        Referrer Commission Rating Summary
                    </span>
                </td>
            </tr>
            <tr><br></tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 16px; font-weight: bold;">Upfront Commission
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 14px; font-weight: bold;">
                    Report Period:
                    {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}
                </td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%;margin-top: 5px">
        <thead class="thead_style">
            <tr>
                <th>Referrer</th>
                <th>No.of Loans</th>
                <th>Loan Amount</th>
                <th>Gross Upfront</th>
                <th>% Paid to Ref</th>
                <th>Referrer Upfront</th>
                <th>FMD Upfront</th>
                <th>Average FMD Upfront</th>
            </tr>
        </thead>
        <tbody class="body_class">
            <?php
            $totalNumberOfLoans = 0;
            $totalLoanAmount = 0;
            $totalGrossUpfront = 0;
            $totalReferrerUpfront = 0;
            $totalFMDUpfront = 0;
            $totalAverageFMDUpfront = 0;
            ?>
            @foreach ($deals as $deal)
                <tr>
                    <td>{{ $deal->Result }}</td>
                    <td>{{ number_format($deal->NumberOfLoansUpfront, 0) }}</td>
                    <td>${{ number_format($deal->SumOfLoansUpfront, 2) }}</td>
                    <td>${{ number_format($deal->SumOfdea_UpfrontEst_ABP, 2) }}</td>
                    <td><?php
                    $d = \App\Models\Deal::where('referror_split_referror', $deal->referror_split_referror)
                        ->orderBy('id', 'desc')
                        ->first();
                    echo $d->referror_split_agg_brk_sp_upfrt . '%';
                    $referror_upfront = ($d->referror_split_agg_brk_sp_upfrt / 100) * $deal->SumOfdea_UpfrontEst_ABP;
                    ?></td>

                    <td>${{ number_format($referror_upfront, 2) }}</td>

                    <td>${{ number_format($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront, 2) }}
                    </td>
                    <td><?php
                    if ($deal->NumberOfLoansUpfront) {
                        echo '$' . number_format(($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront) / $deal->NumberOfLoansUpfront, 2);
                    } else {
                        echo '$0.00';
                    }
                    ?>
                    </td>
                </tr>
                <?php
                // Accumulate values for grand total
                $totalNumberOfLoans += $deal->NumberOfLoansUpfront;
                $totalLoanAmount += $deal->SumOfLoansUpfront;
                $totalGrossUpfront += $deal->SumOfdea_UpfrontEst_ABP;
                $totalReferrerUpfront += $referror_upfront;
                $totalFMDUpfront += $deal->SumOfdea_UpfrontEst_ABP - $referror_upfront;
                if ($deal->NumberOfLoansUpfront) {
                    $totalAverageFMDUpfront += ($deal->SumOfdea_UpfrontEst_ABP - $referror_upfront) / $deal->NumberOfLoansUpfront;
                }
                ?>
            @endforeach
        </tbody>
        <tfoot class="grand_total">
            <tr>
                <th>Total</th>
                <th>{{ $totalNumberOfLoans }}</th>
                <th>${{ number_format($totalLoanAmount, 2) }}</th>
                <th>${{ number_format($totalGrossUpfront, 2) }}</th>
                <th></th>
                <th>${{ number_format($totalReferrerUpfront, 2) }}</th>
                <th>${{ number_format($totalFMDUpfront, 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <br />
    <div id='date-container'></div>
</body>

</html>
