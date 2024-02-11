@extends('layout.main')
@push('style-section')
@endpush
@section('title')
Users
@endsection

@section('page_title_con')

    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Users
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">                             Users
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('body')
<div class="row">
    <div class="col-lg-12 col-md-12">

        <div class="main-card mb-3 card add_form_card">
            <div class="card-body">
                <h5 class="card-title">Add New</h5>
                <div>
                    <form class="" id="" method="post" action="{{ route('admin.user.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="col-sm-12">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="fname" class="">First Name</label>
                                            <input name="fname" id="fname" placeholder="First Name" type="text"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lname" class="form-label">Last Name</label>
                                            <input name="lname" id="lname" placeholder="Last Name" type="text"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="phone" class="">Phone</label>
                                            <input name="phone" id="phone" placeholder="Phone" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="email" class="">Email</label>
                                            <input name="email" id="email" placeholder="Email" type="email"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="image" class="">Image</label><br>
                                            <input name="image" id="image" placeholder="Upload Profile Image" type="file">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="username" class="">User Name</label>
                                            <input name="username" id="username" placeholder="User Name" type="text"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="password" class="">Password</label>
                                            <input name="password" id="password" placeholder="Password"
                                                   type="password" autocomplete="off"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="status" class="">Active</label>
                                            <!--<input name="status" id="status" type="checkbox">-->
                                            <select name="status" id="status" class="form-control status">
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group"><br>
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="divider"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="col-sm-12">
            <div class="">
                <table style="width: 100%" id="TableData"
                    class="table table-hover table-striped table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th class="noVis">Name</th>
                            <th class="noVis">Username</th>
                            <th class="noVis">Email</th>
                            <th class="noVis">Role</th>
                            <th class="noVis">Status</th>
                            <!-- <th class="">Added On</th> -->
                            <th class="">Modified On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="TableDataTbody"></tbody>
                    <tfoot>
                        <tr>
                            <th class="noVis">Name</th>
                            <th class="noVis">Username</th>
                            <th class="noVis">Email</th>
                            <th class="noVis">Role</th>
                            <th class="noVis">Status</th>
                            <!-- <th class="">Added On</th> -->
                            <th class="">Modified On</th>
                            <th>Action</th>
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
                    fname: {
                        required: true,
                        minlength: 2,
                    },
                    lname: {
                        required: true,
                        minlength: 2,
                    },
                    email: {
                        required: true,
                        minlength: 2,
                    },
                    username: {
                        required: true,
                        minlength: 2,
                    },
                    password: {
                        required: true,
                        minlength: 2,
                    }
                },
                messages: {
                    fname: {
                        required: "Please enter a first name",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    lname: {
                        required: "Please enter a last name",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    email: {
                        required: "Please enter a email",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    username: {
                        required: "Please enter a username",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    password: {
                        required: "Please enter a password",
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
                        url: "{{ route('admin.user.post') }}",
                        type:'POST',
                        data: {
                        "_token": "{{ csrf_token() }}",
                            "fname": jQuery('#fname').val(),
                            "lname": jQuery('#lname').val(),
                            "username": jQuery('#username').val(),
                            "email": jQuery('#email').val(),
                            "phone": jQuery('#phone').val(),
                            "password": jQuery('#password').val(),
                            "status" :(jQuery('#status').prop('checked'))?1:0,
                        },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#fname').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#lname').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#username').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#password').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#email').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#phone').val('').removeClass('is-invalid').removeClass('is-valid');
                                hideLoader();
                                refreshTable();
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
            refreshTable();
        });

        function refreshTable()
        {
            if ( $.fn.dataTable.isDataTable( '.yajra-datatable' ) ) {
               var table1 = $('.yajra-datatable').DataTable();
               table1.destroy();
            }

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.user.getrecords') }}",
                columns: [
                    {data: 'name', name: 'fname'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name: 'role'},
                    {data: 'status', name: 'status'},
                    // {data: 'formated_created_at', name: 'formated_created_at'},
                    {data: 'formated_updated_at', name: 'formated_updated_at'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
        }

        function editRecord(current)
        {
            var id = jQuery(current).attr('data-id');
            if(typeof id != "undefined" && parseInt(id) > 0)
            {
                showLoader();
                $.ajax({
                    url: "{{ route('admin.user.getrecord') }}",
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
                                jQuery('#efname').val(payloadData.fname);
                                jQuery('#elname').val(payloadData.lname);
                                jQuery('#eusername').val(payloadData.username);
                                jQuery('#eemail').val(payloadData.email);
                                jQuery('#epassword').val(payloadData.password);
                                jQuery('#ephone').val(payloadData.phone);
                               // jQuery('#estatus').find(":selected").text(payloadData.status);
                               jQuery('#estatus').select2('val', payloadData.status.toString());
                                //alert(payloadData.status);
                                if(payloadData.status == 1)
                                    //jQuery('#estatus').attr('checked','checked');
                                    //jQuery('#estatus').find(":selected").val(payloadData.status);
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
                swal({
                    title: "Are you sure?",
                    text: "You are going to delete record!",
                    type: "warning",
                    showCancelButton: true,
                    allowOutsideClick: false,
                    confirmButtonColor: "#d33"
                }).then(function (result)  {

                    if (result == true) {
                        $.ajax({
                            url: "{{ route('admin.user.delete') }}",
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
                    } else if (result == false) {
                        hideLoader();
                    }
                });
                /*swal.fire({
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
                            url: "{{ route('admin.user.delete') }}",
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
                });*/

            }

        }

        function setPermission(current) {

            var id = jQuery(current).attr('data-id');
            jQuery('#per_user_id').val(id);
            if(typeof id != "undefined" && parseInt(id) > 0)
            {
                showLoader();
                $.ajax({
                    url: "{{ route('admin.user.getmodules') }}",
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
                                // jQuery('#edit_id').val(payloadData.id);
                                hideLoader();

                                var str = '';

                                $.each(payloadData.modules, function(k, v){
                                    let check = '';
                                    if(payloadData.selrec.includes(v.id))
                                        check = 'checked';
                                    //<input type="checkbox" checked data-toggle="toggle" data-on="Yes"
                                    //data-off="No" data-onstyle="success" data-offstyle="danger">
                                    str_sg =`<label class="custom-switch" >
                                                               <input id="perm_`+v.id+`"
                                                                      value="`+v.id+`"
                                                                      name="module[]"
                                                                       type="checkbox" class="custom-switch-input mod permiss_mod">
                                                               <span class="custom-switch-indicator"></span>
                                                               <span class="custom-switch-description"> `+v.module_name+`</span>
                                                           </label>`;
                                    str += '<div class="col-sm-4"><div class="form-group">'+str_sg+'</div></div>';

                                });
                                $("#mods").html('<div class="col-sm-12"><div class="row">'+str+'</div></div><div ' +
                                    'class="col-sm-12"><a href="javascript:void(0)" onclick="return selectAllMod(1)' +
                                    '" style="margin-right:10px">Select All</a>' +
                                    '<a href="javascript:void(0)" onclick="return selectAllMod(0)">Unselect ' +
                                    'All</a>');
                                jQuery('.user-permission-modal').modal('show');
                                $(function(){
                                    $('input[data-toggle="toggle"]').bootstrapToggle();
                                });
                            }
                            else {
                                printErrorMsg('No records found!');
                                hideLoader();
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

        function selectAllMod(stat)
        {
            if(stat == 1)
            {
                jQuery('.permiss_mod').attr('checked','checked').prop('checked',true);
                jQuery('.permiss_mod').trigger('change');
                //$('input[data-toggle="toggle"]').bootstrapToggle();
            }else{
                jQuery('.permiss_mod').removeAttr('checked').prop('checked',false);
                jQuery('.permiss_mod').trigger('change');
                //$('input[data-toggle="toggle"]').bootstrapToggle();
            }
        }

</script>
@endpush

@section('modal-section')


<div class="modal fade user-permission-modal" tabindex="-1" role="dialog" aria-labelledby="User Permission"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">User :: Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" id="usrperForm">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="per_user_id" id="per_user_id" value="" />
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="Module" class="font-weight-bold">Select Modules</label>
                            <span id="mods"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="javascript:savePermission();" class="btn btn-primary">Save
                        changes</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="modal fade edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="Edit Form" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">User :: Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('admin.user.update') }}" id="" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id" value="" />
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="edit_name" maxlength="255" class="font-weight-bold">First Name</label>
                            <input name="fname" id="efname" placeholder="First Name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="lname" class="font-weight-bold">Last Name</label>
                            <input name="lname" id="elname" placeholder="Last Name" type="text" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="phone" class="font-weight-bold">Phone</label>
                            <input name="phone" id="ephone" placeholder="Phone" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input name="email" id="eemail" placeholder="Email" type="email" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">Image</label><br>
                            <input name="image" id="eimage" placeholder="profile Image" type="file">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="username" class="font-weight-bold">User Name</label>
                            <input name="username" id="eusername" placeholder="User Name" type="text"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Password</label>
                            <input name="epassword"  autocomplete="off" id="epassword" placeholder="Password"
                                   type="password"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <!--<input name="estatus" id="estatus" type="checkbox">-->
                            <select name="estatus" id="estatus" class="form-control status">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
     $(".status").select2({
        placeholder: "Select Status",
    });
    function savePermission() {
            var userid = jQuery('#per_user_id').val();
            if(typeof userid != "undefined" && parseInt(userid) > 0)
            {
                showLoader();
                $.ajax({
                    url: "{{ route('admin.user.savemodules') }}",
                    type:'POST',
                    data:$('#usrperForm').serialize(),
                    success: function(data) {
                        if($.isEmptyObject(data.error)){
                            successMessage(data.success);
                            hideLoader();
                            jQuery('.user-permission-modal').modal('hide');
                            refreshTable();
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

        jQuery(document).ready(function(){
            $("#editForm").validate({
                rules: {
                    fname: {
                        required: true,
                        minlength: 2,
                    },
                    lname: {
                        required: true,
                        minlength: 2,
                    },
                    email: {
                        required: true,
                        minlength: 2,
                    },
                    username: {
                        required: true,
                        minlength: 2,
                    }
                },
                messages: {
                    fname: {
                        required: "Please enter a first name",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    lname: {
                        required: "Please enter a last name",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    email: {
                        required: "Please enter a email",
                        minlength: "Your name must consist of at least 2 characters",
                    },
                    username: {
                        required: "Please enter a username",
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
                        url: "{{ route('admin.user.update') }}",
                        type:'POST',
                        data:  {
                            "_token": "{{ csrf_token() }}",
                            "fname": jQuery('#efname').val(),
                            "lname": jQuery('#elname').val(),
                            "username": jQuery('#eusername').val(),
                            "email": jQuery('#eemail').val(),
                            "phone": jQuery('#ephone').val(),
                            "password": jQuery('#epassword').val(),
                            "id" :jQuery('#edit_id').val(),
                            "status" :jQuery('#estatus').val(), //(jQuery('#estatus').prop('checked'))?1:0,
                        },
                        success: function(data) {
                            //console.log(data);
                           //alert('ghgh');
                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                                jQuery('#efname').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#elname').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#eusername').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#eemail').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#epassword').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#ephone').val('').removeClass('is-invalid').removeClass('is-valid');
                                jQuery('#edit_id').val('');
                                jQuery('.edit-form-modal').modal('hide');
                                hideLoader();
                                refreshTable();
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
        });

</script>
@endsection
