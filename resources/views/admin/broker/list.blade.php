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
                    Brokers
                </div>
            </div>
        </div>
    </div>
@endsection
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Broker Search <a class="float-right" href="javascript:void(0)"
                            onclick="return resetFilter(this)">clear
                            Filter</a></h5>
                    <form method="get">
                        <div class="row mb-1">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Type</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Surname</label>
                                    <input name="surname" value="" id="surname" placeholder="Surname" type="text"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Given name</label>
                                    <input name="given_name" value="" id="given_name" placeholder="Given Name"
                                        type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Tranding/Business</label>
                                    <input name="trading" value="" id="trading" type="text"
                                        placeholder="Trading/Business" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">State</label>
                                    <select name="state" id="state" class="multiselect-dropdown">
                                        <option value="">Select State</option>
                                        @if (count($states) > 0)
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">City</label>
                                    <select name="city" id="city" class="multiselect-dropdown">
                                        <option value="">Select City</option>
                                        @if (count($cities) > 0)
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" data-state_id="{{ $city->state_id }}">
                                                    {{ $city->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Postal Code</label>
                                    <input name="postal_code" value="" id="postal_code" placeholder="Postal Code"
                                        type="text" class="form-control alpha-num" maxlength="10">

                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">ID</label>
                                    <input name="id" value="" id="id" placeholder="ID" type="number"
                                        class="form-control input_int_number">
                                </div>

                            </div>

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
                        <a href="{{ route('admin.brokers.add') }}" class="btn btn-success">Add</a>
                    </h5>

                    <div class="table-responsive">

                        <table style="width: 100%;max-width:none !important" id="TableData"
                            class="table-hover table-striped table-bordered display nowrap" data-toggle="table"
                            data-height="500" data-show-columns="true"
                            data-sAjaxSource="{{ route('admin.brokers.getrecords') }}"
                            data-aoColumns='{"mData": "Index no"},{"mData": "Type"},{"mData": "Business"},{"mData": "Surname"},
                               {"mData": "Given Name"},{"mData": "DOB"},{"mData": "State"},{"mData": "City"},{"mData": "Mobile Phone"},{"mData": "Added On"},{"mData": "Modified On"},{"mData":
                               "Action"}'>
                            {{-- ,{"mData":
                            "Trust Name"},{"mData": "Entity Name"},{"mData": "Principle Contact"},{"mData": "ABP"},{"mData": "Postal Code"},{"mData": "Work Phone"},{"mData": "Home Phone"},{"mData": "Industry"} --}}
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Trading/Business</th>
                                    {{-- <th>Trust Name</th> --}}
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    {{-- <th>Entity Name</th>
                                    <th>Principle Contact</th>
                                    <th>ABP</th> --}}
                                    <th>DOB</th>
                                    <th>State</th>
                                    <th>City</th>
                                    {{-- <th>Postal Code</th>
                                    <th>Work Phone</th>
                                    <th>Home Phone</th> --}}
                                    <th>Mobile Phone</th>
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
                                    <th>Type</th>
                                    <th>Trading/Business</th>
                                    {{-- <th>Trust Name</th> --}}
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    {{-- <th>Entity Name</th>
                                    <th>Principle Contact</th>
                                    <th>ABP</th> --}}
                                    <th>DOB</th>
                                    <th>State</th>
                                    <th>City</th>
                                    {{-- <th>Postal Code</th>
                                     <th>Work Phone</th>
                                     <th>Home Phone</th> --}}
                                    <th>Mobile Phone</th>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            $("#state").select2({
                theme: "bootstrap4",
                placeholder: "Select State",
            });
            jQuery('#city option').attr('disabled', 'disabled');
            jQuery('#city option[value=""]').removeAttr('disabled')
            $("#city").select2({
                theme: "bootstrap4",
                placeholder: "Select City",
            });
            jQuery('body').on('change', '#state', function() {
                var currentState = jQuery(this).val();
                if (currentState != '') {
                    jQuery('#city option').attr('disabled', 'disabled');
                    jQuery('#city option[value=""]').removeAttr('disabled')
                    jQuery('#city option[data-state_id="' + currentState + '"]').removeAttr('disabled');
                    jQuery('#city').val('');
                    jQuery('#city').select2({
                        theme: "bootstrap4",
                        placeholder: "Select City",
                    })
                } else {
                    jQuery('#city option').attr('disabled', 'disabled');
                    jQuery('#city').select2({
                        theme: "bootstrap4",
                        placeholder: "Select City",
                    })
                }
            })
            refreshTable();
        })

        function refreshTable() {
            var customArgs = {};
            customArgs['type'] = jQuery('#type').val();
            customArgs['surname'] = jQuery('#surname').val();
            customArgs['given_name'] = jQuery('#given_name').val();
            customArgs['trading'] = jQuery('#trading').val();
            customArgs['state'] = jQuery('#state').val();
            customArgs['city'] = jQuery('#city').val();
            customArgs['postal_code'] = jQuery('#postal_code').val();
            customArgs['id'] = jQuery('#id').val();
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

                            var edit_row = '<a href="{{ url('admin/brokers/edit/') }}/' + value.encrypt_id +
                                '" ' +
                                'data-id="' + value
                                .id + '" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';

                            var first_row = ' &nbsp;' +
                                '<div class="dropdown">\n' +
                                '          <button id="dLabel" type="button" data-toggle="dropdown" \n' +
                                '                  aria-haspopup="true" aria-expanded="false">\n' +
                                +value.id +
                                '            <span class="caret"></span>\n' +
                                '          </button>\n' +
                                '          <ul class="dropdown-menu broker_menu " role="menu" ' +
                                'aria-labelledby="dLabel">\n' +
                                '            <li class="nav-item"><a  href="{{ url('admin/brokers/view/') }}/' +
                                value
                                .encrypt_id + '"><i class="fa fa-eye"></i> View</a></li>\n' +
                                /*  '            <li class="nav-item"><a  href="{{ url('admin/broker-expenses/') }}/'+value.encrypt_id+'"><i class="pe-7s-cash"></i> Expenses</a></li>\n' +
                                 '            <li class="nav-item"><a  href="{{ url('admin/broker-tasks/') }}/'+value.encrypt_id+'"><i class="fa fa-tasks"></i> Tasks</a></li>\n' +'        ' +
                                 '    <li class="nav-item"><a  href="{{ url('admin/broker-fees/') }}/'+value.encrypt_id+'"><i class="pe-7s-safe"></i> Fees</a></li>\n' +
                                 '    <li ' +
                                 'class="nav-item"><a  href="{{ url('admin/broker-certifications/') }}/'+value.encrypt_id+'"><i class="pe-7s-id"></i> Certifications</a></li>\n' +
                                 '    <li ' +
                                 'class="nav-item"><a  href="{{ url('admin/broker-commissions/add/') }}/'+value.encrypt_id+'"><i class="pe-7s-network"></i> Commission Model</a></li>\n' + */
                                '          </ul>\n' +
                                '        </div>';

                            var TempObj = {
                                // "Index no" : '<a target="_blank" href="{{ url('admin/brokers/view/') }}/'+value.encrypt_id+'">'+value.id+'</a>',
                                "Index no": first_row,
                                "Type": value.type,
                                "Business": value.trading,

                                "Surname": value.surname,
                                "Given Name": value.given_name,

                                "DOB": value.dob,
                                "State": value.state_name,
                                "City": value.city_name,

                                "Mobile Phone": value.mobile_phone,

                                "Added On": value.formated_created_at,
                                "Modified On": value.formated_updated_at,
                                "Action": edit_row,
                            };
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
