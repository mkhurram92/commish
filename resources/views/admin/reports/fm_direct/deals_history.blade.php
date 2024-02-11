<html lang="en">
<head>
    <title>Deals History</title>
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
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">Deals History</span></td>
        <td><span style="width: 60%;font-weight: bold;">For period from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px">
    <thead class="thead_style">
    <tr>
        <th style="width: 3%">Deal</th>
        <th style="width: 15%">Client</th>
        <th style="width: 10%">Lender</th>
        <th style="width: 8%">Product</th>
        <th style="width: 4%">Loan Amount</th>
        <th style="width: 7%">Status</th>
        <th style="width: 7%">Date</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @if(count($deals)>0)
        @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->client->surname }}</td>
                <td>{{$deal->lender->code??''}}</td>
                <td>{{ $deal->product->name??$deal->product_id }}</td>
                <td>${{$deal->actual_loan}}</td>
                <td>{{$deal->deal_status->status_code}} {{$deal->deal_status->name}}</td>
                <td>{{date('m/d/Y',strtotime($deal->updated_at))}}</td>
            </tr>
            {{--            @endforeach--}}
        @endforeach
    @endif
    </tbody>
</table>


</body>
</html>
