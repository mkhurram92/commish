<html lang="en">
<head>
    <title>Snapshot of All Deals</title>

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
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">Snapshot of All Deals</span></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px">
    <thead class="thead_style">
    <tr>
        <th style="width: 3%">Deal</th>
        <th style="width: 15%">Broker</th>
        <th style="width: 10%">Client ID</th>
        <th style="width: 10%">Client Name</th>
        <th style="width: 15%">Lender</th>
        <th style="width: 8%">Loan Ref</th>
        <th style="width: 4%">Loan Amount</th>
        <th style="width: 7%">Status</th>
        <th style="width: 7%">Date Settled</th>
        <th style="width: 7%">Product</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @if(count($deals)>0)
        @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->broker->trading??'' }}</td>
                <td>{{ $deal->client->id??'' }}</td>
                <td>{{ $deal->client->first_name??''  }} {{ $deal->client->surname??''  }}</td>
                <td>{{ $deal->lender->code??'' }}</td>
                <td>{{ $deal->loan_ref??'' }}</td>
                <td>${{ $deal->actual_loan??0.00 }}</td>
                <td>0{{ $deal->deal_status->status_code??'' }} {{ $deal->deal_status->name??'' }}</td>
                <td>{{$deal->proposed_settlement}}</td>
                <td>{{ $deal->product->name??'' }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
</body>
</html>
