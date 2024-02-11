@extends('layout.main')
@push('style-section')
@endpush
@section('title')
Brokers
@endsection
@section('page_title_con')
<div class="app-page-title mb-0">
    <div class="page-title-wrapper">
        <div class="page-title-heading">

            <div>
                Brokers Expenses
            </div>
        </div>

    </div>
</div>

@endsection
@section('body')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title float-right">
                    <a href="{{route('admin.brokerexp.add',encrypt($broker->id))}}" class="btn btn-success">Add</a>
                </h5>

                <div class="table-responsive">

                    <table style="width: 100%;max-width:none !important" id="TableData" class=" table-hover
                        table-striped
                        table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                        data-sAjaxSource="{{ route('admin.brokerexp.getrecords',encrypt($broker->id)) }}"
                        data-aoColumns='{"mData": "Index no"},{"mData": "Expense Type"},{"mData": "Order Date"},
                        {"mData": "Broker Charged"}, {"mData": "Broker Paid"}, {"mData": "Base Cost"},{"mData": "Broker Charge"}, {"mData": "Action"}'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expense Type</th>
                                <th>Order Date</th>
                                <th>Broker Charged</th>
                                <th>Broker Paid</th>
                                <th>Base Cost</th>
                                <th>Broker Charge</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Expense Type</th>
                                <th>Order Date</th>
                                <th>Broker Charged</th>
                                <th>Broker Paid</th>
                                <th>Base Cost</th>
                                <th>Broker Charge</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-section')

@include('layout.datatable')
<script>
    jQuery(document).ready(function(){
        refreshTable();
    });

        function refreshTable()
        {
            var customArgs = {};
            refreshdataTable(customArgs,function(response,fnCallback)
            {
                if(typeof response.payload != 'undefined')
                {
                    var payloads = response.payload;
                    var TempObj = {};
                    TempObj['sEcho'] = payloads.sEcho;
                    TempObj['iTotalRecords'] = payloads.iTotalRecords;
                    TempObj['iTotalDisplayRecords'] = payloads.iTotalDisplayRecords;
                    var aaDataArray = [];

                    if(payloads.aaData.length > 0 )
                    {
                        jQuery.each(payloads.aaData ,function(key,value)
                        {
                            var edit_row = '<a href="{{url('admin/broker-expenses/edit/')}}/'+value.encrypt_id+'/{{ encrypt($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Expense Type" : value.expense_type_id,
                                "Order Date" : value.ordered_date,
                                "Broker Charged" : value.broker_charged,
                                "Broker Paid" : value.broker_paid,
                                "Base Cost" : value.base_cost,
                                "Broker Charge" : value.broker_charge,
                                "Action" :  edit_row ,
                            };
                            aaDataArray.push(TempObj);
                        })
                    }
                    TempObj['aaData'] = aaDataArray;
                    fnCallback(TempObj);
                }
            },function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            });
        }
</script>
@endpush
