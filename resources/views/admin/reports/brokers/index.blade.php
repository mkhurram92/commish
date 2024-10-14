@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Broker Reports
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Reports
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page"> Reports
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card add_form_card">
                <div class="card-header">
                    <h5 class="card-title">Broker Filters</h5>
                    <div class="ml-auto">
                        <select class="form-control" name="report_type" id="report_type">
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='broker_invoices'?'selected':'' }} value="broker_invoices">Broker Invoices</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            @if(isset($_GET['report_type'])&&$_GET['report_type']=='broker_commission_monitoring')
                                @include('admin.reports.brokers.filters.broker_commission_monitoring_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='referrer_commission_summary')
                                @include('admin.reports.brokers.filters.referrer_commission_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='broker_invoices')
                                @include('admin.reports.brokers.filters.broker_invoice_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='broker_list')
                                @include('admin.reports.brokers.filters.broker_list_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='broker_staff_expiry')
                                @include('admin.reports.brokers.filters.broker_staff_expiry_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='performance_report')
                                @include('admin.reports.brokers.filters.performance_report_filter')
                            @else
                                @include('admin.reports.brokers.filters.broker_invoice_filter')
                              {{--  @include('admin.reports.brokers.filters.broker_certifications_filter')--}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('reports_script')
    <script>
        $("#report_type").on("change",function (){

            window.location.href='{{ url('admin/reports/broker?report_type=') }}'+$(this).val()
        })
    </script>
@endsection
