<html lang="en">
<head>
    <title>Fm Direct Leads to DNP</title>
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
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">FM Direct Leads with DNP Status</span></td>
        <td><span style="width: 60%;font-weight: bold;">For period from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 5px">
    <thead class="thead_style">
    <tr>
        <th style="width: 3%">Deal</th>
        <th style="width: 15%">Client</th>
        <th style="width: 10%">Product</th>
        <th style="width: 8%">Lead Date</th>
        <th style="width: 4%">Days</th>
        <th style="width: 7%">Calc Status</th>
        <th style="width: 7%">Status</th>
        <th style="width: 7%">Status Date</th>
    </tr>
    </thead>
    <tbody class="body_class">
    @if(count($deals)>0)
        @foreach($deals as $deal)
            <?php
             $deals_status = DB::select("SELECT * FROM deal_status_updates WHERE deal_id = '".$deal->id."'");
//            $deals_list = \App\Models\DealStatusUpdate::query()->select('deals.*')->whereStatus(5)->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
//            $date_from = date("Y-m-d", strtotime($deal->year . "-" . $deal->month . "-01"));
//            $date_to = date("Y-m-t", strtotime($deal->year . "-" . $deal->month . "-01"));
//            $deals_list->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')));
//            $deals_list->where('created_at', '<=', date('Y-m-t H:i:s', strtotime($date_to . ' 23:59:59')));
//            $deals_list = $deals_list->get();
//            ?>
{{--            @foreach($deals_list as $deal_list)--}}
                <tr>

                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->client->surname }}</td>
                    <td>{{ $deal->product->name??$deal->product_id }}</td>
                    <td>{{ $deal->proposed_settlement!=''?date('m/d/Y',strtotime($deal->proposed_settlement)):'' }}</td>
                    <td><?php
                        $earlier = new DateTime(date("Y-m-d", strtotime($deal->created_at)));
                        $later = new DateTime(date("Y-m-d"));
                        echo $abs_diff = $later->diff($earlier)->format("%a");
                        ?></td>
                    @foreach($deals_status as $status)
                        <td>{{$status->status_id}}</td>
                        <td>{{$status->status_id}} DNP</td>
                        <td>{{ $status->updated_at!=''?date('m/d/Y',strtotime($status->updated_at)):'' }}</td>
                    @endforeach
                </tr>
{{--            @endforeach--}}
        @endforeach
    @endif
    </tbody>
</table>



</body>
</html>
