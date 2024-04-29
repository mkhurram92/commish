<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Lender Commission Reconciliation</title>
</head>

<body style="font-family: system-ui, sans-serif;">
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .thead_style tr th {
            background-color: #f2f2f2;
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: center;
            font-weight: 800;
            font-size: 16px;
            height: 30px;
        }

        .body_class tr td,
        .grand_total tr th {
            font-family: system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: center;
            font-size: 14px;
        }

        .subtotal tr td {
            border-top: 1px solid grey;
            border-bottom: 1px solid gray;
            font-size: 12px;
        }

        .grand_total tr th {
            border-top: 2px solid grey;
            font-size: 16px;
            padding-bottom: 5px;
        }

        .body_class tr td {
            font-size: 14px;
            line-height: 1.5;
        }

        .body_class tbody {
            border-collapse: separate;
            border-spacing: 1px 1px;
            border-bottom: 1px solid;
        }

        tr {
            page-break-inside: avoid;
        }

        .grand_total tr th {
            border-top: 1px solid grey;
            font-size: 14px;
            padding-bottom: 2px;
            padding-top: 5px;
        }

        .grand_total tr th {
            font-size: 16px;
        }

        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-total {
            text-align: center;
            font-weight: bold;
        }

        .grand-total-table td {
            width: 12.5%;
        }

        .grand-total-table th,
        .grand-total-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
    </style>
    <table style="margin-top: 5px; margin-bottom: 10px; width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;">
                    <span style="font-size: 28px; font-weight: bold;">
                        Lender Commission Reconciliation
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; font-size: 16px; font-weight: bold;">
                    Report Period: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}
                </td>
            </tr>
        </tbody>
    </table>
    @if (count($lenders) > 0)
        <table style="width: 100%;margin-top: 5px; text-align:center">
            <thead class="thead_style">
                <tr style="background-color: #f2f2f2;">
                    <th style="width:12.5%;">Received</th>
                    <th style="width:12.5%;">AGG Upfront</th>
                    <th style="width:12.5%;">Trail</th>
                    <th style="width:12.5%;">Trail No Gst</th>
                    <th style="width:12.5%;">ABP Upfront</th>
                    <th style="width:12.5%;">Trail</th>
                    <th style="width:12.5%;">Trail No Gst</th>
                    <th style="width:12.5%;">Total</th>
                </tr>
            </thead>
            <tbody class="body_class">
                <?php
                $grand_total_abp_upfront = 0;
                $grand_total_agg_upfront = 0;
                $grand_total_abp_no_gst = 0;
                $grand_total_agg_no_gst = 0;
                $grand_total_abp_gst = 0;
                $grand_total_agg_gst = 0;
                $grand_total_com = 0;
                $grand_total_agg_invoices = 0;
                $grand_total_abp_invoices = 0;
                ?>
                @foreach ($lenders as $lender)
                    <?php
                    $commssions = \App\Models\DealCommission::select(
                        \DB::raw('deals.gst_applies,deal_commissions.id,date_statement,
                                                                                                                                                                                                                                                    
                                                                                ROUND(Sum(If(`type` = 12 AND deals.gst_applies=1,agg_amount * 1.1, 0)), 2) AS agg_gst,
                                                                                ROUND(Sum(If(`type` = 12 AND deals.gst_applies=0,agg_amount,0)), 2) AS agg_no_gst,
                                                                                ROUND(Sum(If(`type` = 13 ,agg_amount * 1.1, 0)), 2) AS agg_upfront,
                                                                                ROUND(Sum(If(`type` = 13 ,broker_amount * 1.1, 0) + If(`type` = 13, referror_amount * 1.1, 0)), 2) AS abp_upfront,
                                                                                ROUND(Sum(If(`type` = 12 AND deals.gst_applies=0,broker_amount,0) + If(`type` = 12 AND deals.gst_applies = 0, referror_amount, 0)), 2) AS abp_no_gst,
                                                                                ROUND(Sum(If(`type` = 12 AND deals.gst_applies=1,broker_amount * 1.1, 0) + 
                                                                                If(`type` = 12 AND deals.gst_applies = 1, referror_amount * 1.1, 0)), 2) AS abp_gst,deal_id'),
                    )
                        ->join('deals', 'deals.id', '=', 'deal_commissions.deal_id')
                        ->join('lenders', 'lenders.id', '=', 'deals.lender_id')
                        ->where('lenders.id', $lender->id)
                        ->where('date_statement', '>=', date('Y-m-d', strtotime($date_from)))
                        ->where('date_statement', '<=', date('Y-m-d', strtotime($date_to)))
                        ->groupBy('date_statement')
                        ->where(function ($qu) {
                            $qu->where('agg_amount', '>', 0)->orWhere('broker_amount', '>', 0);
                        })
                        ->get();
                    ?>
                    <?php
                    $total_abp_upfront = 0;
                    $total_agg_upfront = 0;
                    $total_abp_no_gst = 0;
                    $total_agg_no_gst = 0;
                    $total_abp_gst = 0;
                    $total_agg_gst = 0;
                    $total_com = 0;
                    
                    ?>
                    @if (count($commssions))
                        <tr>
                            <td colspan="8" style="width: 100%;border-bottom: 1px solid; text-align:left;">
                                <span style="font-weight: bold;">
                                    <?php
                                    echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $lender->code ?? '';
                                    ?>
                                </span>
                            </td>
                        </tr>
                        @foreach ($commssions as $commssion)
                            <tr>

                                <?php
                                $invoice_sums = \App\Models\Deal::select(\DB::raw('SUM((agg_est_trail + agg_est_upfront) * 1.1) as sum_of_trail_upfront_gst'), \DB::raw('SUM((broker_est_upfront + broker_est_trail) * 1.1) as sum_of_abp_trail_upfront_gst'))
                                    ->where('id', $commssion->deal_id)
                                    ->first();
                                
                                $grand_total_agg_invoices += $invoice_sums ? $invoice_sums->sum_of_trail_upfront_gst : 0;
                                $grand_total_abp_invoices += $invoice_sums ? $invoice_sums->sum_of_abp_trail_upfront_gst : 0;
                                ?>

                                <td style="width:12.5%;">{{ date('d/m/Y', strtotime($commssion->date_statement)) }}</td>

                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->agg_upfront, 2);
                                    $total_agg_upfront = +$commssion->agg_upfront;
                                    ?>
                                </td>
                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->agg_gst, 2);
                                    $total_agg_gst += $commssion->agg_gst;
                                    ?></td>
                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->agg_no_gst, 2);
                                    $total_agg_no_gst += $commssion->agg_no_gst;
                                    ?></td>
                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->abp_upfront, 2);
                                    $total_abp_upfront += $commssion->abp_upfront;
                                    ?></td>
                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->abp_gst, 2);
                                    $total_abp_gst += $commssion->abp_gst;
                                    ?>
                                </td>
                                <td style="width:12.5%;">
                                    <?php
                                    echo '$' . number_format($commssion->abp_no_gst, 2);
                                    $total_abp_no_gst += $commssion->abp_no_gst;
                                    ?>
                                </td>
                                <td style="width:12.5%;">
                                    <?php $total = $commssion->abp_no_gst + $commssion->abp_gst + $commssion->abp_upfront + $commssion->agg_gst + $commssion->agg_upfront + $commssion->agg_no_gst;
                                    echo '$' . number_format($total, 2);
                                    $total_com += $total;
                                    ?>
                                </td>
                            </tr>
                        @endforeach
                        <?php
                        $grand_total_abp_upfront += $total_abp_upfront;
                        $grand_total_agg_upfront += $total_agg_upfront;
                        $grand_total_abp_no_gst += $total_abp_no_gst;
                        $grand_total_agg_no_gst += $total_agg_no_gst;
                        $grand_total_abp_gst += $total_abp_gst;
                        $grand_total_agg_gst += $total_agg_gst;
                        $grand_total_com += $total_com;
                        ?>
                        <tr>
                            <td colspan="8">
                                <table style="width: 100%">
                                    <tbody style="text-align:center; background-color:#f2f2f2">
                                        <tr>
                                            <td style="width:12.5%;"></td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_agg_upfront, 2) }}</b>
                                            </td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_agg_gst, 2) }}</b></td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_agg_no_gst, 2) }}</b>
                                            </td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_abp_upfront, 2) }}</b>
                                            </td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_abp_gst, 2) }}</b></td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_abp_no_gst, 2) }}</b>
                                            </td>
                                            <td style="width:12.5%;"><b>${{ number_format($total_com, 2) }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
                <!-- Displaying the Grand Totals after processing all Lenders -->
                <tr>
                    <td colspan="8">
                        <table class="grand-total-table">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <td>SubTotal</td>
                                    <td>${{ number_format($grand_total_agg_upfront, 2) }}</td>
                                    <td>${{ number_format($grand_total_agg_gst, 2) }}</td>
                                    <td>${{ number_format($grand_total_agg_no_gst, 2) }}</td>
                                    <td>${{ number_format($grand_total_abp_upfront, 2) }}</td>
                                    <td>${{ number_format($grand_total_abp_gst, 2) }}</td>
                                    <td>${{ number_format($grand_total_abp_no_gst, 2) }}</td>
                                    <td>${{ number_format($grand_total_com, 2) }}</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                <?php
                $agg_subtotal = $grand_total_agg_upfront + $grand_total_agg_gst + $grand_total_agg_no_gst;
                $abp_subtotal = $grand_total_abp_upfront + $grand_total_abp_gst + $grand_total_abp_no_gst;
                
                $agg_inovices = $grand_total_agg_invoices;
                $abp_invoices = $grand_total_abp_invoices;
                
                ?>
                <tr>
                    <td colspan="8">
                        <table class="grand-total-table">
                            <thead style="border: 0;">
                                <tr style="background-color: #ffffff;">
                                    <th colspan="4">AGG Invoices Total :
                                        ${{ number_format($agg_subtotal, 2) }}
                                    </th>
                                    <th colspan="4">ABP Invoices Total :
                                        ${{ number_format($abp_subtotal, 2) }}
                                    </th>
                                </tr>
                                <!--<tr style="background-color: #ffffff;">
                                    <th colspan="4">AGG Invoices :
                                        ${{ number_format($agg_inovices, 2) }}
                                    </th>
                                    <th colspan="4">ABP Invoices :
                                        ${{ number_format($abp_invoices, 2) }}
                                    </th>
                                </tr>
                                <tr style="background-color: #ffffff;">
                                    <th colspan="4">Difference :
                                        ${{ number_format($agg_inovices - $agg_subtotal, 2) }}
                                    </th>
                                    <th colspan="4">Difference :
                                        ${{ number_format($abp_invoices - $abp_subtotal, 2) }}
                                    </th>
                                </tr>-->
                            </thead>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th>Received</th>
                    <th>Agg Upfront</th>
                    <th>Trail</th>
                    <th>Trail No Gst</th>
                    <th>ABP Upfront</th>
                    <th>Trail</th>
                    <th>Trail No Gst</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif
</body>

</html>
