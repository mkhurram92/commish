@extends('layout.main')
@section('title')
    Contact Search
@endsection

@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Reports</h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </div>
    </div>
    <!--End Page header-->

@endsection
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    {{-- <h5 class="card-title">Deals Search <a class="float-right" href="javascript:void(0)"
                            onclick="return resetFilter(this)">clear
                            Filter</a></h5> --}}

                    <form action="{{ route('admin.reports.export_pipeline_records') }}" method="POST" id="export_form" >
                        @csrf
                        <input name="export_type" id="export_type" type="hidden">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <label class="form-label">Group By</label>
                                <select class="form-control" name="group_by" id="group_by">
                                    <option value=""></option>
                                    <option value="lender">Lender</option>
                                    <option value="broker_staff">Broker Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">From</label>
                                <input name="from_date" value="" id="from_date" type="date"
                                       class=" form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">To</label>
                                <input name="to_date" value="" id="to_date" type="date"
                                       class=" form-control">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary filter pull-right"><i class="fa  fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info filter export pdf pull-right">Export PDF</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary filter export excel pull-right">Export Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">Pipeline</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="responsive" >
                                <table class="table table-responsive table-head-custom dataTable no-footer" id="pipeline_report">
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

                                    </tbody>
                                </table>
                            </div>
                            <!--end: Datatable-->
                        </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
@endsection
@push('script-section')

    @include('layout.datatable')
@section("scripts")

    <script>
        var data='';
        jQuery(document).ready(function(){
            data=$('#pipeline_report').DataTable({
                responsive: true,
                searching: false,
                paging:false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('admin.reports.get_pipeline_records') }}",
                    method: 'POST',
                    data: function (d) {
                        d._token= '{{ csrf_token() }}';
                        d.group_by=$( "select[name=group_by] option:checked" ).val();
                        d.from_date=$( "input[name=from_date]" ).val();
                        d.to_date=$( "input[name=to_date]" ).val();
                    }
                },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'client.surname', name: 'contact_search.surname'},
                {data: 'lender.name', name: 'lender.name'},
                {data: 'product.name', name: 'product.name'},
                {data: 'proposed_settlement', name: 'proposed_settlement'},
                {data: 'lender.name', name: 'lender.name'},
                {data: 'deal_status.name', name: 'deal_status.name'},
                {data: 'status_date', name: 'status_date'},
                {data: 'broker_est_loan_amt', name: 'broker_est_loan_amt'},
                {data: 'broker_est_upfront', name: 'broker_est_upfront'},
                {data: 'broker_est_brokerage', name: 'broker_est_brokerage'},
            ]
        });
        });
        $(function () {
            $('.filter').on('click',function(e){
                if($(this).hasClass('export')){
                    if($(this).hasClass('excel')){
                        $("#export_type").val("xlsx")
                    }else if($(this).hasClass('csv')){
                        $("#export_type").val("csv")
                    }else{
                        $("#export_type").val("pdf")
                    }
                    data.draw();
                    newSubmit()
                }else{
                    data.draw();
                }
            });
        })
        function newSubmit() {
// post the form in a new window
            document.forms.export_form.setAttribute("target", "_blank");
            document.forms.export_form.submit();

// set the form back to its original settings
            document.forms.export_form.setAttribute("target", "self");
        }
    </script>
@endsection


@endpush
