@extends('layout.main')
@section('title')
    Dashboard
@endsection

@section('page_title')
    Dashboard
@endsection
@section('body')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <i class="fe fe-send card-custom-icon text-success icon-dropshadow-success" style="font-size: 4rem"></i>
                    <p class="mb-2">Total Deals</p>
                    <h2 class="font-weight-bold mb-1" id="total_deals">0</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <i class="fe fe-users card-custom-icon text-primary icon-dropshadow-primary"
                        style="font-size: 4rem"></i>
                    <p class="mb-2">Total Customer</p>
                    <h2 class="font-weight-bold mb-1" id="total_customers">0</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <i class="fe fe-rotate-cw card-custom-icon text-info icon-dropshadow-info" style="font-size: 4rem"></i>
                    <p class="mb-2">Total Referrer</p>
                    <h2 class="font-weight-bold mb-1" id="total_referrers">0</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <i class="fe fe-codepen card-custom-icon text-purple icon-dropshadow-purple"
                        style="font-size: 4rem"></i>
                    <p class="mb-2">Total Broker</p>
                    <h2 class="font-weight-bold mb-1" id="total_brokers">0</h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-section')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $.ajax("dashboard-data", {
                    type: "GET"
                })
                .done(data => {
                    $("#total_deals").text(data?.deals);
                    $("#total_customers").text(data?.customers);
                    $("#total_referrers").text(data?.referrers);
                    $("#total_brokers").text(data?.brokers);
                })
        });
    </script>
@endpush
