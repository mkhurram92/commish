<html lang="en">
    <head>
        <title>Pipeline</title>
    </head>
    <body>
        <div>
            <div class="row" style="margin-top: 50px;margin-bottom: 50px;display: inline">
                <span style="width: 25%;display: inline-block">FM Direct AIPs</span>
                <span style="width: 60%;display: inline-block">For period from {{ $date_from.' to '.$date_to }}</span>
            </div>
            <table style="width: 100%;margin-top: 20px" id="pipeline_report">
                <thead style="border-bottom:1px solid;text-align: left ">
                    <tr style="text-align: left">
                        <th style="text-align: left">Deal</th>
                        <th style="text-align: left">Client</th>
                        <th style="text-align: left">Product</th>
                        <th style="text-align: left">Status</th>
                        <th style="text-align: left">Status Date</th>
                        <th style="text-align: left">Exclude from tracking</th>
                        <th style="text-align: left">Ref Id</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($deals as $deal)
                    <tr>
                        <td>{{ $deal->id }}</td>
                        <td>{{ $deal->client->surname }}</td>
                        <td>{{ $deal->product->name }}</td>
                        <td>{{ $deal->deal_status->name }}</td>
                        <td>{{ $deal->status_date }}</td>
                        <td>{{ $deal->exclude_from_tracking==1?'Yes':'No' }}</td>
                        <td>{{ $deal->loan_ref }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
