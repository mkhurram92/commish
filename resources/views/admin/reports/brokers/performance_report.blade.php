<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Performance Report</title>

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
    }.grand_total tr th {
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
        <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Performance</span></td>
        <td> <span style="width: 60%;font-weight: bold;">Grouped By {{ $group_by }} For period from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
@if(count($deals)>0)
    @foreach($deals as $deal)
        <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;"><?php
                        if($group_by=='Broker'){
                            echo $deal->broker->surname??'';
                        }else if($group_by=='Product'){
                            echo $deal->product->name??'';
                        }else if($group_by=='BrokerStaff'){
                            echo $deal->broker->surname??'';
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
        $deals_list=\App\Models\Deal::select('deals.*')->with(['lender','client','deal_status','product','broker_staff']);
        if($group_by=='Broker'){
            $deals_list->where('broker_id',$deal->broker_id);
        }else if($group_by=='Product'){
            $deals_list->where('product_id',$deal->product_id);
        }else if($group_by=='BrokerStaff'){
            $deals_list->where('broker_staff_id',$deal->broker_staff_id);
        }else if($group_by=='Status'){
            $deals_list->where('status',$deal->status);
        }else{
            $deals_list->where('lender_id',$deal->lender_id);
        }
        if(!empty($date_from)){
            $deals_list->where('created_at','>=', date('Y-m-d H:i:s',strtotime($date_from.' 00:00:00')));
        }
        if(!empty($date_to)){
            $deals_list->where('created_at','<=', date('Y-m-d H:i:s',strtotime($date_to.' 23:59:59')));
        }
        $deals_list=$deals_list->get();
        $broker_est_loan_amt=0;
        $broker_est_upfront=0;
        $broker_est_brokerage=0;
        ?>

        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th style="width: 3%">Deal</th>
                <th style="width: 20%">Broker</th>
                <th style="width: 20%">Lender</th>
                <th style="width: 20%">Loan Amount</th>
                <th style="width: 20%">Product</th>
                <th style="width: 7%">Upfront</th>
                <th style="width: 7%">Trail</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($deals_list as $deal_list)
                <tr>
                    <td>{{ $deal_list->id }}</td>
                    <td>{{ $deal_list->broker->surname }}</td>
                    <td>{{ $deal_list->lender->name }}</td>
                    <td>${{ $deal_list->broker_est_loan_amt??'0' }}</td>
                    <td>{{ $deal_list->product->name }}</td>
                    <td>${{$deal_list->broker_est_upfront??'0' }}</td>
                    <td>${{$deal_list->broker_est_trail??'0' }}</td>
                </tr>
                <?php
                $broker_est_loan_amt+=$deal_list->broker_est_loan_amt;
                $broker_est_brokerage+=$deal_list->broker_est_brokerage;
                $broker_est_upfront+=$deal_list->broker_est_upfront;
                ?>
            @endforeach
            </tbody>
            <thead class="subtotal">
            <tr>
                <th colspan="3"><?php
                    if($group_by=='Broker'){
                        echo $deal->broker->surname.' Subtotals';
                    }else if($group_by=='Product'){
                        echo $deal->product->name.' Subtotals';
                    }else if($group_by=='BrokerStaff'){
                        echo $deal->broker_staff->surname??''.' Subtotals';
                    }else if($group_by=='Status'){
                        echo $deal->deal_status->name.' Subtotals';
                    }else{
                        echo $deal->lender->name.' Subtotals';
                    }
                    ?></th>
                <th style="text-align: left;width: 7%">${{ $broker_est_loan_amt }}</th>
                <th style="text-align: left;width: 7%"></th>
                <th style="text-align: left;width: 7%">${{$broker_est_upfront}}</th>
                <th style="text-align: left;width: 7%">${{ $broker_est_brokerage }}</th>
            </tr>

            </thead>
        </table>

    @endforeach
@else
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th style="width: 3%">Deal</th>
            <th style="width: 15%">Client</th>
            <th style="width: 15%">Lender</th>
            <th style="width: 10%">Product</th>
            <th style="width: 10%">Broker Staff</th>
            <th style="width: 8%">Proposed Settlement</th>
            <th style="width: 4%">Day</th>
            <th style="width: 7%">Status</th>
            <th style="width: 7%">Status Date</th>
            <th style="width: 7%">Broker Est Loan Amount</th>
            <th style="width: 7%">Broker Est Upfront</th>
            <th style="width: 7%">Broker Est Brokerage</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

</body>
</html>
