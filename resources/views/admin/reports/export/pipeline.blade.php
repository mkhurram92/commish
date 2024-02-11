<html lang="en">
<head>
    <title>Pipeline</title>
</head>
<body>
    <table class="row" style="margin-top: 50px;margin-bottom: 50px">
        <tbody>
            <tr>
                <td style="width: 25%"> <span style="width: 25%;display: inline-block">FM Direct Pipeline</span></td>
                <td> <span style="width: 60%;display: inline-block">For period from {{ $date_from.' to '.$date_to }}</span></td>
            </tr>
        </tbody>
    </table>
    @foreach($deals as $deal)
    <table style="width: 100%;margin-top: 20px" id="pipeline_report">
       <thead>
                <tr>
                    <th>Deal</th>
                    <th>Client</th>
                    <th>Lender</th>
                    <th>Product</th>
                    <th>Proposed Settlement</th>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Status Date</th>
                    <th>Broker Est Loan Amount</th>
                    <th>Broker Est Upfront</th>
                    <th>Broker Est Brokerage</th>
                </tr>
            </thead>
            <tbody>
            @foreach($deals as $deal)
                <tr>
                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->client->surname }}</td>
                    <td>{{ $deal->lender->name }}</td>
                    <td>{{ $deal->product->name }}</td>
                    <td>{{ $deal->proposed_settlement }}</td>
                    <td><?php
                        $earlier = new DateTime(date("Y-m-d",strtotime($deal->created_at)));
                        $later = new DateTime(date("Y-m-d"));
                        echo $abs_diff = $later->diff($earlier)->format("%a");
                        ?></td>
                    <td>{{ $deal->deal_status->name }}</td>
                    <td>{{ $deal->status_date }}</td>
                    <td>{{ $deal->broker_est_loan_amt??'$0' }}</td>
                    <td>{{ $deal->broker_est_upfront??'$0' }}</td>
                    <td>{{ $deal->broker_est_brokerage??'$0' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
    </body>
</html>
