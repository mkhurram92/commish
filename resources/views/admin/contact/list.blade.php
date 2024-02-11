@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Contacts
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Contact Search</h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex"><svg class="svg-icon"
                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">Contacts</li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card card mb-3">
                <div class="card-body">
                    <form method="get">
                        <div class="row mb-1">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Type</option>
                                        <option value="1">Client</option>
                                        <option value="2">Referror</option>
                                        <option value="3">Broker</option>
                                        <!--<option value="4">Broker Staff</option>-->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Business / Trading Name</label>
                                    <input name="trading" value="" id="trading" type="text"
                                        placeholder="Trading/business name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Surname</label>
                                    <input name="surname" value="" id="surname" placeholder="Surname" type="text"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Given Name</label>
                                    <input name="given_name" value="" id="given_name" placeholder="Given Name"
                                        type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-1 col-sm-12">
                                <div class="form-group" style="padding-top: 25px;">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" onclick="return refreshTable()" class="btn btn-primary mt-1"><i
                                            class="fa fa-search"></i></button>
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
            <div class="main-card card mb-3">
                <div class="card-body">
                    <h5 class="card-title float-right">
                        <a href="{{ route('admin.contact.add') }}" class="btn btn-success">Add Client</a>
                        <a href="{{ route('referrer_add') }}" class="btn btn-success">Add Referrer</a>
                        <a href="{{ route('admin.brokers.add') }}" class="btn btn-success">Add Broker</a>
                    </h5>

                    <div class="table-responsive">

                        <table style="width: 100%;max-width:none !important" id="TableData"
                            class="table-hover table-striped table-bordered display nowrap" data-toggle="table"
                            data-height="500" data-show-columns="true"
                            data-sAjaxSource="{{ route('admin.contact.getrecords') }}"
                            data-aoColumns='{"mData": "Index no"},{"mData": "Trading/Business"},{"mData": "Surname"},{"mData": "Given Name"},{"mData": "DOB"},{"mData": "Mobile Phone"},{"mData": "Action"}'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        <table style="width: 100%;max-width:none !important" id="referrer_table"
                            class="table-hover table-striped table-bordered display nowrap" data-toggle="table"
                            data-height="500" data-show-columns="true"
                            data-sAjaxSource="{{ route('admin.contact.getrecords') }}"
                            data-aoColumns='{"mData": "Index no"},{"mData": "Trading/Business"},{"mData": "Surname"},{"mData": "Given Name"},{"mData": "DOB"},{"mData": "Mobile Phone"},{"mData": "Action"}'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        <table style="width: 100%;max-width:none !important" id="broker_table"
                            class="table-hover table-striped table-bordered display nowrap" data-toggle="table"
                            data-height="500" data-show-columns="true"
                            data-sAjaxSource="{{ route('admin.contact.getrecords') }}"
                            data-aoColumns='{"mData": "Index no"},{"mData": "Trading/Business"},{"mData": "Surname"},{"mData": "Given Name"},{"mData": "Work Phone"},{"mData": "Address"},{"mData": "Postal Code"},{"mData": "Action"}'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>Work Phone</th>
                                    <th>Address</th>
                                    <th>Postal Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>Work Phone</th>
                                    <th>Address</th>
                                    <th>Postal Code</th>
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

    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            $("#TableData,#referrer_table,#broker_table").hide();
            // datatable of Broker and Referrer
            $(document).on("change", "#type", function(e) {
                const table_type = $(this).val();
                if (table_type == 2) {
                    // Referror
                    $("#TableData,#referrer_table,#broker_table").hide();
                    $("#referrer_table").show();
                } else if (table_type == 3) {
                    // Broker
                    $("#TableData,#referrer_table,#broker_table").hide();
                    $("#broker_table").show();
                } else {
                    // client
                    $("#TableData,#referrer_table,#broker_table").hide();
                    $("#TableData").show();
                }
            })
            $("#type").trigger("change");
        })

        function refreshTable() {
            if (jQuery('#type').val() == '') {
                errorMessage("Please Select Type !")
                return false;
            }
            var customArgs = {};
            customArgs['type'] = jQuery('#type').val();
            customArgs['surname'] = jQuery('#surname').val();
            //customArgs['entity_name'] = jQuery('#entity_name').val();
            customArgs['given_name'] = jQuery('#given_name').val();
            customArgs['trading'] = jQuery('#trading').val();
            //customArgs['state'] = jQuery('#state').val();
            //customArgs['city'] = jQuery('#city').val();
            //customArgs['postal_code'] = jQuery('#postal_code').val();
            customArgs['id'] = jQuery('#id').val();
            //customArgs['industry'] = jQuery('#industry').val();
            //customArgs['street_number'] = jQuery('#street_number').val();
            //customArgs['street_name'] = jQuery('#street_name').val();
            //customArgs['street_type'] = jQuery('#street_type').val();
            //customArgs['suburb'] = jQuery('#suburb').val();
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

                            var $slug_url = 'contact';

                            if (parseInt(jQuery('#type').val()) > 2) {
                                $slug_url = 'brokers';
                            }
                            var edit_row = '<li class="nav-item"><a href="{{ url('admin') }}/' +
                                $slug_url + '/edit/' + value.encrypt_id + '" ' +
                                'data-id="' + value
                                .id + '" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 " ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i> Edit</a></li>';

                            if (parseInt(jQuery('#type').val()) == 2) {
                                $slug_url = 'referrer';
                            }

                            var first_row = ' &nbsp;' +
                                '<div class="dropdown">\n' +
                                '          <button id="dLabel" type="button" data-toggle="dropdown" \n' +
                                '                  aria-haspopup="true" aria-expanded="false" class="btn btn-primary"><i ' +
                                'class="pe-7s-helm"></i> <span class="caret"></span>\n' +
                                '          </button>\n' +
                                '          <ul class="dropdown-menu broker_menu " role="menu" ' +
                                'aria-labelledby="dLabel">\n' +
                                edit_row +
                                '            <li class="nav-item"><a  href="{{ url('admin') }}/' +
                                $slug_url + '/view/' + value
                                .encrypt_id + '"><i class="fa fa-eye"></i> View</a></li>\n';

                            if ($slug_url == 'contact') {
                                /*first_row += '            <li class="nav-item"><a  href="{{ url('admin/contact-tasks/') }}/'+value.encrypt_id+'"><i class="fa fa-tasks"></i> Tasks</a></li>\n            ' +
                                    '<li class="nav-item"><a  href="javascript:void(0)" data-id="'+value.encrypt_id+'" onclick="return showCommissions(this)"><i class="fa fa-tasks"></i> Commissions</a></li>\n';*/
                            }

                            first_row += '          </ul>\n' +
                                '        </div>';

                            if (parseInt(jQuery('#type').val()) > 2) {
                                edit_row =
                                    '<li class="nav-item"><a href="{{ url('admin/brokers/edit/') }}/' +
                                    value.encrypt_id + '" ' +
                                    'data-id="' + value
                                    .id + '" ' +
                                    'onclick="return' +
                                    ' ' +
                                    'editRecord(this)" class="mb-2 mr-2 " ' +
                                    'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i> Edit' +
                                    '</a></li>';

                                first_row = ' &nbsp;' +
                                    '<div class="dropdown">\n' +
                                    '          <button id="dLabel" type="button" data-toggle="dropdown" \n' +
                                    '                  aria-haspopup="true" aria-expanded="false" class="btn btn-primary"><i ' +
                                    'class="pe-7s-helm"></i><span class="caret"></span>\n' +
                                    '          </button>\n' +
                                    '          <ul class="dropdown-menu broker_menu " role="menu" ' +
                                    'aria-labelledby="dLabel">\n' +
                                    edit_row +
                                    '            <li class="nav-item"><a  href="{{ url('admin/brokers/view/') }}/' +
                                    value
                                    .encrypt_id + '"><i class="fa fa-eye"></i> View</a></li>\n' +
                                    '          </ul>\n' +
                                    '        </div>';
                            }
                            if (customArgs['type'] == 1) {
                                var TempObj = {
                                    "Index no": value.id,
                                    "Surname": value.surname,
                                    "Given Name": value.preferred_name,
                                    "DOB": value.dob,
                                    "Mobile Phone": value.mobile_phone,
                                    //"Email": value.email,
                                    "Postal Code": value.postal_code,
                                    "Trading/Business": value.trading,
                                    "Action": first_row,
                                };
                            } else if (customArgs['type'] == 2) {
                                var TempObj = {
                                    "Index no": value.id,
                                    "Trading/Business": value.trading,
                                    "DOB": value.dob,
                                    "Mobile Phone": value.mobile_phone,
                                    "Surname": value.surname,
                                    "Given Name": value.preferred_name,
                                    "Action": first_row,
                                };
                            } else if (customArgs['type'] == 3) {
                                var TempObj = {
                                    "Index no": value.id,
                                    "Trading/Business": value.trading,
                                    "Surname": value.surname,
                                    "Given Name": value.preferred_name,
                                    "Work Phone": value.work_phone,
                                    "Address": value.address,
                                    "Postal Code": value.pincode,
                                    "Action": first_row,
                                };
                            }
                            aaDataArray.push(TempObj);
                        })
                    }
                    TempObj['aaData'] = aaDataArray;
                    fnCallback(TempObj);
                }
            }, function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {});
        }
    </script>
@endpush


@section('modal-section')
    <div class="modal fade commission-modal" id="commission-modal" tabindex="-1" role="dialog"
        aria-labelledby="Deal
    Commissions" aria-hidden="true">
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
                        <table class="table-bordered table" id="commission-modal-table">
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

        function showCommissions(current) {
            var encId = jQuery(current).attr('data-id');
            modalTable = $('#commission-modal-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.contact.getcomdata') }}",
                    data: function(d) {
                        d.deal_id = encId

                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'commission_type',
                        name: 'commission_type'
                    },
                    {
                        data: 'client',
                        name: 'client'
                    },
                    {
                        data: 'account_no',
                        name: 'account_no'
                    },
                    {
                        data: 'period',
                        name: 'period'
                    },
                    {
                        data: 'commission',
                        name: 'commission'
                    },
                    {
                        data: 'gst',
                        name: 'gst'
                    },
                    {
                        data: 'total_paid',
                        name: 'total_paid'
                    },
                    {
                        data: 'settlement_date',
                        name: 'settlement_date'
                    },
                    {
                        data: 'payment_no',
                        name: 'payment_no'
                    },
                ]
            });
            jQuery('#commission-modal').modal('show')
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
@endsection
