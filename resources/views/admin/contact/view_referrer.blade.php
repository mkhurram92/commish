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
        Referrer :: {{ $contact->surname }} {{ $contact->preferred_name }}
    @else
        Companay :: {{ $contact->trading }}
    @endif
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                @if ($contact->individual == 1)
                    Referrer :: {{ $contact->surname . ' ' . $contact->preferred_name }}
                @else
                    Company :: {{ $contact->trading }}
                @endif
                <a class="btn btn-primary text-white" href="{{ route('admin.contact.edit', encrypt($contact->id)) }}"><i
                        class="pe-7s-pen btn-icon-wrapper"></i> </a>
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
                <li class="breadcrumb-item active" aria-current="page"> View Client::{{ $contact->preferred_name }}
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
                    <!--<li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                            <span>Referrals</span>
                        </a>
                    </li>-->
                    <!--<li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                            <span>Relations</span>
                        </a>
                    </li>-->

                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6">
                            <span>Commission Model</span>
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
                                                        <p class="mb-2"><b>Referrer Type :
                                                            </b></b>{{ $contact->individual == 1 ? 'Individual' : 'Company' }}
                                                        </p>
                                                        <p class="mb-2"><b>Trading/Business : </b>{{ $contact->trading }}
                                                        </p>
                                                        <p class="mb-2"><b>Principle Contact :
                                                            </b>{{ $contact->principle_contact }}</p>
                                                        <p class="mb-2"><b>Trust Name : </b>{{ $contact->trust_name }}
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
                                                                target="_blank">{{ $contact->entity_name }}</a></p>
                                                        <p class="mb-2">
                                                            <b>Industry : </b>{{ $contact->industry_name }}
                                                        </p>
                                                        <p class="mb-2">
                                                            <b>Subject to GST : </b>{{ $contact->has_gst == 1 ? 'Yes' : 'No' }} 
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
                                    <!--
                                    <div class="add-btn">
                                    <button class="btn btn-primary edit-address" data-href="{{ route('admin.contact.viewEditAddress', ['contact_id' => encrypt($contact->id)]) }}">Edit Address</button>
                                </div>
                                -->
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
                        {{-- <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 ">
                <div class="row">
                    <div class=" col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Source of Client
                                </div>
                            </div>
                            <div class="card-body">
                               <div class="row">
                                   <div class="col-sm-4">
                                       <p class="mb-2"><b>Reffered By Existing Client:</b><br/>
                                           @if ($contact->reffered_by_client_display_name != '' && $contact->reffered_by_existing_client > 0)
                                               <a href="{{route('admin.contact.view',encrypt($contact->reffered_by_existing_client))}}"
                                                  target="_blank"> {{ $contact->reffered_by_client_display_name  }}</a>
                                            @else
                                                -
                                               @endif
                                       </p>
                                   </div>
                                   <div class="col-sm-4">
                                       <p class="mb-2"><b>Referror:</b><br/>
                                           @if ($contact->refferors_client_display_name != '' && $contact->refferor > 0)
                                               <a href="{{route('admin.contact.view',encrypt($contact->refferor))}}"
                                                  target="_blank">{{
                                          $contact->refferors_client_display_name }}</a>
                                               @else
                                              -
                                            @endif
                                       </p>
                                   </div>
                                   <div class="col-sm-4">
                                       <p class="mb-2"><b>Referror's Relation to client:</b><br/>{{
                                    ($contact->refferor_relation_display_client != '' ?  $contact->refferor_relation_display_client : '-')}}</p>
                                   </div>
                                   <div class="col-sm-12">
                                       <p class="mb-2"><b>Note:</b><br/>{{
                                    ($contact->refferor_note != '' ?  $contact->refferor_note : '-')}}</p>
                                   </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

                        <!--  @if (count($contact->tasks) > 0)
    <div class="col-lg-12 col-md-12">
                            <div class="main-card mb-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Tasks
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table-bordered table">
                                                        <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Followup Date</th>
                                                            <th>Processor</th>
                                                            <th>Details</th>
                                                            <th>User</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($contact->tasks as $ke => $relationDetail)
    <tr>
                                                                <td>{{ $ke + 1 }}</td>
                                                                <td>{{ $relationDetail->followup_date }}</td>
                                                                <td>{{ $relationDetail->processor }}</td>
                                                                <td>{{ $relationDetail->details }}</td>
                                                                <td>{{ $relationDetail->user }}</td>
                                                            </tr>
    @endforeach
         
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    @endif-->
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="main-card mb-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            @if ($client_referrals)
                                                @foreach ($client_referrals as $k => $client_referral)
                                                    <div class="col-sm-4">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <div class="card-title">
                                                                    Reffered To {{ $k + 1 }}
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="mb-2">
                                                                    <b>Name:</b>{{ $client_referral->referred_to }}
                                                                </p>
                                                                <p class="mb-2">
                                                                    <b>Service:</b>{{ $client_referral->service->name }}
                                                                </p>
                                                                <p class="mb-2">
                                                                    <b>Date:</b>{{ date('M d, Y', strtotime($client_referral->date)) }}
                                                                </p>
                                                                <p class="mb-2">
                                                                    <b>Notes:</b>{{ $client_referral->notes }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif




                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">


                    <div class="row">


                        <div class="col-lg-12 col-md-12">
                            <div class="main-card mb-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Relations
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table-bordered table">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Name</th>
                                                                <th>Relation</th>
                                                                <!-- <th>Mail Out?</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($contact->relationDetails) > 0)
                                                                @foreach ($contact->relationDetails as $ke => $relationDetail)
                                                                    <tr>
                                                                        <td>{{ $ke + 1 }}</td>
                                                                        <td><a href="{{ route('admin.contact.view', encrypt($relationDetail->client_relation_id)) }}"
                                                                                target="_blank" class="active_a">
                                                                                {{ $relationDetail->surname . ' ' . $relationDetail->first_name }}</a>
                                                                        </td>
                                                                        <td>{{ $relationDetail->client_relation_label }}
                                                                        </td>
                                                                        <!-- <td>{{ $relationDetail->mailout_display }}</td> -->
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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

                <div class="tab-pane tabs-animation fade show" id="tab-content-4" role="tabpanel">
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

                <div class="tab-pane tabs-animation fade" id="tab-content-6" role="tabpanel">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-body">

                                        <form method="post"
                                            action="{{ isset($taskdata)
                                                ? route('admin.referrercom.update', [encrypt($taskdata->id), encrypt($contact->id)])
                                                : route('admin.referrercom.post', encrypt($contact->id)) }}"
                                            id="commission_model_form" onsubmit="return saveCommissionModelForm(this)">
                                            @csrf
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="form-row">
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <label class="form-label font-weight-bold">Commission Model</label>
                                                        <select name="commission_model" id="commission_model"
                                                            class="form-control">
                                                            <option value="">Select Model</option>
                                                            @foreach ($commission_models as $commission_model)
                                                                <option value="{{ $commission_model->id }}"
                                                                    {{ isset($contact) && $contact->commission_model_id == $commission_model->id ? 'selected="selected"' : '' }}>
                                                                    {{ $commission_model->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <label class="form-label font-weight-bold">Upfront(%)</label>
                                                        <input name="upfront_per" id="upfront_per" type="text"
                                                            class="form-control text-lowercase number-input"
                                                            data-max="100" data-min="0" placeholder="Upfront" required
                                                            value="{{ $contact->upfront_per }}">
                                                        @if ($errors->has('upfront_per'))
                                                            <div class="error" style="color:red">
                                                                {{ $errors->first('upfront_per') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <label class="form-label font-weight-bold">Trail(%)</label>
                                                        <input name="trail_per" id="trail_per" type="text"
                                                            class="form-control text-lowercase number-input"
                                                            data-max="100" data-min="0" placeholder="Trail" required
                                                            value="{{ $contact->trail_per }}">
                                                        @if ($errors->has('trail_per'))
                                                            <div class="error" style="color:red">
                                                                {{ $errors->first('trail_per') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <label class="form-label font-weight-bold">Flat Fee Charge</label>
                                                        <input name="flat_fee_chrg" id="flat_fee_chrg" type="text"
                                                            class="form-control text-lowercase number-input"
                                                            placeholder="Flat Fee Chrg" required
                                                            value="{{ $contact->flat_fee_chrg }}">
                                                        @if ($errors->has('flat_fee_chrg'))
                                                            <div class="error" style="color:red">
                                                                {{ $errors->first('flat_fee_chrg') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <button class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                                <div class="clearfix clear"></div>
                                                <table class="table-bordered mb-0 table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Institute</th>
                                                            <th>Upfront(%)</th>
                                                            <th>Trail(%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($institutes as $key => $institute)
                                                            <tr><?php
                                                            $rcmi = DB::table('referrer_commission_model_institution')
                                                                ->where('referrer_com_mo_inst_id', $contact->rcomid)
                                                                ->where('lender_id', $institute->id)
                                                                ->first();
                                                            //print_R($rcmi->upfront);
                                                            ?>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $institute->name != '' ? $institute->name : $institute->code }}
                                                                </td>
                                                                <td><input type="hidden"
                                                                        name="institutes_model[{{ $institute->id }}][id]"
                                                                        value="{{ $institute->id }}" class="form-control"
                                                                        data-max="100" /><input type="text"
                                                                        class="form-control upfront_amnt number-input"
                                                                        placeholder="Upfront" data-max="100"
                                                                        name="institutes_model[{{ $institute->id }}][upfront]"
                                                                        id="institutes_model_{{ $institute->id }}_upfront"
                                                                        value="{{ isset($rcmi->upfront) ? $rcmi->upfront : '' }}" />
                                                                </td>
                                                                <td><input type="text"
                                                                        class="form-control trail_amnt number-input"
                                                                        data-max="100"
                                                                        name="institutes_model[{{ $institute->id }}][trail]"
                                                                        id="institutes_model_{{ $institute->id }}_trail"
                                                                        placeholder="Trail"
                                                                        value="{{ isset($rcmi->trail) ? $rcmi->trail : '' }}" />
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="col-sm-12"></div>

                                            </div>
                                        </form>

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
        $(document).on("click", ".edit-address", function(e) {
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

        function showAddTasks() {
            jQuery('#add_task_form').trigger("reset");
            jQuery('#detail').text("");
            jQuery('#add_task_form').attr('action', '{{ route('admin.contacttsk.post', encrypt($contact->id)) }}')
            jQuery('#tasks-modal').modal('show')
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

                            jQuery('#edit_id').val(deal.id);
                            jQuery('#followup_date').val(dealtaskdata.followup_date);
                            jQuery('#end_date').val(dealtaskdata.end_date);
                            //jQuery('#processor').val(dealtaskdata.processor);
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
                            var edit_row = '<a data-url="{{ url('admin/contact-tasks/get-record/') }}/' +
                                value
                                .encrypt_id +
                                '/{{ encrypt($contact->id) }}" ' +
                                'data-id="' + value
                                .id + '" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit"  class="pe-7s-pen btn-icon-wrapper" ' +
                                'style="color:#fff"></i></a>';


                            var TempObj = {
                                "Index no": value.id,
                                "User": value.user,
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
                                                <option value="{{ $user->id }}">
                                                    {{ $user->given_name . ' ' . $user->surname }}
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
        var deal_enc = '{{ encrypt($contact->id) }}'

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


        jQuery(document).ready(function() {
            $("#task_user").select2()

            //jQuery('.upfront_amnt').val('<?php echo $contact->upfront_per; ?>');
            //jQuery('.trail_amnt').val('<?php echo $contact->trail_per; ?>');

            jQuery('body').on('keyup blur keypress', '#upfront_per', function() {
                var currentVal = jQuery(this).val()
                jQuery('.upfront_amnt').val(currentVal);

            })

            jQuery('body').on('keyup blur keypress', '#trail_per', function() {
                var currentVal = jQuery(this).val()
                jQuery('.trail_amnt').val(currentVal);
            })

            jQuery('body').on('change', '#commission_model', function() {
                var curVal = jQuery(this).val();

                jQuery('.upfront_amnt').val(0);
                jQuery('.trail_amnt').val(0);
                jQuery('#upfront_per').val('');
                jQuery('#trail_per').val('');
                jQuery('#flat_fee_chrg').val('');
                jQuery('#bdm_flat_fee_per').val('');
                jQuery('#bdm_upfront_per').val('');
                if (curVal != '') { //console.log(curVal);
                    $.ajax({
                        url: '{{ route('admin.referrercom.getcmml', encrypt($contact->id)) }}',
                        type: 'POST',
                        data: {
                            "com_model": curVal
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", $(
                                'meta[name="csrf-token"]').attr('content'));

                        },
                        success: function(data) {

                            if (!$.isEmptyObject(data.error)) {
                                printErrorMsg(data.error);
                                hideLoader();

                            } else if (!$.isEmptyObject(data.errors)) {
                                printErrorMsg(data.errors);
                                hideLoader();
                            } else {
                                if (!$.isEmptyObject(data.comm_model)) {
                                    jQuery('#upfront_per').val(data.comm_model.upfront_per)
                                    jQuery('#trail_per').val(data.comm_model.trail_per)
                                    jQuery('#flat_fee_chrg').val(data.comm_model.flat_fee_chrg)
                                    jQuery('#bdm_flat_fee_per').val(data.comm_model
                                        .bdm_flat_fee_per)
                                    jQuery('#bdm_upfront_per').val(data.comm_model
                                        .bdm_upfront_per)
                                }

                                if (!$.isEmptyObject(data.comm_insti)) {
                                    jQuery(data.comm_insti).each(function(ikey, ival) {
                                        jQuery('#institutes_model_' + ival.lender_id +
                                            '_upfront').val(parseFloat(ival.upfront)
                                            .toFixed(2))
                                        jQuery('#institutes_model_' + ival.lender_id +
                                            '_trail').val(parseFloat(ival.trail)
                                            .toFixed(2))
                                    })
                                }
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
                }
                return false;
            })
        })

        function saveCommissionModelForm(current) {
            //alert('gg');
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $("#commission_model_form").serialize(),
                success: function(data) {
                    // console.log(data);
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();

                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        /* setTimeout(function(){
                            location.reload()
                        },1000); */
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
