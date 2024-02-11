<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Trail Commission not Received</title>

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
        <td style="width: 25%"> <span style="font-size: 18px">Loans with trail but not received from lender</span></td>
    </tr>
    </tbody>
</table>

@if(count($lenders)>0)
    
     <!--    <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;"></span></td>
                <td></td>
            </tr>
            </tbody>
        </table> -->
        <?php
        $broker_est_loan_amt=0;
        $broker_est_upfront=0;
        $broker_est_brokerage=0;

        ?>

        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th>Client</th>
                <th>Lender</th>
                <th>Loan Amount</th>
                <th>Product</th>
                <th>Deal Id</th>
                <th>Trail</th>
                <th>Settlement Date</th>

            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($lenders as $lender)
                    <?php
                    $deal_trail=\App\Models\DealCommission::where('type',12)->where('deal_id',$lender->id)->orderBy('id','desc')->first();
                    ?>
                    @if(!$deal_trail)
                       <?php $products = \App\Models\Products::where('id',$lender->product_id)->first();?>
                        <tr>
                            <td>{{ $lender->contact_id??'' }}</td>
                            <td>{{ $lender->name }}</td>
                            <td>${{ $lender->actual_loan??0.00 }}</td>
                            <td>{{ $products->name }}</td>
                            <td>{{ $lender->id }}</td>
                            <td>{{ $lender->broker_est_trail??0.00 }}</td>
                            <td>{{ $lender->proposed_settlement}}</td>
                        </tr>
                    @endif
             @endforeach    
            </tbody>
        </table>

   
@else

    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
                <th>Client</th>
                <th>Lender</th>
                <th>Loan Amount</th>
                <th>Product</th>
                <th>Deal Id</th>
                <th>Trail</th>
                <th>Settlement Date</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

</body>
</html>
