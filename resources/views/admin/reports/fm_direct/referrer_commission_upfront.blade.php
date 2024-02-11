<html lang="en">
<head>
    <title>Referrer Commission Upfront</title>

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

    .thead_style tr th {
        border-bottom: 1px solid grey;
        font-family: Arial, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-weight: 800;
        letter-spacing: 2px;
        font-size: 13px;
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
        <td style="width: 40%"> <span style="font-size: 24px;font-weight: bold;font-family: Arial, Helvetica, sans-serif">Referrer Commission Upfront Summary</span></td>
        <td> <span style="width: 50%;">For Dates paid from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
<table style="margin-top: 15px;margin-bottom:5px;margin-left: auto;margin-right: auto;width: 50%;border-bottom: 1px solid">
    <tbody>
    <tr>
        <td style="text-align: center;font-size: 18px;font-weight: bold;margin-left: auto;margin-right: 20%">UPFRONT COMMISSION</td>
    </tr>
    </tbody>
</table>
@if(count($deals)>0)
        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th style="width: 40%">Referrer</th>
                <th  style="width: 15%">No.of Loans</th>
                <th  style="width: 15%">Loan Amount</th>
                <th  style="width: 15%">Consultant Amount Upfront</th>
                <th  style="width: 15%">Referrer Amount Upfront</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($deals as $deal)
                @if($deal->referrer != '' &&  $deal->referrer->surname != '')
                <tr>
                    <td>{{ $deal->referrer->surname??'' }}</td>
                    <td>{{ $deal->NumberOfLoansUpfront }}</td>
                    <td>${{ $deal->SumOfLoansUpfront??0 }}</td>
                    <td>${{ $deal->SumOfdea_BrokerageEst_ABP??0 }}</td>
                   <td>${{$deal->ReferrorUpfront??0}}</td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>

@else
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th>Referrer</th>
            <th>No.of Loans</th>
            <th>Loan Amount</th>
            <th>Consultant Amount Upfront</th>
            <th>Referrer Amount Upfront</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif


</body>
</html>
