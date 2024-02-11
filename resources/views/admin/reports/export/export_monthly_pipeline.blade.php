
<table class="table table-separate table-head-custom dataTable no-footer" id="pipeline_report">
    <thead>
    <tr>
        <th width="">Deal</th>
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
                ?>></td>
            <td>{{ $deal->deal_status->name }}</td>
            <td>{{ $deal->status_date }}</td>
            <td>{{ $deal->broker_est_loan_amount }}</td>
            <td>{{ $deal->broker_est_upfront }}</td>
            <td>{{ $deal->broker_est_brokerage }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
