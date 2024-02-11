@extends('layout.main')
@push('style-section')
@endpush
@section('title')
Deals
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Deals</h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">Deals</li>
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

                <form method="get">
                    <div class="row">
                        <!--<div class="col-md-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="entity_name" id="entity_name" class="form-control" placeholder="Company Name" />
                        </div>-->
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group ">
                                <label class="form-label">Business / Trading name</label>
                                <input name="trading" value="" id="trading" type="text"
                                       placeholder="Business / Trading name" class=" form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group ">
                                <label class="form-label">Surname</label>
                                <input name="surname" value="" id="surname" placeholder="Surname" type="text" class=" form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group ">
                                <label class="form-label">Given Name</label>
                                <input name="preferred_name" value="" id="preferred_name" placeholder="Given Name" type="text" class=" form-control">
                            </div>
                        </div>
                        <div class="clearfix clear"></div>
                        <div class="col-md-3 col-sm-12">
                            <label class="form-label">Lenders</label>
                            <select name="lender_id" id="lender_id" class="form-control">
                                <option value="">All Lenders</option>
                                @foreach ($lenders as $lender)
                                    <option value="{{$lender->id}}">{{$lender->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label class="form-label">Products</label>
                            <select name="product_id" id="product_id" class=" form-control">
                                <option value="">All Products</option>
                                @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label">Loan Amount</label>

                            <div class="form-group row">
                                <div class="col-6">

                                    <select name="loan_op" id="loan_op" class=" form-control"
                                    >
                                        <option value="">Operation</option>
                                        <option value="eq">=</option>
                                        <option value="gt">></option>
                                        <option value="gte">>=</option>
                                        <option value="lt"><</option>
                                        <option value="lte"><=</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input name="loan_amt" value="" id="loan_amt" placeholder="Loan Amount" type="text" class=" form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class=" form-control">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}">{{$status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--<div class="col-md-3">
                            <label class="form-label">From</label>
                            <input name="from_date" value="" id="from_date" type="text" placeholder="dd/mm/yyyy"
                                   data-toggle="datepicker"
                                   class=" form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">To</label>
                            <input name="to_date" value="" id="to_date" type="text" placeholder="dd/mm/yyyy"
                                   data-toggle="datepicker"
                                   class=" form-control">
                        </div>-->
                        <div class="col-md-3">
                                <label class="form-label">Deal ID</label>
                                <input name="deal_id" value="" id="deal_id" type="text"
                                       placeholder="Deal ID" class=" form-control">
                        </div>

                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <button type="button" onclick="return filterTable()" class="btn btn-primary
                                pull-right"><i class="fa  fa-search"></i> Search</button>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title float-right">
                    <a href="{{route('admin.deals.add')}}" class="btn btn-success">Add</a>
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Broker</th>
                                <th>Client</th>
                                <th>Lender</th>
                                <th>Loan Ref</th>
                                <th>Loan Amount</th>
                                <th>Status</th>
                                <th>Status Date</th>
                                <th>Product</th>
                                <!-- <th>Client Email</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-section')

@include('layout.datatable')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript"
        src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}">
</script>
<script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>

<script>
    var table = '';
    jQuery(document).ready(function(){
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.deals.getrecords') }}",
                data: function (d) {
                    d.type = $('#type').val(),
                    d.surname = $('#surname').val(),
                    d.trading = $('#trading').val(),
                    d.lender_id = $('#lender_id').val(),
                    d.product_id = $('#product_id').val(),
                    d.loan_op = $('#loan_op').val(),
                    d.loan_amt = $('#loan_amt').val(),
                    d.deal_id = $('#deal_id').val(),
                    d.status = $('#status').val(),
                    //d.entity_name = $('#entity_name').val(),
                    //d.from_date = $('#from_date').val(),
                    //d.to_date = $('#to_date').val(),
                    d.preferred_name = $('#preferred_name').val()
                    d.broker_trading = $('#broker_trading').val()
                    //d.ex_loan_repaid = ($('#ex_loan_repaid').is(':checked'))?1:'',
                    //d.has_trail = ($('#has_trail').is(':checked'))?1:''
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'broker_trading', name: 'broker_trading'},
                {data: 'fullname', name: 'fullname'},
                {data: 'lendername', name: 'lendername'},
                {data: 'loan_ref', name: 'loan_ref'},
                {data: 'actual_loan', name: 'actual_loan'},
                {data: 'status', name: 'st.name'},
                {data: 'proposed_settlement', name: 'deals.created_at'},
                {data: 'productname', name: 'productname'},
                // {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });


    function filterTable() {
        table.draw();
    }

</script>
<style>
    .broker_menu {
        width: 150px !important;
    }

    .broker_menu .nav-item {
        padding: 0px 20px 3px;
    }
</style>
@endpush

@section('modal-section')


    <div class="modal fade commission-modal" id="commission-modal" tabindex="-1" role="dialog" aria-labelledby="Deal
    Commissions"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Deal's Commission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="commission-modal-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    Commission Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Account No
                                </th>
                                <th>
                                    Period
                                </th>
                                <th>
                                    Commission
                                </th>
                                <th>
                                    GST
                                </th>
                                <th>
                                    Total Paid
                                </th>
                                <th>
                                    Settlement Date
                                </th>
                                <th>
                                    Payment No
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <script>
            var modalTable = '';
        function showCommissions(current)
        {
            var encId = jQuery(current).attr('data-id');
            modalTable = $('#commission-modal-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.deals.getcomdata') }}",
                    data: function (d) {
                        d.deal_id = encId

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'commission_type', name: 'commission_type'},
                    {data: 'client', name: 'client'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'period', name: 'period'},
                    {data: 'commission', name: 'commission'},
                    {data: 'gst', name: 'gst'},
                    {data: 'total_paid', name: 'total_paid'},
                    {data: 'settlement_date', name: 'settlement_date'},
                    {data: 'payment_no', name: 'payment_no'},
                ]
            });
            jQuery('#commission-modal').modal('show')
        }

    </script>
    @endsection
