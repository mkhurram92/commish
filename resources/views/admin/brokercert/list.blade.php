@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Brokers Certifications
@endsection
@section('page_title_con')
<div class="app-page-title mb-0">
    <div class="page-title-wrapper">
        <div class="page-title-heading">

            <div>
                Brokers Certifications :: {{$broker->given_name}}
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
                    <a href="{{route('admin.brokercert.add', encrypt($broker->id))}}" class="btn btn-success">Add</a>
                </h5>

                <div class="table-responsive">

                    <table style="width: 100%;max-width:none !important" id="TableData" class=" table-hover
                        table-striped
                        table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                        data-sAjaxSource="{{ route('admin.brokercert.getrecords',encrypt($broker->id)) }}"
                        data-aoColumns='{"mData": "Index no"},{"mData": "Type"},{"mData": "Required"}, {"mData":
                        "Held"},{"mData": "Expiry Date"},{"mData": "Added On"},{"mData": "Modified On"},{"mData":
                        "Action"}'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type </th>
                                <th>Required</th>
                                <th>Held</th>
                                <th>Expiry Date</th>
                                <th>Added On</th>
                                <th>Modified On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Type </th>
                                <th>Required</th>
                                <th>Held</th>
                                <th>Expiry Date</th>
                                <th>Added On</th>
                                <th>Modified On</th>
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
        })

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
                            var edit_row = '<a href="{{url('admin/broker-certifications/edit/')}}/'+value
                                    .encrypt_id+'/{{ encrypt
                            ($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Type" : value.certificate_name,
                                "Required" : value.required_display,
                                "Held" : value.held_display,
                                "Expiry Date" : value.expiry_date,
                                "Added On" : value.formated_created_at,
                                "Modified On" : value.formated_updated_at,
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
