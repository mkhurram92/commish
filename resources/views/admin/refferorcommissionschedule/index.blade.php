@extends('layout.main')
@push('style-section')
@endpush
@section('title')
Referrer::commission Schedule
@endsection
@section('page_title_con')

<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">
            Referrer::commission Schedule
        </h4>
    </div>
    <div class="page-rightheader ml-auto d-lg-flex d-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

            <li class="breadcrumb-item active" aria-current="page">                             Referrer::commission Schedule
            </li>
        </ol>
    </div>
</div>
@endsection

@section('body')
<div class="main-card mb-3 card add_form_card">
    <div class="card-header">
        <h5 class="card-title">Add New</h5>
    </div>
    <div class="card-body">

        <div>
            <form class="" id="addForm" method="post" action="">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="lender_id" class="mr-sm-2">Referrer</label>
                                <select name="lender_id" id="lender_id"  class="form-control">
                                    <option value="">Select Referrer</option>
                                    @foreach($lenders as $lender)
                                        <option value="{{$lender->id}}">{{$lender->lender_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="product_id" class="mr-sm-2">Product</label>
                                <select name="product_id" id="product_id" placeholder="" class="form-control">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="commission_type_id" class="mr-sm-2">Commission Type</label>
                                <select name="commission_type_id" id="commission_type_id" placeholder="" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach($commission_types as $commission_type)
                                        <option value="{{$commission_type->id}}">{{$commission_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="form-label">Flat Rate</label>
                                    <input type="text" name="flat_rate" id="flat_rate" placeholder="0.00"
                                           class="form-control number-input" />
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">% Rate</label>
                                    <input type="text" name="per_rate" id="per_rate" placeholder="0.00%"
                                           class="form-control number-input" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">Note</label>
                                <textarea name="note" id="note" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
            <div class="divider"></div>

        </div>
    </div>
</div>

<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="col-sm-12">
            <div class="">
                <table style="width: 100%" id="TableData" class="table table-hover table-striped table-bordered"
                    data-toggle="table" data-height="500" data-show-columns="true"
                    data-sAjaxSource="{{ route('admin.refferorcommissionschedule.getrecords') }}"
                    data-aoColumns='{"mData": "Index no"},{"mData": "Referrer"},{"mData": "Product"},{"mData": "Commis Type"},{"mData": "Flat Rate"},{"mData": "Per Rate"},{"mData": "Note"},{"mData": "Added On"},{"mData": "Modified On"},{"mData": "Action"}'>
                    <thead>
                        <tr>
                            <th class="no-sort">#</th>
                            <th class="noVis">Referrer</th>
                            <th class="noVis">Product</th>
                            <th class="noVis">Commis. Type</th>
                            <th class="noVis">Flat Rate</th>
                            <th class="noVis">% Rate</th>
                            <th class="noVis">Note</th>
                            <th class="noshow">Added On</th>
                            <th class="">Modified On</th>
                            <th style="width: 15%;">Action</th>

                        </tr>
                    </thead>
                    <tbody id="TableDataTbody"></tbody>
                    <tfoot>
                        <tr>
                            <th class="no-sort">#</th>
                            <th class="noVis">Referrer</th>
                            <th class="noVis">Product</th>
                            <th class="noVis">Commis. Type</th>
                            <th class="noVis">Flat Rate</th>
                            <th class="noVis">% Rate</th>
                            <th class="noVis">Note</th>
                            <th class="noshow">Added On</th>
                            <th class="">Modified On</th>
                            <th style="width: 15%;">Action</th>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(() => {

        jQuery('#lender_id').val('').select2({

            placeholder: "Select Referrer",
        });
        jQuery('#product_id').val('').select2({

            placeholder: "Select Product",
        });
        jQuery('#commission_type_id').val('').select2({
            
            placeholder: "Select Type",
        });

            $("#addForm").validate({
                rules: {

                    lender_id: {
                        required: true
                    },product_id: {
                        required: true
                    },commission_type_id: {
                        required: true
                    }

                },
                messages: {
                    lender_id: {
                        required: "Please select Referrer"
                    },product_id: {
                        required: "Please select product"
                    },commission_type_id: {
                        required: "Please select commission type"
                    }
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },
                submitHandler: function(){

                    $.ajax({
                        url: "{{ route('admin.refferorcommissionschedule.post') }}",
                        type:'POST',
                        data:  {
                        "_token": "{{ csrf_token() }}",
                            "lender_id": jQuery('#lender_id').val(),
                            "product_id": jQuery('#product_id').val(),
                            "commission_type_id": jQuery('#commission_type_id').val(),
                            "flat_rate": jQuery('#flat_rate').val(),
                            "per_rate": jQuery('#per_rate').val(),
                            'note' : jQuery('#note').val()
                             },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#lender_id').val('').select2({

                                    placeholder: "Select Referrer",
                                });
                                jQuery('#product_id').val('').select2({

                                    placeholder: "Select Product",
                                });
                                jQuery('#commission_type_id').val('').select2({

                                    placeholder: "Select Type",
                                });
                                jQuery('#flat_rate').val('');
                                jQuery('#per_rate').val('');
                                jQuery('#note').val('');
                                hideLoader();
                                refreshTable({});
                            }else{
                                printErrorMsg(data.error);
                                hideLoader();
                            }
                        },error:function(jqXHR, textStatus, errorThrown)
                        {
                            errorMessage(jqXHR.responseText)

                        }
                    });
                    return false;
                }
            });
            refreshTable({});
        });

        function refreshTable(customArgs)
        {

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
                            var edit_row = '<a href="javascript:void(0)" data-id="'+value.id+'" onclick="return ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';
                            // var delete_row = '<a href="javascript:void(0)" data-id="'+value.id+'" onclick="return ' +
                            //     'deleteRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger" ' +
                                // 'title="Delete"><i title="Delete" class="pe-7s-trash btn-icon-wrapper"></i></a>';

                            var TempObj = {
                                "Index no" : value.id,
                                "Referrer" : value.lender_name,
                                "Product" : value.product_name,
                                "Commis Type" : value.commission_type_name,
                                "Flat Rate" : value.flat_rate,
                                "Per Rate" : value.per_rate,
                                "Note" : value.note,
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


        function editRecord(current)
        {
            var id = jQuery(current).attr('data-id');
            if(typeof id != "undefined" && parseInt(id) > 0)
            {
                showLoader();
                $.ajax({
                    url: "{{ route('admin.refferorcommissionschedule.getrecord') }}",
                    type:'POST',
                    data:  {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(data) {

                        if($.isEmptyObject(data.error)){
                            var payloadData = data.payload;
                            if(typeof payloadData != "undefined" && Object.keys(payloadData).length>0)
                            {
                                jQuery('#edit_lender_id').val(payloadData.refferor_id).select2({

                                    placeholder: "Select Referrer",
                                });
                                jQuery('#edit_product_id').val(payloadData.product_id).select2({

                                    placeholder: "Select Product",
                                });
                                jQuery('#edit_commission_type_id').val(payloadData.commission_type_id).select2({

                                    placeholder: "Select Type",
                                });
                                jQuery('#edit_id').val(payloadData.id);
                                jQuery('#edit_flat_rate').val(payloadData.flat_rate);
                                jQuery('#edit_per_rate').val(payloadData.per_rate);
                                jQuery('#edit_note').val(payloadData.note);
                                hideLoader();
                                jQuery('.edit-form-modal').modal('show');

                            }

                        }else{
                            printErrorMsg(data.error);
                            hideLoader();
                        }
                    },error:function(jqXHR, textStatus, errorThrown)
                    {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                });
            }

        }

        function deleteRecord(current)
        {
            var id = jQuery(current).attr('data-id');
            if(typeof id != "undefined" && parseInt(id) > 0)
            {
                showLoader();
                swal.fire({
                    title: "Are you sure?",
                    text: "You are going to delete record!",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                    icon: 'warning',
                }, function(isConfirm){
                    console.log(isConfirm)

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.refferorcommissionschedule.delete') }}",
                            type:'POST',
                            data:  {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(data) {

                                if($.isEmptyObject(data.error)){
                                    successMessage(data.success);
                                    refreshTable();
                                    hideLoader();

                                }else{
                                    printErrorMsg(data.error);
                                    hideLoader();
                                }
                            },error:function(jqXHR, textStatus, errorThrown)
                            {
                                errorMessage(jqXHR.responseText)
                                hideLoader();
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        hideLoader();
                    }
                });

            }

        }

</script>
@endpush

@section('modal-section')
<div class="modal fade edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="Edit Form" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> Commission Schedule :: Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id" value="" />
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="edit_lender_id" class="form-label">Referrer</label>
                                    <select name="lender_id" id="edit_lender_id"  class="form-control">
                                        <option value="">Select Referrer</option>
                                        @foreach($lenders as $lender)
                                            <option value="{{$lender->id}}">{{$lender->lender_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="edit_product_id" class="form-label">Product</label>
                                    <select name="product_id" id="edit_product_id" placeholder="" class="form-control">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="edit_commission_type_id" class="form-label">Commission Type</label>
                                    <select name="commission_type_id" id="edit_commission_type_id" placeholder="" class="form-control">
                                        <option value="">Select Type</option>
                                        @foreach($commission_types as $commission_type)
                                            <option value="{{$commission_type->id}}">{{$commission_type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">Flat Rate</label>
                                        <input type="text" name="flat_rate" id="edit_flat_rate" placeholder="0.00"
                                               class="form-control number-input" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">% Rate</label>
                                        <input type="text" name="per_rate" id="edit_per_rate" placeholder="0.00%"
                                               class="form-control number-input" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" id="edit_note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
            $("#editForm").validate({
                rules: {

                    lender_id: {
                        required: true
                    },product_id: {
                        required: true
                    },commission_type_id: {
                        required: true
                    }

                    },
                    messages: {
                    lender_id: {
                        required: "Please select Referrer"
                    },product_id: {
                        required: "Please select product"
                    },commission_type_id: {
                        required: "Please select commission type"
                    }
                    },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },
                submitHandler: function(){

                    showLoader();
                    $.ajax({
                        url: "{{ route('admin.refferorcommissionschedule.post') }}",
                        type:'POST',
                        data:  {
                            "_token": "{{ csrf_token() }}",
                            "lender_id": jQuery('#edit_lender_id').val(),
                            "product_id": jQuery('#edit_product_id').val(),
                            "commission_type_id": jQuery('#edit_commission_type_id').val(),
                            "flat_rate": jQuery('#edit_flat_rate').val(),
                            "per_rate": jQuery('#edit_per_rate').val(),
                            'note' : jQuery('#edit_note').val(),
                            "id" :jQuery('#edit_id').val(),

                        },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#edit_id').val('');

                                jQuery('.edit-form-modal').modal('hide');
                                jQuery('#edit_lender_id').val('').removeClass('is-invalid').removeClass('is-valid').select2({

                                    placeholder: "Select Referrer",
                                });
                                jQuery('#edit_product_id').val('').removeClass('is-invalid').removeClass('is-valid').select2({

                                    placeholder: "Select Product",
                                });
                                jQuery('#edit_commission_type_id').val('').removeClass('is-invalid').removeClass('is-valid').select2({

                                    placeholder: "Select Type",
                                });
                                jQuery('#edit_flat_rate').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#edit_per_rate').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#edit_note').val('').removeClass('is-invalid').removeClass('is-valid');
                                hideLoader();
                                refreshTable({});
                            }else{
                                printErrorMsg(data.error);
                                hideLoader();
                            }
                        },error:function(jqXHR, textStatus, errorThrown)
                        {
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    });
                    return false;
                }
            });
        })
</script>

<style>
    .select2-container--bootstrap4, .select2-container--default{
        width:100% !important;
    }
    </style>
@endsection
