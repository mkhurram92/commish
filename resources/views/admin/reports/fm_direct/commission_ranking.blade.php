<html lang="en">
<head>
    <title>Client Commission Rankings</title>

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
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">Client Commission Rankings</span></td>
        <td><span
                style="width: 60%;font-size:13px;font-weight: bold;">For period from {{ $date_from.' to '.$date_to }}</span>
        </td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px">
    <thead class="thead_style">
    <tr>
        <th style="width: 40%">Client</th>
        <th style="width: 20%">Upfront</th>
        <th style="width: 20%">Trail</th>
        <th style="width: 20%">Total</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @if(count($deals)>0)
        @foreach($deals as $deal)
            @if($deal->upfront != null || $deal->trail != null )
                <tr>
                    <?php $total = $deal->upfront + $deal->trail?>
                    <td>{{ $deal->client->id??'' }} {{ $deal->client->first_name??''  }} {{ $deal->client->surname??''  }}</td>
                    <td>${{ $deal->upfront??0.00 }}</td>
                    <td>${{ $deal->trail??0.00 }}</td>
                    <td>${{ $total??0.00  }}</td>
                </tr>
            @endif
        @endforeach
    @endif
    </tbody>
</table>
</body>
</html>
