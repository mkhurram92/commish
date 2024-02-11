<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Broker Invoice</title>

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

        .gross_tota_trail tr th {
            font-family: system-ui, system-ui, sans-serif;
            border-collapse: separate;
            border-spacing: 5px 5px;
            text-align: left;
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
    
	 	
	 
    @if (count($brokers) > 0)
        @foreach ($brokers as $broker)
	<table style="margin-top: 5px;margin-bottom:20px;width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;">
						<?php
					$avatarUrl = url('assets/images/logo.jpeg');
$arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
$type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
$avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
$avatarBase64Data = base64_encode($avatarData);
$imageData = 'data:image/' . $type . ';base64,' . $avatarBase64Data;
					?>
					
					<img src="{{$imageData}}" style="width:600px;"><p style="margin:0px;text-align:right"><b>Level 5, 333 King William Street Adelaide SA 5000</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>ABN 32 513 077 269</b></p>
					</td>
            </tr>
            <tr>
			    <td>Recipient Created : <b>Tax Invoice</b></td>
			</tr>
            <tr>
                <td>From: {{ $date_from }} To: {{ $date_to }}</td>
            </tr>
			</tbody>
    </table>
            <table style="border:1px solid;padding:20px">
                <thead>
                    <tr>
                        <th>{{ $broker->deal->broker->trading ?? '' }}</th></tr>
					<tr><td>{{ $broker->deal->broker->business ?? '' }}</td></tr>
                     
                </thead>

            </table>
            <?php
            $broker_staffs = $broker_staffs_array[$broker->deal->broker_id];
            ?>

            <table style="width: 100%;margin-top: 5px">
                <thead class="thead_style">
                    <tr>
                        <th>Client</th>
                        <th>Institution</th>
                        <th>Loan Amount</th>
                        <th>Deal Id</th>
                        <th>Model</th>
                        <th>%</th>
                        <th>Amount</th>
                        <th>FMA</th>
                        <th>Referror</th>
                        <th>Broker</th>
                    </tr>
                </thead>
                <tbody class="body_class">
                    <?php
                        $total_actual_loan = 0;
                        $total_agg_amount = 0;
                        $total_total_amount = 0;
                        $total_broker_amount = 0;
                        $total_referror_amount = 0;
					
					 $total_actual_loan2 = [];
                        $total_agg_amount2 = [];
                        $total_total_amount2 = [];
                        $total_broker_amount2 = [];
                        $total_referror_amount2 = [];
					$older_broker_name=0;
                    ?> 
                    @foreach ($broker_staffs as $index => $broker_staff_obj)
					<?php
					$type_name='';
					$typeget=DB::Select("select * from commission_types where id='{$broker_staff_obj->type}'");
					if(count($typeget)>0){
					foreach($typeget as $typeget2){
					$type_name=$typeget2->name;	
					}
					}
					
					  $nameeee=$broker_staff_obj?->deal?->broker_staff ? $broker_staff_obj->deal->broker_staff->given_name . ' ' . $broker_staff_obj->deal->broker_staff->surname .' ( '.$type_name.' ) ' : '';
					 
					?>
                    @if ($nameeee!=$older_broker_name)
					<?php 
					    $total_actual_loan2[$nameeee] = 0;
                        $total_agg_amount2[$nameeee] = 0;
                        $total_total_amount2[$nameeee] = 0;
                        $total_broker_amount2[$nameeee] = 0;
                        $total_referror_amount2[$nameeee] = 0;
					if($older_broker_name!=0){
					?>
					<tr style="border-top:1px solid black">
                        <td></td>
                        <td style="width:12%"><b>Total</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_actual_loan2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_total_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_agg_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_referror_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_broker_amount2[$older_broker_name],2) }}</b></td>
                    </tr>
					<?php
					}
					$older_broker_name=$nameeee;
					
					 ?>
                            <tr style="border-bottom: 1px solid;padding-bottom: 5px">
                                <td style="border-bottom: 1px solid;padding-bottom: 5px" colspan="2">Broker Staff
                                </td>
                                <td style="border-bottom: 1px solid;padding-bottom: 5px;" colspan="8"><span
                                        style="background-color:#ffff99">  {{$nameeee}}</span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="width: 12%">
                                {{ $broker_staff_obj->deal->client ? $broker_staff_obj->deal->client->surname . ' ' . $broker_staff_obj->deal->client->preferred_name : '' }}
                            </td>
                            <td style="width: 11%">{{ $broker_staff_obj->deal->lender->code ?? '' }}</td>
                            <td style="width: 11%">$ {{ $broker_staff_obj->deal->actual_loan }}</td>
                            <td style="width: 11%">{{ $broker_staff_obj->deal->id }}</td>
                            @if ($broker_staff_obj->deal->commission_model == 1)
                                <td style="width: 11%">Fixed Rate</td>
                            @elseif($broker_staff_obj->deal->commission_model == 2)
                                <td style="width: 11%">Flat Rate</td>
                            @elseif($broker_staff_obj->deal->commission_model == 3)
                                <td style="width: 11%">Variable Rate</td>
                            @else
                                <td></td>
                            @endif
                            <td style="width: 11%">${{ $broker_staff_obj->deal->broker_split_agg_brk_sp_trail }}</td>
                            <td style="width: 11%">${{ $broker_staff_obj->total_amount }}</td>
                            <td style="width: 11%">${{ $broker_staff_obj->agg_amount }}</td>
                            <td style="width: 11%">${{ $broker_staff_obj->referror_amount }}</td>
                            <td style="width: 11%">${{ $broker_staff_obj->broker_amount }}</td>

                            <?php
                            $total_actual_loan += $broker_staff_obj->deal->actual_loan;
                            $total_agg_amount += $broker_staff_obj->agg_amount;
                            $total_total_amount += $broker_staff_obj->total_amount;
                            $total_referror_amount += $broker_staff_obj->referror_amount;
                            $total_broker_amount += $broker_staff_obj->broker_amount;
							
							
							      $total_actual_loan2[$nameeee] += $broker_staff_obj->deal->actual_loan;
                            $total_agg_amount2[$nameeee] += $broker_staff_obj->agg_amount;
                            $total_total_amount2[$nameeee] += $broker_staff_obj->total_amount;
                            $total_referror_amount2[$nameeee] += $broker_staff_obj->referror_amount;
                            $total_broker_amount2[$nameeee] += $broker_staff_obj->broker_amount;
                            ?>
                        </tr>
                    @endforeach
					<tr style="border-top:1px solid black">
                        <td></td>
                        <td style="width:12%"><b>Total</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_actual_loan2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_total_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_agg_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_referror_amount2[$older_broker_name],2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format((float)$total_broker_amount2[$older_broker_name],2) }}</b></td>
                    </tr>
                    <tr style="border-top:1px solid black">
                        <td></td>
                        <td style="width:12%"><b>Grand Total</b></td>
                        <td style="width:11%"><b>${{ number_format($total_actual_loan,2) }}</b></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"></td>
                        <td style="width:11%"><b>${{ number_format($total_total_amount,2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format($total_agg_amount,2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format($total_referror_amount,2) }}</b></td>
                        <td style="width:11%"><b>${{ number_format($total_broker_amount,2) }}</b></td>
                    </tr>
                </tbody>

            </table>
	<div class="pagebreak"> </div>

        @endforeach
    @else
        <table style="width: 100%;margin-top: 5px">
            <thead class="thead_style">
                <tr>
                    <th>Client</th>
                    <th>Institute</th>
                    <th>Loan Amount</th>
                    <th>Deal Id</th>
                    <th>Model</th>
                    <th>%</th>
                    <th>Amount</th>
                    <th>FMA</th>
                    <th>Broker</th>
                </tr>
            </thead>
            <tbody class="body_class">
            </tbody>
        </table>
    @endif

	<style>
	 
    .pagebreak {
        clear: both;
        page-break-after: always;
    }
 
	</style>
</body>

</html>
