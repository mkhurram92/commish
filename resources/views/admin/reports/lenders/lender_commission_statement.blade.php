<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Lender Commission Statement</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: separate;
            border-spacing: 0 5px;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        th,
        td {
            text-align: left;
            font-size: 12px;
        }

        th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .thead_style th {
            border-bottom: 1px solid grey;
            font-size: 12px;
            font-family: system-ui, sans-serif;
        }

        .gross_tota_trail th,
        .subtotal th {
            border-top: 1px solid grey;
        }

        .grand_total th {
            border-top: 2px solid grey;
            font-size: 14px;
        }

        .body_class td {
            border-bottom: 0px solid #e0e0e0;
        }

        .body_class tbody {
            border-bottom: 1px solid #e0e0e0;
        }

        tr {
            page-break-inside: avoid;
        }

        .header-row {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        .date-row {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .product-row {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }

        .product-row th {
            background-color: #f2f2f2;
        }

        .product-total-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .grand-total-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .pagebreak {
            clear: both;
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;">
                    <span style="font-size: 28px; font-weight: bold;">
                        Lender Commission Statement
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 16px; font-weight: bold;">
                    Statement Date: {{ date('d/m/Y', strtotime($to_date)) }}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 14px; font-weight: bold;">
                    Institution : <?php
                                    $lender = \App\Models\Lenders::where('id', $lenders)->first();
                                    echo $lender->name;
                                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; font-size: 16px; font-weight: bold;">
                    Finance Mutual Direct
                </td>
            </tr>
        </tbody>
    </table>
    <?php
    $agg_trail_amount = 0;
    $agg_upfront_amount = 0;
    $abp_trail_amount = 0;
    $abp_upfront_amount = 0;
    ?>
    @php
    $grandTotalAggAmount = $grandTotalBrokerAmount = $grandTotalReferrorAmount = 0;
    @endphp
    @foreach ($dealsforFIMU as $dealType => $deals)
    <table>
        <thead class="thead_style">
            <tr class="product-row">
                <th colspan="6">{{ $dealType }}</th>
            </tr>
            <tr class="product-row">
                <th style="width: 15%">Product</th>
                <th style="width: 35%">Client</th>
                <th style="width: 14%">Master</th>
                <th style="width: 12%">ABP</th>
                <th style="width: 12%">Referror</th>
                <th style="width: 12%">Total Amount</th>
            </tr>
        </thead>
        <tbody class="body_class">
            @php
            $totalAggAmount = $totalBrokerAmount = $totalReferrorAmount = 0;
            @endphp
            @if ($deals->isNotEmpty())
            @foreach ($deals as $deal)
            <tr>
                <td>{{ $deal->productName }}</td>
                <td>{{ $deal->client_name }}</td>
                <td>${{ number_format($deal->agg_amount, 2) }}</td>
                <td>${{ number_format($deal->broker_amount, 2) }}</td>
                <td>${{ number_format($deal->referror_amount, 2) }}</td>
                <td>${{ number_format($deal->total_amont, 2) }}</td>
            </tr>
            @php
            $totalAggAmount += $deal->agg_amount;
            $totalBrokerAmount += $deal->broker_amount;
            $totalReferrorAmount += $deal->referror_amount;
            @endphp
            @endforeach
            @else
            <tr>
                <td colspan="6">No records found for this product type.</td>
            </tr>
            @endif
            <tr class="product-total-row">
                <td colspan="2">{{ $dealType }} Total : {{ $deals->count() }}</td>
                <td>${{ number_format($totalAggAmount, 2) }}</td>
                <td>${{ number_format($totalBrokerAmount, 2) }}</td>
                <td>${{ number_format($totalReferrorAmount, 2) }}</td>
                <td>${{ number_format($totalAggAmount + $totalBrokerAmount + $totalReferrorAmount, 2) }}</td>
            </tr>
            <?php
            if ($dealType == 'Trail') {
                $agg_trail_amount += $totalAggAmount;
                $abp_trail_amount += $totalBrokerAmount;
            } elseif ($dealType == 'Upfront') {
                $agg_upfront_amount += $totalAggAmount;
                $abp_upfront_amount += $totalBrokerAmount;
            }
            ?>
            @php
            $grandTotalAggAmount += $totalAggAmount;
            $grandTotalBrokerAmount += $totalBrokerAmount;
            $grandTotalReferrorAmount += $totalReferrorAmount;
            @endphp
        </tbody>
    </table>
    @endforeach
    <!-- Finance Mutual Direct Section Total Row -->
    <table style="width:100%">
        <tbody>
            <tr class="product-total-row">
                <td colspan="2">APB Total</td>
                <td style="width: 14%">${{ number_format($grandTotalAggAmount, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalBrokerAmount, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalReferrorAmount, 2) }}</td>
                <td style="width: 12%">
                    ${{ number_format($grandTotalAggAmount + $grandTotalBrokerAmount + $grandTotalReferrorAmount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin-top: 2px; margin-bottom: 2px;">
    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; font-size: 16px; font-weight: bold;">
                    Other ABPs
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin-top: 2px; margin-bottom: 2px;">
    @php
    $grandTotalAggAmountOthers = $grandTotalBrokerAmountOthers = $grandTotalReferrorAmountOthers = 0;
    @endphp
    <!-- Loop for dealsforOthers -->
    @foreach ($dealsforOthers as $dealType => $deals)
    <table>
        <thead class="thead_style">
            <tr class="product-row">
                <th colspan="6">{{ $dealType }}</th>
            </tr>
            <tr class="product-row">
                <th style="width: 15%">Product</th>
                <th style="width: 35%">Client</th>
                <th style="width: 14%">Master</th>
                <th style="width: 12%">ABP</th>
                <th style="width: 12%">Referror</th>
                <th style="width: 12%">Total Amount</th>
            </tr>
        </thead>
        <tbody class="body_class">
            @php
            $totalAggAmount = $totalBrokerAmount = $totalReferrorAmount = 0;
            @endphp
            @if ($deals->isNotEmpty())
            @foreach ($deals as $deal)
            <tr>
                <td>{{ $deal->productName }}</td>
                <td>{{ $deal->client_name }}</td>
                <td>${{ number_format($deal->agg_amount, 2) }}</td>
                <td>${{ number_format($deal->broker_amount, 2) }}</td>
                <td>${{ number_format($deal->referror_amount, 2) }}</td>
                <td>${{ number_format($deal->total_amont, 2) }}</td>
            </tr>
            @php
            $totalAggAmount += $deal->agg_amount;
            $totalBrokerAmount += $deal->broker_amount;
            $totalReferrorAmount += $deal->referror_amount;
            @endphp
            @endforeach
            @else
            <tr>
                <td colspan="6">No records found for this product type.</td>
            </tr>
            @endif
            <tr class="product-total-row">
                <td colspan="2">{{ $dealType }} Total : {{ $deals->count() }}</td>
                <td>${{ number_format($totalAggAmount, 2) }}</td>
                <td>${{ number_format($totalBrokerAmount, 2) }}</td>
                <td>${{ number_format($totalReferrorAmount, 2) }}</td>
                <td>${{ number_format($totalAggAmount + $totalBrokerAmount + $totalReferrorAmount, 2) }}</td>
            </tr>
            <?php
            if ($dealType == 'Trail') {
                $agg_trail_amount += $totalAggAmount;
                $abp_trail_amount += $totalBrokerAmount;
            } elseif ($dealType == 'Upfront') {
                $agg_upfront_amount += $totalAggAmount;
                $abp_upfront_amount += $totalBrokerAmount;
            }
            ?>
            @php
            $grandTotalAggAmountOthers += $totalAggAmount;
            $grandTotalBrokerAmountOthers += $totalBrokerAmount;
            $grandTotalReferrorAmountOthers += $totalReferrorAmount;
            @endphp
        </tbody>
    </table>
    @endforeach
    <!-- Other APBs Section Total Row -->
    <table style="width:100%">
        <tbody>
            <tr class="product-total-row">
                <td colspan="2">Other ABPs Total</td>
                <td style="width: 14%">${{ number_format($grandTotalAggAmountOthers, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalBrokerAmountOthers, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalReferrorAmount, 2) }}</td>
                <td style="width: 12%">
                    ${{ number_format($grandTotalAggAmountOthers + $grandTotalBrokerAmountOthers + $grandTotalReferrorAmountOthers, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin-top: 2px; margin-bottom: 2px;">
    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; font-size: 16px; font-weight: bold;">
                    Referrors
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin-top: 2px; margin-bottom: 2px;">
    @php
    $grandTotalAggAmountReferror = $grandTotalBrokerAmountReferror = $grandTotalReferrorAmountReferror = 0;
    @endphp
    <!-- Loop for Referrors -->
    @foreach ($dealsforReferror as $dealType => $deals)
    <table>
        <thead class="thead_style">
            <tr class="product-row">
                <th colspan="6">{{ $dealType }}</th>
            </tr>
            <tr class="product-row">
                <th style="width: 15%">Product</th>
                <th style="width: 35%">Client</th>
                <th style="width: 14%">Master</th>
                <th style="width: 12%">ABP</th>
                <th style="width: 12%">Referror</th>
                <th style="width: 12%">Total Amount</th>
            </tr>
        </thead>
        <tbody class="body_class">
            @php
            $totalAggAmount = $totalBrokerAmount = $totalReferrorAmount = 0;
            @endphp
            @if ($deals->isNotEmpty())
            @foreach ($deals as $deal)
            <tr>
                <td>{{ $deal->productName }}</td>
                <td>{{ $deal->client_name }}</td>
                <td>${{ number_format($deal->agg_amount, 2) }}</td>
                <td>${{ number_format($deal->broker_amount, 2) }}</td>
                <td>${{ number_format($deal->referror_amount, 2) }}</td>
                <td>${{ number_format($deal->total_amont, 2) }}</td>
            </tr>
            @php
            $totalAggAmount += $deal->agg_amount;
            $totalBrokerAmount += $deal->broker_amount;
            $totalReferrorAmount += $deal->referror_amount;
            @endphp
            @endforeach
            @else
            <tr>
                <td colspan="6">No records found for this product type.</td>
            </tr>
            @endif
            <tr class="product-total-row">
                <td colspan="2">{{ $dealType }} Total : {{ $deals->count() }}</td>
                <td>${{ number_format($totalAggAmount, 2) }}</td>
                <td>${{ number_format($totalBrokerAmount, 2) }}</td>
                <td>${{ number_format($totalReferrorAmount, 2) }}</td>
                <td>${{ number_format($totalAggAmount + $totalBrokerAmount + $totalReferrorAmount, 2) }}</td>
            </tr>
            <?php
            if ($dealType == 'Trail') {
                $agg_trail_amount += $totalAggAmount;
                $abp_trail_amount += $totalBrokerAmount;
            } elseif ($dealType == 'Upfront') {
                $agg_upfront_amount += $totalAggAmount;
                $abp_upfront_amount += $totalBrokerAmount;
            }
            ?>
            @php
            $grandTotalAggAmountReferror += $totalAggAmount;
            $grandTotalBrokerAmountReferror += $totalBrokerAmount;
            $grandTotalReferrorAmountReferror += $totalReferrorAmount;
            @endphp
        </tbody>
    </table>
    @endforeach
    <!-- Referror Section Total Row -->
    <table>
        <tbody class="body_class">
            <tr class="product-total-row">
                <td colspan="2">Referror Total</td>
                <td style="width: 14%">${{ number_format($grandTotalAggAmountReferror, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalBrokerAmountReferror, 2) }}</td>
                <td style="width: 12%">${{ number_format($grandTotalReferrorAmountReferror, 2) }}</td>
                <td style="width: 12%">
                    ${{ number_format($grandTotalAggAmountReferror + $grandTotalBrokerAmountReferror + $grandTotalReferrorAmountReferror, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Display Grand Total for dealsforOthers -->
    <table>
        <tbody class="body_class">
            <tr class="grand-total-row">
                <td colspan="2">Grand Total</td>
                <td style="width: 14%">
                    ${{ number_format($grandTotalAggAmount + $grandTotalAggAmountOthers + $grandTotalAggAmountReferror, 2) }}
                </td>
                <td style="width: 12%">
                    ${{ number_format($grandTotalBrokerAmount + $grandTotalBrokerAmountOthers + $grandTotalBrokerAmountReferror, 2) }}
                </td>
                <td style="width: 12%">
                    ${{ number_format($grandTotalReferrorAmount + $grandTotalReferrorAmountOthers + $grandTotalReferrorAmountReferror, 2) }}
                </td>
                <td style="width: 12%">
                    ${{ number_format(
                        $grandTotalAggAmount +
                            $grandTotalAggAmountOthers +
                            $grandTotalAggAmountReferror +
                            $grandTotalBrokerAmount +
                            $grandTotalBrokerAmountOthers +
                            $grandTotalBrokerAmountReferror +
                            +$grandTotalReferrorAmount +
                            $grandTotalReferrorAmountOthers +
                            $grandTotalReferrorAmountReferror,
                    
                        2,
                    ) }}
                </td>
            </tr>
        </tbody>
    </table>
    <?php
    $agg_trail_total = $agg_trail_amount;
    $agg_upfront_total = $agg_upfront_amount;
    $agg_gst = ($agg_trail_amount + $agg_upfront_amount) * 0.1;
    $agg_no_gst = 0;
    $apb_trail = $abp_trail_amount;
    $abp_upfront = $abp_upfront_amount;
    $abp_gst = ($abp_trail_amount + $abp_upfront_amount) * 0.1;
    $abp_no_gst = 0;
    ?>
    <div class="pagebreak"> </div>
    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;"></th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">Upfront</th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">Trail</th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">GST</th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">Sub Total</th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">Trail No GST</th>
            <th style="border: 1px solid grey; padding: 8px; text-align: center;">Total</th>
        </tr>
        <tr>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">AGG</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_upfront_total, 2) }}</td>
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">${{ number_format($agg_gst, 2) }}
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total + $agg_gst, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">${{ number_format($agg_no_gst, 2) }}
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total + $agg_gst + $agg_no_gst, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">ABP</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($abp_upfront, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">${{ number_format($apb_trail, 2) }}
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">${{ number_format($abp_gst, 2) }}
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($apb_trail + $abp_gst, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">${{ number_format($abp_no_gst, 2) }}
            </td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($apb_trail + $abp_gst + $abp_no_gst, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">Total</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_upfront_total + $abp_upfront, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total + $apb_trail, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total + $abp_gst, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($agg_trail_total + $agg_gst + $apb_trail + $abp_gst, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($abp_no_gst + $abp_no_gst, 2) }}</td>
            <td style="border: 1px solid grey; padding: 8px; text-align: center;">
                ${{ number_format($apb_trail + $abp_gst + $abp_no_gst + $agg_trail_total + $agg_gst + $agg_no_gst, 2) }}
            </td>
        </tr>
    </table>
</body>

</html>