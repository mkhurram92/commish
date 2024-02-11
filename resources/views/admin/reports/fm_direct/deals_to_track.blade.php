<html lang="en">
<head>
    <title>Deals To Track</title>
    <style>
        table {
            border: 0;
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .page-break {
            page-break-after: always;
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
        <td style="width: 25%"><span style="font-size: 18px;font-weight: bold;">Deals To Track</span></td>
        <td><span style="width: 60%;font-weight: bold;">For period from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>

    <table style="margin-top: 5px; ">
        <thead class="thead_style">
        <tr>
            <th style="width: 3%">Deal</th>
            <th style="width: 15%">Client</th>
            <th style="width: 10%">Product</th>
            <th style="width: 8%">Loan Amt est.</th>
            <th style="width: 10%">Current Status</th>
            <th style="width: 7%">Lead</th>
            <th style="width: 7%">LE-EN Age (days)</th>
            <th style="width: 7%">Enquiry</th>
            <th style="width: 10%">EN-00 Age (days)</th>
            <th style="width: 10%">Application</th>
            <th style="width: 10%">00-01 Age (days)</th>
            <th style="width: 10%">Submitted</th>
            <th style="width: 10%">01-02 Age (days)</th>
            <th style="width: 10%">AIP</th>
            <th style="width: 10%;"> ->API Age (days)</th>

            <th style="width: 10%">Conditional</th>
            <th style="width: 10%"> 02-03 Age (days)</th>
            <th style="width: 10%">Formal</th>
            <th style="width: 10%"> 03-04 Age (days)</th>
            <th style="width: 10%">DNP</th>
            <th style="width: 10%"> ->DNP Age (days)</th>
            <th style="width: 10%">Settled</th>
            <th style="width: 10%">Total Age Days</th>

        </tr>
        </thead>
        <tbody class="body_class">
        @if(count($deals)>0)
            @foreach($deals as $deal)
                <?php
                $deals_status = DB::select("SELECT * FROM deal_status_updates WHERE deal_id = '" . $deal->id . "'");
                //            $deals_list = \App\Models\DealStatusUpdate::query()->select('deals.*')->whereStatus(5)->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
                //            $date_from = date("Y-m-d", strtotime($deal->year . "-" . $deal->month . "-01"));
                //            $date_to = date("Y-m-t", strtotime($deal->year . "-" . $deal->month . "-01"));
                //            $deals_list->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')));
                //            $deals_list->where('created_at', '<=', date('Y-m-t H:i:s', strtotime($date_to . ' 23:59:59')));
                //            $deals_list = $deals_list->get();
                //            ?>
                {{--            @foreach($deals_list as $deal_list)--}}
                <tr style="page-break-after: always;">

                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->client->surname }}</td>
                    <td>{{ $deal->product->name??$deal->product_id }}</td>
                    <td>${{ $deal->actual_loan??$deal->product_id }}</td>
                    <td>{{$deal->status??''}} {{ $deal->deal_status->name??'' }}</td>
                    <td>{{ $deal->created_at!=''?date('m/d/Y',strtotime($deal->created_at)):'' }}</td>
                    <?php
                    $previous_date = $deal->created_at;
                    $total_days = 0;
                    $count = 0;
                    ?>
                    @foreach($deals_status as $status)
                        <td><?php
                            $count++;
                            $date = date_create($previous_date);
                            $earlier = strtotime(date_format($date, 'Y-m-d'));
                            $date = date_create($status->updated_at);
                            $later = strtotime(date_format($date, 'Y-m-d'));
                            $datediff = $later - $earlier;
                            echo $abs_diff = round($datediff / (60 * 60 * 24)) ?? 0;
                            $total_days += $abs_diff;
                            $previous_date = $status->updated_at;
                            $count++;
                            ?></td>
                        <td>{{$status->updated_at!=''?date('m/d/Y',strtotime($status->updated_at)):''}}</td>
            @endforeach
            <?php  for ($i = 0; $i < (16 - $count); $i++) { ?>
            <td>0</td>
            <?php  }  ?>
            <td>{{$total_days}}</td>

            </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</body>
</html>
