<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Upfront Outstanding</title>

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
    }.grand_total tr th {
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
    .body_class tbody{
        border-collapse: separate;
        border-spacing: 5px 5px;
        border-bottom: 1px solid;
    }
    thead{display: table-header-group;}
    tfoot {display: table-row-group;}
    tr {page-break-inside: avoid;}
</style>

<table style="margin-top: 5px;margin-bottom:5px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 30%"> <span style="font-size: 18px;font-weight: bold;">FM Direct Upfront Outstanding</span></td>
        <td> <span style="width: 60%;">Grouped By {{ $group_by }} For period from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
<?php
$total_actual_loan_amt=0;
$total_ABP_est_upfront_amt=0;
$total_ABP_actual_upfront_amt=0;
$total_ABP_upfront_diff=0;
$total_ABP_est_brokerage=0;
$total_ABP_actual_brokerage=0;
$total_brokerage_dif=0;
?>
@if(count($deals)>0)
    @foreach($deals as $deal)
        <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;"><?php
                        if($group_by=='Product'){
                            echo $deal->product->name??'';
                        }else if($group_by=='BrokerStaff'){
                            echo $deal->broker_staff->surname??'';
                        }else if($group_by=='Status'){
                            echo $deal->deal_status->name;
                        }else{
                            echo $deal->lender->name??'';
                        }
                        ?></span></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <?php
        $deals_list=\App\Models\Deal::select('deals.*')->with(['lender','client','deal_status','product','broker_staff'])
            ->whereStatus(4)
            ->where(function ($query){
                $query->where('broker_est_upfront','>',0)->orWhere('broker_est_loan_amt','>',0);
            });
        if(!empty($request['from_date'])){
            $deals_list->where('status_date','>=', date('Y-m-d H:i:s',strtotime($request['from_date'])));
        }if(!empty($request['to_date'])){
            $deals_list->where('status_date','<=', date('Y-m-d H:i:s',strtotime($request['to_date'])));
        }
        if($group_by=='Product'){
            $deals_list->where('product_id',$deal->product_id);
        }else if($group_by=='BrokerStaff'){
            $deals_list->where('broker_staff_id',$deal->broker_staff_id);
        }else if($group_by=='Status'){
            $deals_list->where('status',$deal->status);
        }else{
            $deals_list->where('lender_id',$deal->lender_id);
        }
        $deals_list=$deals_list->get();
        $actual_loan_amt=0;
        $ABP_est_upfront_amt=0;
        $ABP_actual_upfront_amt=0;
        $ABP_upfront_diff=0;
        $ABP_est_brokerage=0;
        $ABP_actual_brokerage=0;
        $brokerage_dif=0;
        ?>

        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th style="width: 6%">Deal</th>
                <th style="width: 15%">Client</th>
                <th style="width: 15%">Lender</th>
                <th style="width: 10%">Broker Staff</th>
                <th style="width: 8%">Loan Amount</th>
                <th style="width: 4%">Date Settled</th>
                <th style="width: 7%">ABP Est. Upfront</th>
                <th style="width: 7%">Actual Upfront</th>
                <th style="width: 7%">Upfront Difference</th>
                <th style="width: 7%">ABP Est. Brokerage</th>
                <th style="width: 7%">Actual Brokerage</th>
                <th style="width: 7%">Brokerage Difference</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($deals_list as $k=>$deal_list)
                <?php
                $upfront=\App\Models\DealCommission::whereDealId($deal->id)->where('type',13)->sum('broker_amount');
                $brokerrage=\App\Models\DealCommission::whereDealId($deal->id)->where('type',4)->sum('broker_amount');
                ?>
                <tr>
                    <td>{{ $deal_list->id }}</td>
                    <td>{{ $deal_list->client->surname }}</td>
                    <td>{{ $deal_list->lender->name }}</td>
                    <td>{{ $deal_list->broker_staff->surname??'' }}</td>
                    <td>{{ $deal_list->actual_loan }}</td>
                    <td>{{ date('m/d/Y',strtotime($deal_list->proposed_settlement))??''}}</td>
                    <td>{{ $deal_list->broker_est_upfront }}</td>
                    <td>{{ $upfront }}</td>
                    <td>{{ $up_diff=$deal_list->broker_est_upfront-$upfront }}</td>
                    <td>${{ $deal_list->broker_est_brokerage??'0' }}</td>
                    <td>${{$brokerrage??'0' }}</td>
                    <td>${{ $br_dif=$deal_list->broker_est_brokerage-$brokerrage }}</td>
                </tr>
                <?php
                $actual_loan_amt+=$deal_list->actual_loan;
                $ABP_est_upfront_amt+=$deal_list->broker_est_upfront;
                $ABP_actual_upfront_amt+=$upfront;
                $ABP_upfront_diff+=$up_diff;
                $ABP_est_brokerage+=$deal_list->broker_est_brokerage;
                $ABP_actual_brokerage+=$brokerrage;
                $brokerage_dif+=$br_dif;
                ?>
            @endforeach
            </tbody>
            <thead class="subtotal">
            <tr>
                <th colspan="3"><?php
                    if($group_by=='BrokerStaff'){
                        echo $deal->broker_staff->surname??''.' Subtotals';
                    }else if($group_by=='Status'){
                        echo $deal->deal_status->name.' Subtotals';
                    }else{
                        echo $deal->lender->name.' Subtotals';
                    }
                    ?>
                </th>
                <th colspan="1">{{ 'Rows: '.count($deals_list) }}</th>
                <th style="text-align: left;width: 7%">${{ $actual_loan_amt }}</th>
                <th style="text-align: left;width: 7%"></th>
                <th style="text-align: left;width: 7%">${{$ABP_est_upfront_amt}}</th>
                <th style="text-align: left;width: 7%">${{ $ABP_actual_upfront_amt }}</th>
                <th style="text-align: left;width: 7%">${{ $ABP_upfront_diff }}</th>
                <th style="text-align: left;width: 7%">${{ $ABP_est_brokerage }}</th>
                <th style="text-align: left;width: 7%">${{ $ABP_actual_brokerage }}</th>
                <th style="text-align: left;width: 7%">${{ $brokerage_dif }}</th>
            </tr>
            <?php
            $total_actual_loan_amt+=$actual_loan_amt;
            $total_ABP_est_upfront_amt+=$ABP_est_upfront_amt;
            $total_ABP_actual_upfront_amt+=$ABP_actual_upfront_amt;
            $total_ABP_upfront_diff+=$ABP_upfront_diff;
            $total_ABP_est_brokerage+=$ABP_est_brokerage;
            $total_ABP_actual_brokerage+=$ABP_actual_brokerage;
            $total_brokerage_dif+=$brokerage_dif;
            ?>
            </thead>
        </table>
    @endforeach
@else
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
        <tr>
            <th style="width: 6%">Deal</th>
            <th style="width: 15%">Client</th>
            <th style="width: 15%">Lender</th>
            <th style="width: 10%">Broker Staff</th>
            <th style="width: 8%">Loan Amount</th>
            <th style="width: 4%">Date Settled</th>
            <th style="width: 7%">ABP Est. Upfront</th>
            <th style="width: 7%">Actual Upfront</th>
            <th style="width: 7%">Upfront Difference</th>
            <th style="width: 7%">Broker Est Brokerage</th>
            <th style="width: 7%">ABP Est. Brokerage</th>
            <th style="width: 7%">Actual Brokerage</th>
            <th style="width: 7%">Brokerage Difference</th>
        </tr>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif
@if(count($deals)>0)

    <table class="grand_total"  style="width: 100%">
        <tr>
            <th colspan="2"><?php
                echo 'Grand Total'
                ?></th>
            <th style="text-align: left;width: 7%">${{ $total_actual_loan_amt }}</th>
            <th style="text-align: left;width: 7%"></th>
            <th style="text-align: left;width: 7%">${{$total_ABP_est_upfront_amt}}</th>
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
