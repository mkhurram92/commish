@extends('layout.main')
@push('style-section')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        html,
        body {
            overflow-x: hidden !important;
        }
    </style>
@endpush
@section('title')
    @if ($contact->individual == 1)
        Contact :: {{ $contact->surname }} {{ $contact->preferred_name }}
    @else
        Company :: {{ $contact->trading }}
    @endif
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                @if ($contact->individual == 1)
                    Contact :: {{ $contact->surname . ' ' . $contact->preferred_name }}
                @else
                    Company :: {{ $contact->trading }}
                @endif
                <a class="btn btn-primary text-white" href="{{ route('admin.contact.edit', encrypt($contact->id)) }}">
                    <i class="pe-7s-pen btn-icon-wrapper"></i>
                </a>
            </h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex"><svg class="svg-icon"
                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('admin.contact.list') }}">Clients</a>
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('body')
    <div id="" class="panel panel-primary">
        <div class="tab-menu-heading">
            <div class="tabs-menu">
                <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav panel-tabs">
                    <li class="nav-item">
                        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                            <span>Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                            <span>Referrals</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                            <span>Relations</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-5">

                            <span>Source Of Client</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                            <span>Tasks</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                                    <span>Commissions</span>
                                                </a>
                                            </li>-->
                </ul>
            </div>
        </div>

        <div class="panel-body tabs-menu-body">
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="main-card mb-3">
                                <div class="rows">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="mb-2"><b>Client Type :
                                                            </b>{{ $contact->individual == 1 ? 'Individual' : 'Company' }}
                                                        </p>
                                                        <p class="mb-2"><b>Trading/Business : </b>{{ $contact->trading }}
                                                        </p>
                                                        <p class="mb-2"><b>Principle Contact :
                                                            </b>{{ $contact->principle_contact }}
                                                        </p>
                                                        <p class="mb-2"><b>Trust Name : </b>
                                                            {{ $contact->trust_name }}
                                                        </p>
                                                        <p class="mb-2"><b>Status :
                                                            </b>{{ $contact->status == 1 ? 'Active' : 'Inactive' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="mb-2"><b>Salutation :
                                                            </b>{{ ucfirst($contact->role_title . '.') }}</p>
                                                        <p class="mb-2"><b>Surname : </b>{{ $contact->surname }}</p>
                                                        <p class="mb-2"><b>Given Name :
                                                            </b>{{ $contact->preferred_name }}</p>
                                                        <p class="mb-2"><b>DOB : </b>{{ $contact->dob }}</p>
                                                        <p class="mb-2"><b>ABN : </b>{{ $contact->abn }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="mb-2"><b>Work Phone : </b>{{ $contact->work_phone }}
                                                        </p>
                                                        <p class="mb-2"><b>Home Phone : </b>{{ $contact->home_phone }}
                                                        </p>
                                                        <p class="mb-2"><b>Mobile Phone :
                                                            </b>{{ $contact->mobile_phone }}</p>
                                                        <!--<p class="mb-2"><b>Fax : </b>{{ $contact->fax }}</p>-->
                                                        <p class="mb-2"><b>Email : </b><a
                                                                href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                        </p>
                                                        <p class="mb-2"><b>Web : </b>{{ $contact->web }}</p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="mb-2"><b>Broker : </b><a class="active_a"
                                                                href="{{ route('admin.brokers.view', encrypt($contact->contact_abp_id)) }}"
                                                                target="_blank">{{ $contact->abp_name }}</a></p>
                                                        <?php //print_R($contact);
                                                        ?>
                                                        <p class="mb-2"><b>Industry : </b>{{ $contact->industry_name }}
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="card-title">
                                        Addresses
                                    </div>
                                    {{-- <div class="add-btn">
                                        <button class="btn btn-primary edit-address"
                                            data-href="{{ route('admin.contact.viewEditAddress', ['contact_id' => encrypt($contact->id)]) }}">Edit
                                            Address</button>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Residential </h5>
                                            <p class="mb-2"><b>Address : </b>
                                                {{ $address?->unit != '' ? $address?->unit . ' ' : '' }}
                                                {{ $address?->street_name != '' ? $address->street_name . ' ' : '' }}

                                            </p>
                                            <p class="mb-2"><b>City : </b>{{ $address?->city }}</p>
                                            <p class="mb-2"><b>State : </b>{{ $address?->withState?->full_name }}</p>
                                            <p class="mb-2"><b>Postal Code : </b>{{ $address?->postal_code }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Postal </h5>
                                            <p class="mb-2"><b>Address : </b>
                                                {{ $postalAddress?->unit != '' ? $postalAddress?->unit . ' ' : '' }}
                                                {{ $postalAddress?->street_name != '' ? $postalAddress->street_name . ' ' : '' }}
                                            </p>
                                            <p class="mb-2"><b>City : </b>{{ $postalAddress?->city }}</p>
                                            <p class="mb-2"><b>State : </b>{{ $postalAddress?->withState?->full_name }}
                                            </p>
                                            <p class="mb-2"><b>Postal Code : </b>{{ $postalAddress?->postal_code }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary float-right"
                                        onclick="return showAddReferredTo()">Add Referred To</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-bordered table" id="referral_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Service</th>
                                                    <th>Date</th>
                                                    <th>Notes</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Service</th>
                                                    <th>Date</th>
                                                    <th>Notes</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary float-right"
                                        onclick="return showAddRelationship()">Add Relationship</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-bordered table" id="relationship_table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>Relation</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @if (count($contact->relationDetails) > 0)
                                                                    @foreach ($contact->relationDetails as $ke => $relationDetail)
                                                                    <tr>
                                                                        <td>{{($ke+1)}}</td>
                                                <td><a href="{{route('admin.contact.view',encrypt($relationDetail->client_relation_id))}}" target="_blank" class="active_a">
                                                        {{$relationDetail->surname.' '.$relationDetail->first_name}}</a></td>
                                                <td>{{$relationDetail->client_relation_label}}</td>
                                                <!-- <td>{{ ($relationDetail->mailout_display) }}</td> -->
                                                </tr>
                                                @endforeach
                                                @endif --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table-bordered table" id="commission-modal-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>
                                                                Commission Type
                                                            </th>
                                                            <!--      <th>
                                                                                                          Client
                                                                                                      </th>
                                                                                                      <th>
                                                                                                          Account No
                                                                                                      </th> -->
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <button type="button" class="btn btn-primary float-right"
                                            onclick="return showAddTasks()">Add Task</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table style="width: 100%;max-width:none !important" id="TableData"
                                                    class="table-hover table-striped table-bordered display nowrap"
                                                    data-toggle="table" data-height="500" data-show-columns="true"
                                                    data-sAjaxSource="{{ route('admin.contacttsk.getrecords', encrypt($contact->id)) }}"
                                                    data-aoColumns='{"mData": "Index no"},{"mData": "Start Date"},{"mData": "End Date"},{"mData": "User"},
                                                    {"mData": "Detail"},{"mData": "Added On"},{"mData":
                                                    "Action"}'>
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <!--<th>Processor</th>-->
                                                            <th>User</th>
                                                            <th>Detail</th>
                                                            <th>Added On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <!--<th>Processor</th>-->
                                                            <th>User</th>
                                                            <th>Detail</th>
                                                            <th>Added On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-5" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary float-right"
                                        onclick="return showEditSourceOfClient()">Edit Source of Client</button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if ($contact->referrer_type == 1)
                                            <div class="col-md-4">
                                                <h4>Referred By</h4>

                                                Referrer

                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Referrer
                                                </h4>

                                                {{ $contact?->withOtherReferrer?->surname ?? 'N/A' }}

                                            </div>
                                        @elseif($contact->referrer_type == 2)
                                            <div class="col-md-4">
                                                <h4>Referred By</h4>

                                                Mutual Rewards Client

                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Existing Clients
                                                </h4>

                                                <a href="{{ route('admin.contact.view', encrypt($contact->referrer_id)) }}"
                                                    target="_blank">
                                                    {{ $contact?->withReferrerToExistingClient?->surname ?? 'N/A' }}
                                                </a>

                                            </div>
                                        @elseif($contact->referrer_type == 3)
                                            <div class="col-md-4">
                                                <h4>Referred By</h4>

                                                Staff Referral

                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Staff Referral
                                                </h4>

                                                {{ $contact?->withStaffReferral?->name ?? 'N/A' }}

                                            </div>
                                        @elseif($contact->referrer_type == 4)
                                            <div class="col-md-4">
                                                <h4>Referred By</h4>

                                                Social Media

                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Social Media
                                                </h4>

                                                {{ isset($contact) && $contact->social_media_link == 1 ? 'Facebook' : '' }}
                                                {{ isset($contact) && $contact->social_media_link == 2 ? 'Twitter' : '' }}
                                                {{ isset($contact) && $contact->social_media_link == 3 ? 'Instagram' : '' }}
                                                {{ isset($contact) && $contact->social_media_link == 4 ? 'Youtube' : '' }}

                                            </div>
                                        @else
                                            <div class="col-md-8">
                                                <h4>Referred By</h4>
                                                Unknown
                                            </div>
                                        @endif
                                        <div class="col-md-4">
                                            <h4>Referrer Relationship</h4>

                                            {{ $contact?->withReferrerRelationship?->name ?? 'N/A' }}

                                        </div>
                                        <br><br><br><br><br>
                                        <div class="col-12">
                                            <h4>Note</h4>
                                            {{ $contact?->refferor_note ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-section')
    @include('layout.datatable')
    <script type="text/javascript"
        src="{{ asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front-assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front-assets/js/form-components/datepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $("#task_user").select2()
        var encId = '{{ encrypt($contact->id) }}'
        var modalTable = $('#commission-modal-table').DataTable({
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
                // {data: 'client', name: 'client'},
                // {data: 'account_no', name: 'account_no'},
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
        $("#relationship_table").DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: "{{ route('admin.contact.addrelationship', ['contact_id' => encrypt($contact?->id)]) }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'relation_with',
                    name: 'Name'
                },
                {
                    data: 'relation',
                    name: 'Relation'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $("#referral_table").DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: "{{ route('admin.contact.addreferredto', ['contact_id' => encrypt($contact?->id)]) }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'Name'
                },
                {
                    data: 'service_id',
                    name: 'Service'
                },
                {
                    data: 'date',
                    name: 'Date'
                },
                {
                    data: 'notes',
                    name: 'Note'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on("click", ".view-referral-to,.edit-referral-to,.edit-address", function(e) {
            $.ajax($(e.target).attr("data-href"), {
                    type: "GET",
                    data: {
                        type: $(e.target).attr("data-type"),
                    }
                })
                .done(res => {
                    $(document).find("#crudModal").html(res?.html)
                    $(document).find("#crudModal .modal").modal("show")
                    $('[data-toggle="datepicker"]').datepicker({
                        format: 'dd/mm/yyyy',
                        autoHide: true
                    });
                })
        })

        function showAddTasks() {
            jQuery('#add_task_form').trigger("reset");
            jQuery('#detail').text("");
            jQuery('#add_task_form').attr('action', '{{ route('admin.contacttsk.post', encrypt($contact->id)) }}')
            jQuery('#tasks-modal').modal('show')
            $("#task_user").select2()
        }

        function showAddReferredTo() {
            jQuery('#add_referred_to_form').trigger("reset");
            jQuery('#referred_to_note').text("");
            jQuery('#add_referred_to_form').attr('action',
                '{{ route('admin.contact.addreferredto', ['contact_id' => encrypt($contact->id)]) }}')
            jQuery('#referred_to-modal').modal('show')
        }

        function showEditSourceOfClient() {
            jQuery('#edit_source_of_client_form').trigger("reset");
            jQuery('#edit_source_of_client_form').attr('action',
                '{{ route('admin.contact.editSourceOfClient', ['contact_id' => encrypt($contact->id)]) }}')
            jQuery('#edit_source_of_client-modal').modal('show')
            jQuery("#edit_source_of_client_form").find("#relation_with").select2();
            jQuery("#edit_source_of_client_form").find("#relation").select2();
        }

        function showAddRelationship() {
            jQuery('#add_relationship_form').trigger("reset");
            jQuery('#add_relationship_form').attr('action',
                '{{ route('admin.contact.addrelationship', ['contact_id' => encrypt($contact->id)]) }}')
            jQuery('#add-relationship-modal').modal('show')
            jQuery("#add_relationship_form").find("#relation_with").select2();
            jQuery("#add_relationship_form").find("#relation").select2();
        }

        function editRecord(current) {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
            showLoader();
            $.ajax({
                url: url,
                type: 'POST',
                data: {},
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();

                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        if (typeof data.task != "undefined" && data.task != '') {
                            var deal = data.task;
                            var dealtaskdata = deal.taskdata
                            console.log(dealtaskdata);
                            jQuery('#edit_id').val(deal.id);
                            jQuery('#followup_date').val(dealtaskdata.followup_date);
                            jQuery('#end_date').val(dealtaskdata.end_date);
                            jQuery('#processor').val(dealtaskdata.processor);
                            jQuery('#user').val(dealtaskdata.user);
                            jQuery('#detail').html(dealtaskdata.details);
                            jQuery('#add_task_form').attr('action',
                                '{{ url('admin/contact-tasks/update') }}/' + dealtaskdata.enc_id +
                                '/{{ encrypt($contact->id) }}')
                            jQuery('#tasks-modal').modal('show')
                            hideLoader();
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function refreshTable() {
            var customArgs = {};
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
                            const dataUrl = "{{ url('admin/contact-tasks/get-record/') }}/'" + value
                                .encrypt_id + "'/{{ encrypt($contact->id) }}";
                            var edit_row = '<a data-url="' + dataUrl + '" ' +
                                'data-id="' + value.id + '" ' +
                                'onclick="return' + ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit"  class="pe-7s-pen btn-icon-wrapper" ' +
                                'style="color:#fff"></i></a>';
                            var TempObj = {
                                "Index no": value.id,
                                "User": value.processor,
                                "Processor": value.processor,
                                "Start Date": value.followup_date,
                                "End Date": value.end_date,
                                "Detail": value.details,
                                "Added On": value.formated_created_at,
                                "Action": edit_row,
                            };
                            aaDataArray.push(TempObj);
                        })
                    }
                    TempObj['aaData'] = aaDataArray;
                    fnCallback(TempObj);
                }
            }, function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {});
        }

        jQuery(document).ready(function() {
            refreshTable();
        })
    </script>
@endpush

@section('modal-section')
    <div id="crudModal"></div>
    <div class="modal fade edit_source_of_client-modal" id="edit_source_of_client-modal" tabindex="-1" role="dialog"
        aria-labelledby="Deal Tasks" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Source of Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" onsubmit="return saveSourceOfClient(this)"
                        id="edit_source_of_client_form">
                        @csrf
                        <div class="form-row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Referred By</label>
                                            <select name="referrer_type" id="referrer_type"
                                                class="form-control linked_to_selector">
                                                <option value="">Select Client</option>
                                                <option
                                                    {{ isset($contact) && $contact->referrer_type == 1 ? 'selected' : '' }}
                                                    value="1">Referrer</option>
                                                <option
                                                    {{ isset($contact) && $contact->referrer_type == 2 ? 'selected' : '' }}
                                                    value="2">Mutual Rewards Client</option>
                                                <option
                                                    {{ isset($contact) && $contact->referrer_type == 3 ? 'selected' : '' }}
                                                    value="3">Staff Referral </option>
                                                <option
                                                    {{ isset($contact) && $contact->referrer_type == 4 ? 'selected' : '' }}
                                                    value="4">Social Media</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type == 1 ? '' : 'hidden' }}"
                                        id="other_referrer_div">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Referrer</label>
                                            <!--<input name="other_referrer" id="other_referrer" class="form-control" value="{{ isset($contact) ? $contact->other_referrer : '' }}">-->
                                            <select name="other_referrer" id="other_referrer"
                                                class="form-control linked_to_selector">
                                                <option value="">Select Referrer</option>

                                                @foreach ($referrers as $referrer)
                                                    <option value="{{ $referrer->id }}"
                                                        @if (isset($contact) && $contact->other_referrer != '' && $contact->other_referrer == $referrer->id) selected="selected" @endif>
                                                        @if ($contact->individual == 1 && $contact->status == 1)
                                                            {{ $referrer->id . ' ' . $referrer->surname . ' ' . $referrer->preferred_name }}
                                                        @elseif ($contact->individual == 2 && $contact->status == 1)
                                                            {{ $referrer->id . ' ' . $referrer->trading }}
                                                        @endif
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type == 2 ? '' : 'hidden' }}"
                                        id="from_clients">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Existing Clients</label>

                                            <select name="client_id" id="client_id"
                                                class="form-control linked_to_selector">

                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                        {{ isset($contact) && $contact->referrer_id != '' && $contact->referrer_id == $client->id
                                                            ? 'selected="selected"'
                                                            : '' }}>
                                                        {{ $client->surname . ' ' . $client->first_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type == 3 ? '' : 'hidden' }}"
                                        id="from_referrer">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Staff Referral</label>

                                            <select name="referrer_id" id="referrer_id"
                                                class="form-control linked_to_selector">
                                                <option value="">Select Staff Referral</option>
                                                @foreach ($processors as $processor)
                                                    <option value="{{ $processor->id }}"
                                                        {{ isset($contact) && $contact->referrer_id != '' && $contact->referrer_id == $processor->id
                                                            ? 'selected="selected"'
                                                            : '' }}>
                                                        {{ $processor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type == 4 ? '' : 'hidden' }}"
                                        id="from_social_media">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Social Media</label>
                                            <!--<input name="social_media_link" id="social_media_link" class="form-control" value="{{ isset($contact) ? $contact->social_media_link : '' }}">-->
                                            <select name="social_media_link" id="social_media_link"
                                                class="form-control linked_to_selector">
                                                <option value="">Select Social Media</option>
                                                <option
                                                    {{ isset($contact) && $contact->social_media_link == 1 ? 'selected' : '' }}
                                                    value="1">Facebook</option>
                                                <option
                                                    {{ isset($contact) && $contact->social_media_link == 2 ? 'selected' : '' }}
                                                    value="2">Twitter</option>
                                                <option
                                                    {{ isset($contact) && $contact->social_media_link == 3 ? 'selected' : '' }}
                                                    value="3">Instagram </option>
                                                <option
                                                    {{ isset($contact) && $contact->social_media_link == 4 ? 'selected' : '' }}
                                                    value="4">Youtube</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Referrer Relationship</label>
                                            <select name="refferor_relation_to_client" id="refferor_relation_to_client"
                                                class="form-control">
                                                <option value="">Select Relation</option>
                                                @foreach ($relations as $relation)
                                                    <option
                                                        {{ isset($contact) && $contact->refferor_relation_to_client == $relation->id ? 'selected' : '' }}
                                                        value="{{ $relation->id }}">{{ $relation->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Notes:</label>
                                    <textarea class="form-control" id="refferor_note" name="refferor_note" rows="4"> {{ isset($contact) && $contact->refferor_note ? $contact->refferor_note : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary mt-1">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade add-relationship-modal" id="add-relationship-modal" tabindex="-1" role="dialog"
        aria-labelledby="Deal Tasks" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Relationship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" onsubmit="return saveRelationshipForm(this)"
                        id="add_relationship_form">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Relationship with</label>
                                    <select name="relation_with" id="relation_with"
                                        class="multiselect-dropdown form-control">
                                        <option value="">Select Client</option>
                                        @if (count($clients) > 0)
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">
                                                    {{ $client->surname }} {{ $client->preferred_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="relation" class="form-label font-weight-bold">Relation</label>
                                    <select name="relation" id="relation" class="multiselect-dropdown form-control">
                                        <option value="">Select Relation</option>
                                        @if (count($relations) > 0)
                                            @foreach ($relations as $relation)
                                                <option value="{{ $relation->id }}">
                                                    {{ $relation->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary mt-1">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade referred_to-modal" id="referred_to-modal" tabindex="-1" role="dialog"
        aria-labelledby="Deal Tasks" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Referred To</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" onsubmit="return saveReferredToForm(this)"
                        id="add_referred_to_form">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Referred To</label>
                                    <input type="text" name="client_referral" id="client_referrals"
                                        class="form-control" maxlength="255" value="" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="service_id" class="form-label font-weight-bold">Service</label>
                                    <select name="service_id" id="service_id" class="multiselect-dropdown form-control">
                                        <option value="">Select Service</option>
                                        @if (count($services) > 0)
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}">
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Date</label>
                                    <input type="text" data-toggle="datepicker" placeholder="dd/mm/yyyy"
                                        name="date" autocomplete="off" id="date" class="form-control"
                                        maxlength="255" value="" />
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleText" class="form-label font-weight-bold">Note</label>
                                    <textarea name="referred_to_note" id="referred_to_note" class="form-control"></textarea>
                                </div>
                            </div>

                            <button class="btn btn-primary mt-1">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade tasks-modal" id="tasks-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Tasks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" onsubmit="return saveForm(this)" id="add_task_form">
                        @csrf
                        <div class="form-row">
                            <input type="hidden" name="edit_id" id="edit_id" />

                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Start Date</label>
                                    <input type="text" data-toggle="datepicker" placeholder="dd/mm/yyyy"
                                        name="followup_date" autocomplete="off" id="followup_date" class="form-control"
                                        maxlength="255" value="" />

                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">End Date</label>
                                    <input type="text" data-toggle="datepicker" placeholder="dd/mm/yyyy"
                                        name="end_date" autocomplete="off" id="end_date" class="form-control"
                                        maxlength="255" value="" />

                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="position-relative form-group">
                                    <label for="user" class="form-label font-weight-bold">User</label>
                                    <select name="user" id="task_user" class="multiselect-dropdown form-control">
                                        <option value="">Select User</option>
                                        @if (count($task_users) > 0)
                                            @foreach ($task_users as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($user->id == $user->id) selected @endif>
                                                    {{ $user->surname . ' ' . $user->given_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                                    <textarea name="detail" id="detail" class="form-control"></textarea>
                                </div>
                            </div>

                            <button class="btn btn-primary mt-1">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <script>
        var deal_enc = "{{ encrypt($contact->id) }}"

        function saveForm(current) {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $("form").serialize(),
                success: function(data) {

                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();

                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        jQuery('#add_task_form').trigger("reset");
                        jQuery('#tasks-modal').modal('hide');
                        refreshTable();
                        hideLoader();

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function saveAddressForm(current) {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $(current).serialize(),
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();
                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 1000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function saveSourceOfClient(current) {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $(current).serialize(),
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();
                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 1000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function saveRelationshipForm(current) {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $(current).serialize(),
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();
                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        jQuery('#add_relationship_form').trigger("reset");
                        jQuery('#relationship-edit-modal').modal('hide');
                        jQuery('#add-relationship-modal').modal('hide');
                        jQuery('#relationship-edit-modal,.modal-backdrop').remove();
                        $("#relationship_table").DataTable().ajax.reload();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function saveReferredToForm(current) {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $(current).serialize(),
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();
                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        jQuery('#add_referred_to_form').trigger("reset");
                        jQuery('#referred_to-modal').modal('hide');
                        jQuery('#referred_to-edit-modal').modal('hide');
                        jQuery('#referred_to-edit-modal,.modal-backdrop').remove();
                        $("#referral_table").DataTable().ajax.reload();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }
        jQuery("#referrer_type").on("change", function() {
            if (jQuery(this).val() == 1) {
                jQuery("#referrer_id").val("");
                jQuery("#social_media_link").val("");
                if (jQuery("#from_referrer").hasClass("hidden") == false) {
                    jQuery("#from_referrer").addClass("hidden");
                }
                if (jQuery("#from_clients").hasClass("hidden") == false) {
                    jQuery("#from_clients").addClass("hidden");
                }
                if (jQuery("#from_social_media").hasClass("hidden") == false) {
                    jQuery("#from_social_media").addClass("hidden");
                }
                jQuery("#other_referrer_div").removeClass("hidden")

            } else if (jQuery(this).val() == 2) {
                jQuery("#referrer_id").val("");
                jQuery("#other_referrer").val("");
                jQuery("#from_social_media").val("");
                if (jQuery("#from_referrer").hasClass("hidden") == false) {
                    jQuery("#from_referrer").addClass("hidden");
                }
                if (jQuery("#from_social_media").hasClass("hidden") == false) {
                    jQuery("#from_social_media").addClass("hidden");
                }
                if (jQuery("#other_referrer_div").hasClass("hidden") == false) {
                    jQuery("#other_referrer_div").addClass("hidden");
                }
                jQuery("#from_clients").removeClass("hidden")
            } else if (jQuery(this).val() == 4) {
                jQuery("#referrer_id").val("");
                jQuery("#other_referrer").val("");
                jQuery("#from_social_media").val("");
                if (jQuery("#from_referrer").hasClass("hidden") == false) {
                    jQuery("#from_referrer").addClass("hidden");
                }
                if (jQuery("#from_clients").hasClass("hidden") == false) {
                    jQuery("#from_clients").addClass("hidden");
                }
                if (jQuery("#other_referrer_div").hasClass("hidden") == false) {
                    jQuery("#other_referrer_div").addClass("hidden");
                }
                jQuery("#from_social_media").removeClass("hidden")
            } else {
                jQuery("#social_media_link").val("");
                jQuery("#other_referrer").val("");
                if (jQuery("#from_social_media").hasClass("hidden") == false) {
                    jQuery("#from_social_media").addClass("hidden");
                }
                if (jQuery("#other_referrer_div").hasClass("hidden") == false) {
                    jQuery("#other_referrer_div").addClass("hidden");
                }
                if (jQuery("#from_clients").hasClass("hidden") == false) {
                    jQuery("#from_clients").addClass("hidden");
                }

                jQuery("#from_referrer").removeClass("hidden")
            }
        });
    </script>
    <style>
        .datepicker-container {
            z-index: 10000000 !important;
        }

        .select2-container--bootstrap4,
        .select2-container--default {
            width: 100% !important;
        }

        .hidden {
            display: none !important;
        }
    </style>
@endsection
