@extends('layout.main')
@push('style-section')
@endpush
@section('title')
Relationships
@endsection
@section('page_title_con')
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">
            Missing Ref.
        </h4>
    </div>
    <div class="page-rightheader ml-auto d-lg-flex d-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                        <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" /></svg><span class="breadcrumb-icon"> Home</span></a></li>

            <li class="breadcrumb-item active" aria-current="page"> Missing Ref. No
            </li>
        </ol>
    </div>
</div>
@endsection
@section('body')

<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="col-sm-12">
            <div class="">
                <table style="width: 100%" id="TableData" class="table table-hover table-striped table-bordered" data-toggle="table" data-height="500" data-show-columns="true" data-sAjaxSource="{{ route('admin.deals.getdealMissingRecords') }}" data-aoColumns='{"mData": "Index no"},{"mData": "Loan Ref"},{"mData": "Funder"},{"mData": "Client"},{"mData": "Added On"},{"mData": "Modified On"}'>
                    <thead>
                        <tr>
                            <th class="no-sort">#</th>
                            <th class="noVis">Loan Ref</th>
                            <th class="noVis">Funder</th>
                            <th class="noVis">Client</th>
                            <th class="noVis">Added On</th>
                            <th class="noVis">Action</th>
                        </tr>
                    </thead>
                    <tbody id="TableDataTbody"></tbody>
                    <tfoot>
                        <tr>
                            <th class="no-sort">#</th>
                            <th class="noVis">Loan Ref</th>
                            <th class="noVis">Funder</th>
                            <th class="noVis">Client</th>
                            <th class="noVis">Added On</th>
                            <th class="noVis">Action</th>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-section')
@include('layout.datatable')
<script>
    $(document).ready(() => {
        refreshTable({});
    });

    function refreshTable(customArgs) {
        refreshdataTable(customArgs, function(response, fnCallback) {

            if (typeof response.payload != 'undefined') {

                var payloads = response.payload;
                var TempObj = {};
                TempObj['sEcho'] = payloads.sEcho;
                TempObj['iTotalRecords'] = payloads.iTotalRecords;
                TempObj['iTotalDisplayRecords'] = payloads.iTotalDisplayRecords;
                var aaDataArray = [];

                if (payloads.aaData.length > 0) {


                    jQuery.each(payloads.aaData, function(key, value) {
                        var edit_row = '<a href="javascript:void(0)" data-id="' + value.id + '" onclick="return ' +
                            'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                            'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';
                        // var delete_row = '<a href="javascript:void(0)" data-id="'+value.id+'" onclick="return ' +
                        //     'deleteRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger" ' +
                        //     'title="Delete"><i title="Delete" class="pe-7s-trash btn-icon-wrapper"></i></a>';

                        var TempObj = {
                            "Index no": value.id
                            , "Loan Ref": value.loan_ref
                            , "Funder": value.funder
                            , "Client": value.client
                            , "Added On": value.formated_created_at
                            , "Modified On": value.formated_updated_at
                            , "Action": edit_row
                        , };
                        aaDataArray.push(TempObj);
                    })
                }
                TempObj['aaData'] = aaDataArray;
                fnCallback(TempObj);
            }
        }, function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {


        });

    }

</script>
@endpush

@section('modal-section')


@endsection
