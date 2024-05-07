@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    FM Direct Reports
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
                    <h5 class="card-title">FM Direct Filters</h5>
                    <div class="ml-auto">
                        <select class="form-control" name="report_type" id="report_type">
                            {{--
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='approved_in_principle'?'selected':'' }} value="approved_in_principle">Approved in Principle</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='birthdays_list'?'selected':'' }} value="birthdays_list">Birthday List</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='clients_list'?'selected':'' }} value="clients_list">Clients List</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='deal_tasks'?'selected':'' }} value="deal_tasks">Deals Tasks</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='outstanding-commissions'?'selected':'' }} value="outstanding-commissions">Outstanding Commissions</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='referrers_rating_summary'?'selected':'' }} value="referrers_rating_summary">Referrer's Rating Summary</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='upfront_outstanding'?'selected':'' }} value="upfront_outstanding">Upfront Outstanding</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='trail_outstanding'?'selected':'' }} value="trail_outstanding">Trail Outstanding</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='deals_dnp_report'?'selected':'' }} value="deals_dnp_report">Leads with DNP Status</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='deals_to_track'?'selected':'' }} value="deals_to_track">Deals to Track</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='sales'?'selected':'' }} value="sales">Sales</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='referrer_commission_upfront'?'selected':'' }} value="referrer_commission_upfront">Referrer Commission Upfront</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='deals_history'?'selected':'' }} value="deals_history">Deals History</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='snapshot_of_all_deals'?'selected':'' }} value="snapshot_of_all_deals">Snapshot of All Deals</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='commission_ranking'?'selected':'' }} value="commission_ranking">Client Commission Ranking</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='trail_discrepancies'?'selected':'' }} value="trail_discrepancies">Trail Discrepancies</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='upfront_discrepancies'?'selected':'' }} value="upfront_discrepancies">Upfront Discrepancies</option>
                            --}}
                            
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='deals_settled'?'selected':'' }} value="deals_settled">Deals Settled</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='pipeline'?'selected':'' }} value="pipeline">Pipeline</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='monthly_pipeline'?'selected':'' }} value="monthly_pipeline">Monthly Pipeline</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='referrers_rating_summary'?'selected':'' }} value="referrers_rating_summary">Referrer Rating Summary</option>
                            <option {{ isset($_GET['report_type'])&&$_GET['report_type']=='outstanding-commissions'?'selected':'' }} value="outstanding-commissions">Commission Outstanding</option>

                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            
                            @if(isset($_GET['report_type'])&&$_GET['report_type']=='pipeline')
                                @include('admin.reports.fm_direct.filters.pipeline_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='monthly_pipeline')
                                @include('admin.reports.fm_direct.filters.monthly_pipeline_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='deals_settled')
                                @include('admin.reports.fm_direct.filters.deals_settled_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='clients_list')
                                @include('admin.reports.fm_direct.filters.clients_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='birthdays_list')
                                @include('admin.reports.fm_direct.filters.birthday_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='referrers_rating_summary')
                                @include('admin.reports.fm_direct.filters.referrers_rating_summary_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='deal_tasks')
                                @include('admin.reports.fm_direct.filters.deal_tasks_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='upfront_outstanding')
                                @include('admin.reports.fm_direct.filters.upfront_outstanding_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='trail_outstanding')
                                @include('admin.reports.fm_direct.filters.trail_outstanding_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='outstanding-commissions')
                                @include('admin.reports.fm_direct.filters.outstanding_commissions_filter')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='deals_dnp_report')
                                @include('admin.reports.fm_direct.filters.deals_dnp_report_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='deals_to_track')
                                @include('admin.reports.fm_direct.filters.deals_to_track_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='sales')
                                @include('admin.reports.fm_direct.filters.sales_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='referrer_commission_upfront')
                                @include('admin.reports.fm_direct.filters.referrer_commission_upfront_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='deals_history')
                                @include('admin.reports.fm_direct.filters.deals_history_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='snapshot_of_all_deals')
                                @include('admin.reports.fm_direct.filters.snapshot_of_all_deals_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='commission_ranking')
                                @include('admin.reports.fm_direct.filters.commission_ranking_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='trail_discrepancies')
                                @include('admin.reports.fm_direct.filters.trail_discrepancies_filters')
                            @elseif(isset($_GET['report_type'])&&$_GET['report_type']=='upfront_discrepancies')
                                @include('admin.reports.fm_direct.filters.upfront_discrepancies_filters')
                            @else
                    
                                @include('admin.reports.fm_direct.filters.deals_settled_filters')
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
            window.location.href='{{ url("admin/reports/fm-direct?report_type=") }}'+$(this).val()
        })
    </script>
@endsection
