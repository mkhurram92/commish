@extends('layout.main')
@push('style-section')
@endpush
@section('title')
@if (isset($broker) && !empty($broker))
        @if ($broker->is_individual == 1)
            View Broker :: {{ $broker->surname }} {{ $broker->given_name }}
        @else
            View Broker :: {{ $broker->trading }}
        @endif
    @else
        View Broker
    @endif   
@endsection
@section('page_title_con')

<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">
            @if (isset($broker) && !empty($broker))
                @if ($broker->is_individual == 1)
                    View Broker :: {{ $broker->surname }} {{ $broker->given_name }}
                @else
                    View Broker :: {{ $broker->trading }}
                @endif
            @else
                View Broker
            @endif 
            <a class="btn
                btn-primary text-white" href="{{route('admin.brokers.edit',encrypt($broker->id))}}"><i
                    class="pe-7s-pen
                btn-icon-wrapper"></i> </a>
        </h4>
    </div>
    <div class="page-rightheader ml-auto d-lg-flex d-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
            <li class="breadcrumb-item " aria-current="page">
                <a href="{{route('admin.contact.list') }}">Contacts</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">                             View Broker::{{$broker->given_name}}
            </li>
        </ol>
    </div>
</div>
@endsection
@section('body')
    <div id="" class="panel panel-primary">
        <div class="tab-menu-heading">
            <div class="tabs-menu ">
    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav panel-tabs">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                <span>Information</span>
            </a>
        </li>
       
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6">
                <span>Commission Model</span>
            </a>
        </li>
        
        @if (!$broker->parent_broker > 0)
            <li class="nav-item">
                <a role="tab" class="nav-link" id="tab-8" data-toggle="tab" href="#tab-content-8">
                    <span>Broker Staff</span>
                </a>
            </li>
        @endif
        

    </ul>
            </div>
        </div>
        <div class="panel-body tabs-menu-body">
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="row mb-3">
                <div class="col-sm-3">
                  <div class="card">
                      <div class="card-body">
                          <p class="mb-2"><b>Broker Type : </b>{{($broker->is_individual == 1 ? 'Individual' : 'Company')}}</p>
                          <p class="mb-2"><b>Trading/Business : </b>{{$broker->trading}}</p>
                          <p class="mb-2"><b>Trust Name : </b>{{$broker->trust_name}}</p>
                          <p class="mb-2"><b>Entity Name : </b>{{$broker->entity_name}}</p>
                          <p class="mb-2"><b>Broker Status : </b></b>{{($broker->is_active == 1 ? 'Active' : 'Inactive')}}</p>
                      </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="card">
                      <div class="card-body">
                          <p class="mb-2"><b>Salutation : </b>{{$broker->salutation}}</p>
                          <p class="mb-2"><b>Surname : </b>{{$broker->surname}}</p>
                          <p class="mb-2"><b>Given Name : </b>{{$broker->given_name}}</p>
                          <p class="mb-2"><b>DOB : </b>{{$broker->dob}}</p>
                          <p class="mb-2"><b>ABN : </b>{{$broker->abn}}</p>
                      </div>
                  </div>

                </div>
                <div class="col-sm-3">
                   <div class="card">
                       <div class="card-body">
                           <p class="mb-2"><b>Work Phone : </b>{{$broker->work_phone}}</p>
                           <p class="mb-2"><b>Home Phone : </b>{{$broker->home_phone}}</p>
                           <p class="mb-2"><b>Mobile Phone : </b>{{$broker->mobile_phone}}</p>
                           <p class="mb-2"><b>Email : </b>{{$broker->email}}</p>
                           <p class="mb-2"><b>Web : </b>{{$broker->web}}</p>
                       </div>
                   </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                           <p class="mb-2"><b>Fax : </b>{{$broker->fax}}</p>
                            <p class="mb-2"><b>Address : </b>{{$broker->business}}</p>
                            <p class="mb-2"><b>State : </b>{{$broker->state_name}}</p>
                            <p class="mb-2"><b>City : </b>{{$broker->city_name}}</p>
                            <p class="mb-2"><b>Postal Code : </b>{{$broker->pincode}}</p>
                        </div>
                    </div>

                </div>
            </div>
 
        </div>
        
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 ">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 ">
                               <div class="card">
                                   <div class="card-header">
                                       <div class="card-title">
                                           Other
                                       </div>
                                   </div>
                                   <div class="card-body">
                                       <p class="mb-2"><b>ABN : </b>{{ ($broker->abn != '') ? $broker->abn :'-'  }}</p>
                                       <p class="mb-2"><b>Start Date : </b>{{ ($broker->start_date!='') ? $broker->start_date : '-' }}</p>
                                       <p class="mb-2"><b>End Date : </b>{{ ($broker->end_date != '') ? $broker->end_date : '-' }}</p>
                                       <p class="mb-2"><b>Subject To GST? : </b>{{ ($broker->subject_to_gst > 0) ? 'Yes' : 'No' }}</p>
                                   </div>
                               </div>
                            </div>

                            <div class="col-sm-6 ">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            Bank Detail
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2"><b>Bank Name : </b>{{ ($broker->bank != '') ? $broker->bank : '-' }}</p>
                                        <p class="mb-2"><b>Account Name : </b>{{ ($broker->account_name != '') ? $broker->account_name :'-'  }}</p>
                                        <p class="mb-2"><b>BSB : </b>{{ ($broker->bsb != '') ? $broker->bsb : '-' }}</p>
                                        <p class="mb-2"><b>Account Number : </b>{{ ($broker->account_number!='') ? $broker->account_number : '-' }}</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
                  <div class="row mb-3">
               <div class="col-sm-12">
                   <div class="card">
                       <div class="card-body">
                           <div class="row">
                               <div class="col-sm-12">
                                   <p class="mb-2"><b>Note:</b><br/>
                                       {{ ($broker->note != '') ? $broker->note :'-'  }}
                                   </p>
                               </div>

                           </div>

                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-1" role="tabpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered table">
                                    <thead>
                                    <tr>
                                        <th>Referror</th>
                                        <th>Entity</th>
                                        <th>Upfront %</th>
                                        <th>Trail %</th>
                                        <th>Comm per Deal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($broker->referrorClients) > 0)
                                        @foreach($broker->referrorClients as $referrorClient)
                                            <tr>
                                                <td>{{ $referrorClient->referrors_name }}</td>
                                                <td>{{ $referrorClient->entity }}</td>
                                                <td>{{ $referrorClient->upfront }}</td>
                                                <td>{{ $referrorClient->trail }}</td>
                                                <td>{{ $referrorClient->comm_per_deal }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="text-align: center" colspan="5">No Records.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane tabs-animation fade " id="tab-content-8" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12 d-flex justify-content-between">
                                <div class="title d-flex align-items-center">
                                    <h4 class="mb-0">Broker staff</h4>
                                </div>
                                <div class="btn">
                                    <button class="btn btn-primary" id="add-broker-staff">Add Broker staff</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered table" id="broker_staff_table" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>Surname</th>
                                            <th>Given Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($broker?->broker_staff as $staff)
                                            <tr>
                                                <td>{{$staff->surname}}</td>
                                                <td>{{$staff->given_name}}</td>
                                                <td>{{ $staff->email ==0 ? '' : $staff->email}}</td>
                                                <td>{{ $staff->mobile == 0 ? '' : $staff->mobile}}</td>
                                                <td>
                                                    <a href="javascript:void(0)" data-href="{{ route("admin.brokers.editBrokerStaff",['broker_staff_id'=>encrypt($staff->id)]) }}" class="btn btn-primary edit-crud">Edit</a>
                                                    <a href="javascript:void(0)" data-href="{{ route("admin.brokers.deleteBrokerStaff",['broker_staff_id'=>encrypt($staff->id)]) }}" class="btn btn-danger delete-crud">Delete</a>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Surname</th>
                                            <th>Given Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
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

        <div class="tab-pane tabs-animation fade " id="tab-content-4" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-right" onclick="return showAddFee()">Add Fee</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table style="width: 100%;max-width:none !important" id="TableDataf" class=" table-hover
                                    table-striped
                                    table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                    data-sAjaxSource="{{ route('admin.brokerfee.getrecords',encrypt($broker->id)) }}"
                                    data-aoColumns='{"mData": "Index no"},{"mData": "Type"},{"mData": "Frequency"}, {"mData": "Due Date"},{"mData": "Amount"},{"mData": "Added On"},{"mData": "Modified On"},{"mData": "Action"}'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type </th>
                                            <th>Frequency</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
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
                                            <th>Frequency</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
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
            </div>
        </div>


        <div class="tab-pane tabs-animation fade " id="tab-content-6" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post"
                            action="{{isset($taskdata)?route('admin.brokercom.update', [encrypt
                            ($taskdata->id),
                            encrypt($broker->id)]):route('admin.brokercom.post',encrypt($broker->id))}}" id="commission_model_form" onsubmit="return saveCommissionModelForm(this)">
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
                                      <div class="position-relative  form-group">
                                          <label class="form-label font-weight-bold">Commission Model</label> 
                                          <select name="commission_model" id="commission_model" class="form-control">
                                              <option value="">Select Model</option>
                                              @foreach($commission_models as $commission_model)
                                                  <option value="{{$commission_model->id}}" 
                                                      {{ isset($broker) && $broker->commission_model_id == $commission_model->id ? 'selected="selected"' : '' }}
                                                      >{{ $commission_model->name }}</option>
                                                  @endforeach
                                              </select>
                                      </div>
                                  </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Upfront(%)</label>
                                          <input name="upfront_per" id="upfront_per" type="text" class="form-control
                                          text-lowercase number-input" data-max="100" data-min="0" placeholder="Upfront" required  value="{{$broker->upfront_per}}">
                                          @if($errors->has('upfront_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('upfront_per')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Trail(%)</label>
                                          <input name="trail_per" id="trail_per" type="text" class="form-control
                                          text-lowercase number-input" data-max="100" data-min="0" placeholder="Trail" required value="{{$broker->trail_per}}">
                                          @if($errors->has('trail_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('trail_per')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Flat Fee Charge</label>
                                          <input name="flat_fee_chrg" id="flat_fee_chrg" type="text" class="form-control
                                          text-lowercase number-input" placeholder="Flat Fee Chrg" required value="{{$broker->flat_fee_chrg}}">
                                          @if($errors->has('flat_fee_chrg'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('flat_fee_chrg')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                    <button class="mt-1 btn btn-primary">Submit</button>
                                   </div>
                              <div class="clearfix clear"></div>
                              <table class="mb-0 table table-bordered">
                                      <thead>
                                          <tr>
                                              <th>#</th>
                                              <th>Institute</th>
                                              <th>Upfront(%)</th>
                                              <th>Trail(%)</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($institutes as $key => $institute)
                                              <tr><?php 
                                              $bcmi = DB::table('broker_commission_model_institution')->where('broker_com_mo_inst_id', $broker->bcomid)->where('lender_id', $institute->id)->first();
                                              //print_R($bcmi->upfront);
                                              ?>
                                                  <td>{{ ($key + 1) }}</td>
                                                  <td>{{($institute->name != '') ? $institute->name : $institute->code}}</td>
                                                  <td><input type="hidden" name="institutes_model[{{$institute->id}}][id]" value="{{ $institute->id }}" class="form-control" data-max="100" /><input type="text" class="form-control upfront_amnt number-input" placeholder="Upfront" data-max="100" name="institutes_model[{{$institute->id}}][upfront]" id="institutes_model_{{$institute->id}}_upfront" value="{{isset($bcmi) ? $bcmi->upfront:''}}" /></td>
                                                  <td><input type="text" class="form-control trail_amnt number-input" data-max="100" name="institutes_model[{{$institute->id}}][trail]" id="institutes_model_{{$institute->id}}_trail" placeholder="Trail" value="{{isset($bcmi) ? $bcmi->trail : ''}}" /></td>
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
<script>
    $("#broker_staff_table").DataTable({
        "order":[]
    });
    $(document).on("click","#add-broker-staff",function(e){
        $("#add-broker-staff-modal").modal("show");
    })
    $(document).on("click",".edit-crud",function(e){
        showLoader();
        $.get(e.target.getAttribute("data-href"),function(res){
            hideLoader();
            if(res?.html){
                $("#crud_modal").html(res?.html);
                $("#crud_modal").find(".modal").modal("show");
            }
        })
    })
    $(document).on("click",".delete-crud",function(e){
        showLoader();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: jQuery(e.target).attr('data-href'),
                    type:'POST',
                    data: $(e.target).serialize(),
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(data) {
                        if(!$.isEmptyObject(data.error)){
                            printErrorMsg(data.error);
                            hideLoader();
                        }else if(!$.isEmptyObject(data.errors)){
                            printErrorMsg(data.errors);
                            hideLoader();
                        }else{
                            if(data?.success){
                                successMessage(data.success);
                                setTimeout(() => {
                                    window.location.reload(true);
                                }, 1000);
                            }else{
                                errorMessage("unknown error");
                            }
                        }
                    },error:function(jqXHR, textStatus, errorThrown)
                    {
                        if(IsJsonString(jqXHR.responseText))
                        {
                            var respo =JSON.parse(jqXHR.responseText);
                            errorMessage(message);
                            printErrorMsg(respo.errors)
                            hideLoader();
                        }else{
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    }
                });
            }
        })
    })
    var dataTableexpense;
var dataTableexpenseAjaxParams = {};


function showAddFee()
{
    jQuery('#add_fee_form').attr('action','{{ route('admin.brokerfee.post',encrypt($broker->id))  }}')
    jQuery('#fee-modal').modal('show')
}


function refreshdataTableexpense(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTableexpense != 'undefined' && typeof dataTableexpense == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTableexpense.destroy();
    }
    if (jQuery('#TableDatae').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDatae').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDatae thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDatae thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDatae').attr('data-allowexport');
        var allowCardView = jQuery('#TableDatae').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDatae').attr('data-orientation');
        var dtPageSize = jQuery('#TableDatae').attr('data-pagesize');
        var keys = jQuery('#TableDatae').attr('data-keys');
        var initcallback = jQuery('#TableDatae').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTableexpense = jQuery('#TableDatae').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDatae').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTableexpenseAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDatae').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTableexpense.columns.adjust();
        }, 100);
    }
}

var dataTablefees;
var dataTablefeesAjaxParams = {};
function refreshdataTablefees(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTablefees != 'undefined' && typeof dataTablefees == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTablefees.destroy();
    }
    if (jQuery('#TableDataf').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDataf').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDataf thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDataf thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDataf').attr('data-allowexport');
        var allowCardView = jQuery('#TableDataf').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDataf').attr('data-orientation');
        var dtPageSize = jQuery('#TableDataf').attr('data-pagesize');
        var keys = jQuery('#TableDataf').attr('data-keys');
        var initcallback = jQuery('#TableDataf').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTablefees = jQuery('#TableDataf').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDataf').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTablefeesAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDataf').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTablefees.columns.adjust();
        }, 100);
    }
}

var dataTablecerts;
var dataTablecertsAjaxParams = {};
function refreshdataTablecerts(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTablecerts != 'undefined' && typeof dataTablecerts == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTablecerts.destroy();
    }
    if (jQuery('#TableDatac').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDatac').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDatac thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDatac thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDatac').attr('data-allowexport');
        var allowCardView = jQuery('#TableDatac').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDatac').attr('data-orientation');
        var dtPageSize = jQuery('#TableDatac').attr('data-pagesize');
        var keys = jQuery('#TableDatac').attr('data-keys');
        var initcallback = jQuery('#TableDatac').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTablecerts = jQuery('#TableDatac').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDatac').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTablecertsAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDatac').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTablecerts.columns.adjust();
        }, 100);
    }
}


jQuery(document).ready(function(){

   // jQuery('.upfront_amnt').val('<?php echo $broker->upfront_per;?>');
    //jQuery('.trail_amnt').val('<?php echo $broker->trail_per;?>');

jQuery('body').on('keyup blur keypress','#upfront_per',function(){
    var currentVal  = jQuery(this).val()
    jQuery('.upfront_amnt').val(currentVal);

})

jQuery('body').on('keyup blur keypress','#trail_per',function(){
    var currentVal  = jQuery(this).val()
    jQuery('.trail_amnt').val(currentVal);
})

jQuery('body').on('change','#commission_model',function(){
    var curVal =  jQuery(this).val();
    
    jQuery('.upfront_amnt').val(0);
    jQuery('.trail_amnt').val(0);
    jQuery('#upfront_per').val('');
    jQuery('#trail_per').val('');
    jQuery('#flat_fee_chrg').val('');
    jQuery('#bdm_flat_fee_per').val('');
    jQuery('#bdm_upfront_per').val('');
    if(curVal != '')
    {//console.log(curVal);
                $.ajax({
            url: '{{ route("admin.brokercom.getcmml",encrypt($broker->id)) }}',
            type:'POST',
            data:  {"com_model":curVal},
            beforeSend: function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

            },
            success: function(data) {

                if(!$.isEmptyObject(data.error)){
                    printErrorMsg(data.error);
                    hideLoader();

                }else if(!$.isEmptyObject(data.errors)){
                    printErrorMsg(data.errors);
                    hideLoader();
                }else{
                     if(!$.isEmptyObject(data.comm_model))
                     {
                         jQuery('#upfront_per').val(data.comm_model.upfront_per)
                         jQuery('#trail_per').val(data.comm_model.trail_per)
                         jQuery('#flat_fee_chrg').val(data.comm_model.flat_fee_chrg)
                         jQuery('#bdm_flat_fee_per').val(data.comm_model.bdm_flat_fee_per)
                         jQuery('#bdm_upfront_per').val(data.comm_model.bdm_upfront_per)
                     }

                     if(!$.isEmptyObject(data.comm_insti))
                     {
                         jQuery(data.comm_insti).each(function(ikey,ival){
                                jQuery('#institutes_model_'+ival.lender_id+'_upfront').val(parseFloat(ival.upfront).toFixed(2))
                                jQuery('#institutes_model_'+ival.lender_id+'_trail').val(parseFloat(ival.trail).toFixed(2))
                         })
                     }
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
    }
    return false;
})
})
function saveCommissionModelForm(current)
{

    showLoader();
    $.ajax({
        url: jQuery(current).attr('action'),
        type:'POST',
        data:  $("#commission_model_form").serialize(),
        success: function(data) {

            if(!$.isEmptyObject(data.error)){
                printErrorMsg(data.error);
                hideLoader();

            }else if(!$.isEmptyObject(data.errors)){
                printErrorMsg(data.errors);
                hideLoader();
            }else{
                successMessage(data.success);
                /* setTimeout(function(){
                    location.reload()
                },1000); */
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

</script>
@endpush


@section('modal-section')
    <div id="crud_modal"></div>
    <div class="modal fade add-broker-staff-modal" id="add-broker-staff-modal" tabindex="-1" role="dialog" aria-labelledby="Broker Staff" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expensetitle">Add Broker Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route("admin.brokers.addBrokerStaff",['broker_id'=>encrypt($broker->id)]) }}" onsubmit="return saveAddBrokerStaffForm(this)" id="add_broker_staff_form">
                        @csrf
                        <div class="form-row">
                            <input type="hidden" name="edit_id" id="broker_staff_edit_id" />
                            <div class="col-sm-6">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Surname</label>
                                    <input name="surname" id="name" type="text" class="form-control text-lowercase" required value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Given Name</label>
                                    <input name="given_name" id="name" type="text" class="form-control text-lowercase" required value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Email</label>
                                    <input name="email" id="email" type="email" class="form-control text-lowercase" required value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Phone</label>
                                    <input name="phone" id="phone" type="text" class="form-control text-lowercase" required value="">
                                </div>
                            </div>
                            <button type="submit" class="mt-1 btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script>
        var deal_enc = '{{encrypt($broker->id)}}'
        function saveAddBrokerStaffForm(current)
        {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $(current).serialize(),
                success: function(data) {
                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();
                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_broker_staff_form').trigger("reset");
                        jQuery('#add-broker-staff-modal').modal('hide');
                        window.location.reload();
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
        function saveForm(current)
        {

            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("form").serialize(),
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_task_form').trigger("reset");
                        jQuery('#tasks-modal').modal('hide');
                        refreshTableTablet();
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
    </script>
    <style>
        .datepicker-container{
            z-index: 9999 !important;
        }
    </style>
@endsection
