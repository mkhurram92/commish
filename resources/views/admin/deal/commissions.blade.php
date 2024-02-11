@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Commissions
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Commissions
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item " aria-current="page">
                    <a href="{{route('admin.deals.list')}}">Deals</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Commissions
                </li>
            </ol>
        </div>
    </div>

@endsection
@section('body')
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                {{-- <h5 class="card-title">Deals Search <a class="float-right" href="javascript:void(0)"
                        onclick="return resetFilter(this)">clear
                        Filter</a></h5> --}}

                <form method="post" action="{{route('admin.deals.importcommission')}}" onsubmit="return saveForm
                (this)" id="import_csv_form" enctype="multipart/form-data">
                    @csrf
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="file" name="import_csv" id="import_csv" accept=".csv, application/vnd
                                .openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,
                                text/csv" />
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" id="finish-btn"
                                        class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-success">
                                    Import
                                </button>
                            </div>
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
                                <th>Commission Type</th>
                                <th>Funder</th>
                                <th>Period</th>
                                <th>Rate</th>
                                <th>Commission</th>
                                <th>GST</th>
                                <th>Total Paid</th>
                                <th>Referrer</th>
                                <th>Sattlement Date</th>
                                <th>Modified On</th>
                                <th>Payment No.</th>
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
<script>

    function saveForm(current)
    {

        showLoader();
        let formData = new FormData($('#import_csv_form')[0]);
        let file = $('#import_csv')[0].files[0];
        formData.append('file', file, file.name);
        $.ajax({
            url: jQuery(current).attr('action'),
            type:'POST',
            data:  formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function(data) {

                if(!$.isEmptyObject(data.error)){
                    printErrorMsg(data.error);
                    hideLoader();

                }else if(!$.isEmptyObject(data.errors)){
                    printErrorMsg(data.errors);
                    hideLoader();
                }else{
                    console.log(data);
                    if(data.success == 1){
                        successMessage('Record uploaded successfully!');
                    }else{
                       // successMessage(data.success);
                        //successMessage("Good job!", "You clicked the button!");
                        swal({
                            title: "Some missing ref. No found!",
                            text: "Please visit missing import records page for more information!",
                            icon: "success",
                        });

                        setTimeout(function () {
                            //window.location = "{{route('admin.contact.list')}}"
                            window.location = "{{url('admin/deals/deal-missing')}}";
                        }, 3000);

                        //{{ route('admin.deals.getdealMissingRecords') }}
                    }
                    //successMessage(data.success);
                    setTimeout(function(){
                   //     location.reload();
                    },3000);
                    hideLoader();

                }
            },error:function(jqXHR, textStatus, errorThrown)
            {
                if(IsJsonString(jqXHR.responseText))
                {
                    var respo =JSON.parse(jqXHR.responseText);
                    errorMessage(respo.message);
                    printErrorMsg(respo.errors)
                    hideLoader();
                }else{
                    errorMessage(jqXHR.responseText)
                    hideLoader();
                }
            }
        });
        return false;
    }

    var table = '';
    jQuery(document).ready(function(){
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.deals.getcommissions') }}",
                data: function (d) {

                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'commission_type', name: 'commission_type'},
                {data: 'bank', name: 'funder'},
                {data: 'period', name: 'period'},
                {data: 'rate', name: 'rate'},
                {data: 'commission', name: 'commission'},
                {data: 'gst', name: 'gst'},
                {data: 'total_paid', name: 'total_paid'},
                {data: 'referrer', name: 'referrer'},
                {data: 'settlement_date', name: 'settlement_date'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'payment_no', name: 'payment_no'}

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
    .swal-modal .swal-text {
        text-align: center;
    }
</style>
@endpush
