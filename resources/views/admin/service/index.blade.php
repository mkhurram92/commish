@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Services
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Services
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">                             Services
                </li>
            </ol>
        </div>
    </div>


@endsection

@section('body')
    <div class="main-card mb-3 card add_form_card">
        <div class="card-body">
            <h5 class="card-title">Add New</h5>
            <div>
                <form class="form-inline" id="addForm" method="post" action="">
                    <div class="col-sm-4">
                        <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                            <label for="name" class="mr-sm-2">Name</label>
                            <input name="name" id="name" placeholder="" type="text" class="form-control">
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
                   <table style="width: 100%"  id="TableData" class="table table-hover table-striped table-bordered"
                           data-toggle="table" data-height="500" data-show-columns="true" data-sAjaxSource="{{ route('admin.service.getrecords') }}"
                           data-aoColumns='{"mData": "Index no"},{"mData": "Name"},{"mData": "Added On"},{"mData": "Modified On"},{"mData": "Action"}'>
                       <thead>
                       <tr>
                           <th class="no-sort">#</th>
                           <th class="noVis">Name</th>
                           <th class="noshow">Added On</th>
                           <th class="">Modified On</th>
                           <th style="width: 15%;">Action</th>

                       </tr>
                       </thead>
                       <tbody id="TableDataTbody"></tbody>
                       <tfoot>
                       <tr>
                           <th class="no-sort">#</th>
                           <th class="noVis">Name</th>
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
    <script>
        $(document).ready(() => {
            $("#addForm").validate({
                rules: {

                    name: {
                        required: true,
                        minlength: 2,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Your name must consist of at least 2 characters",
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
                        url: "{{ route('admin.service.post') }}",
                        type:'POST',
                        data:  {
                        "_token": "{{ csrf_token() }}",
                            "name": jQuery('#name').val()
                             },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#name').val('').removeClass('is-invalid').removeClass('is-valid');
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
                            //     'title="Delete"><i title="Delete" class="pe-7s-trash btn-icon-wrapper"></i></a>';

                            var TempObj = {
                                "Index no" : value.id,
                                "Name" : value.name,
                                "Added On" : value.formated_created_at,
                                "Modified On" :value.formated_updated_at,
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
                    url: "{{ route('admin.service.getrecord') }}",
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
                                jQuery('#edit_id').val(payloadData.id);
                                jQuery('#edit_name').val(payloadData.name);
                                hideLoader();
                                jQuery('.edit-form-modal').modal('show');
                                refreshTable({});
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
                            url: "{{ route('admin.service.delete') }}",
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
<div class="modal fade edit-form-modal" tabindex="-1" role="dialog"
     aria-labelledby="Edit Form" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Contact Role :: Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post"  action="" id="editForm">
            <div class="modal-body">

                    <input type="hidden" name="edit_id" id="edit_id" value=""/>
                    <div class="col-sm-12">
                        <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                            <label for="edit_name" maxlength="255"  class="mr-sm-2 font-weight-bold">Name</label>
                            <input name="name" id="edit_name" placeholder="" type="text" class="form-control">
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

                    name: {
                        required: true,
                        minlength: 2,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Your name must consist of at least 2 characters",
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
                        url: "{{ route('admin.service.update') }}",
                        type:'POST',
                        data:  {
                            "_token": "{{ csrf_token() }}",
                            "name": jQuery('#edit_name').val(),
                            "id" :jQuery('#edit_id').val(),
                        },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#edit_name').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#edit_id').val('');
                                jQuery('.edit-form-modal').modal('hide');
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
    @endsection
