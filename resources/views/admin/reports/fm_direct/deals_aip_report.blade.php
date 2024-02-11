<html lang="en">
<head>
    <title>Deals AIP</title>

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
            <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">FM Direct Deals Settled</span></td>
            <td> <span style="width: 50%;font-weight: bold;">For period from {{ date('m/d/Y',strtotime($date_from)).' to '.date('m/d/Y',strtotime($date_to)) }}</span></td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;margin-top: 20px" >
        <thead class="thead_style">
        <tr>
            <th style="text-align: left">Deal</th>
            <th style="text-align: left">Client</th>
            <th style="text-align: left">Product</th>
            <th style="text-align: left">Status</th>
            <th style="text-align: left">Status Date</th>
            <th style="text-align: left">Exclude from tracking</th>
            <th style="text-align: left">Ref Id</th>
        </tr>
        </thead>
        <tbody class="body_class">
        @foreach($deals as $deal)
            <tr>
                <td>{{ $deal->id }}</td>
                <td>{{ $deal->client->surname }}</td>
                <td>{{ $deal->product->name }}</td>
                <td>{{ $deal->deal_status->name }}</td>
                <td>{{ date('m/d/Y',strtotime($deal->status_date)) }}</td>
                <td><input disabled type="checkbox" {{ $deal->exclude_from_tracking==1?'checked':'' }} /></td>
                <td>{{ $deal->loan_ref }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
