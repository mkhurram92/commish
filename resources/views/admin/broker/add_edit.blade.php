@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if (isset($broker) && !empty($broker))
        @if ($broker->is_individual == 1)
            Edit Broker :: {{ $broker->surname }} {{ $broker->given_name }}
        @else
            Edit Broker :: {{ $broker->trading }}
        @endif
    @else
        Add Broker
    @endif
@endsection
@section('page_title_con')

<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">
        @if (isset($broker) && !empty($broker))
        @if ($broker->is_individual == 1)
            Edit Broker :: {{ $broker->surname }} {{ $broker->given_name }}
        @else
            Edit Broker :: {{ $broker->trading }}
        @endif
        @else
            Add Broker
        @endif
        </h4>
    </div>
    <div class="page-rightheader ml-auto d-lg-flex d-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon"
                        xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                        <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" /></svg><span
                        class="breadcrumb-icon"> Home</span></a></li>
            <li class="breadcrumb-item " aria-current="page">
                <a href="{{route('admin.contact.list') }}">Contacts</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"> 
                @if (isset($broker) && !empty($broker))
                    @if ($broker->is_individual == 1)
                        Edit Broker :: {{ $broker->surname }} {{ $broker->given_name }}
                    @else
                        Edit Broker :: {{ $broker->trading }}
                    @endif
                    @else
                        Add Broker
                    @endif
            </li>
        </ol>
    </div>
</div>
@endsection
@section('body')
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form method="post"
                    action="{{isset($broker)?route('admin.brokers.update', encrypt($broker->id)):route('admin.brokers.post')}}"
                    onsubmit="return saveForm(this)">
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
                    <div id="" class="panel panel-primary">
                        <div class="panel-body tabs-menu-body">

                            <div class="tab-content">
                                <div id="step-0" class="tab-pane tabs-animation fade show active" role="tabpanel">
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            Basic Information
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="position-relative form-group" id="parent" style="display: none;">
                                            <label class="form-label font-weight-bold">Parent Broker</label>
                                            <select name="parent_broker" id="parent_broker" class="form-control">
                                                <option selected value="">Choose Parent Broker</option>
                                                @foreach($parents as $parent)
                                                <option value="{{$parent->id}}" {{isset($broker) &&
                                                                    $broker->parent_broker == $parent->id ?
                                                                    'selected="selected"' : '' }}>
                                                    {{$parent->display_name}}</option>
                                                @endforeach

                                            </select>
                                            @if($errors->has('parent_broker'))
                                            <div class="error" style="color:red">{{$errors->first('parent_broker')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Broker Type</label>
                                                <select name="individual" id="individual" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="Individual" @if (isset($broker) && $broker->is_individual == '1') selected @endif>
                                                        Individual
                                                    </option>
                                                    <option value="Company" @if (isset($broker) && $broker->is_individual == '2') selected @endif>
                                                        Company
                                                    </option>
                                                </select>
                                                @if ($errors->has('individual'))
                                                    <div class="error" style="color:red">
                                                    {{ $errors->first('individual') }}</div>
                                                 @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 col-sm-12 company-fields">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Company Name</label>
                                            <input name="entity_name" maxlength="255"
                                                value="{{isset($broker)?$broker->entity_name:""}}"
                                                placeholder="Company Name" type="text" class="form-control">
                                            @if($errors->has('entity_name'))
                                            <div class="error" style="color:red">{{$errors->first('entity_name')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 company-fields">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Trading/Business Name</label>
                                            <input name="trading" value="{{isset($broker)?$broker->trading:""}}"
                                                placeholder="Trading/Business" type="text" maxlength="255"
                                                class="form-control">
                                            @if($errors->has('trading'))
                                            <div class="error" style="color:red">{{$errors->first('trading')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 company-fields">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Trust Name</label>
                                            <input name="trust_name" value="{{isset($broker)?$broker->trust_name:""}}"
                                                placeholder="Trust Name" maxlength="255" type="text"
                                                class="form-control">
                                            @if($errors->has('trust_name'))
                                            <div class="error" style="color:red">{{$errors->first('trust_name')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Salutation</label>
                                            <select name="role_title" id="role_title" class="form-control">
                                                <option value="Mr" @if(isset($broker) && $broker->role_title == "mr")
                                                    selected @endif>
                                                    Mr
                                                </option>
                                                <option value="Miss" @if(isset($broker) && $broker->role_title ==
                                                    "miss") selected @endif>
                                                    Miss
                                                </option>
                                                <option value="Mrs" @if(isset($broker) && $broker->role_title == "mrs")
                                                    selected @endif>
                                                    Mrs
                                                </option>
                                                <option value="Ms" @if(isset($broker) && $broker->role_title == "ms")
                                                    selected @endif>
                                                    Ms
                                                </option>
                                                <option value="Dr" @if(isset($broker) && $broker->role_title == "dr")
                                                    selected @endif>
                                                    Dr
                                                </option>
                                                <option value="Prof" @if(isset($broker) && $broker->role_title ==
                                                    "prof") selected @endif>
                                                    Prof.
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Surname</label>
                                            <input name="surname" maxlength="150" value="{{isset($broker)
                                                ?$broker->surname:""}}" placeholder="Surname" type="text"
                                                class="form-control">
                                            @if($errors->has('surname'))
                                            <div class="error" style="color:red">{{$errors->first('surname')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Given Name</label>
                                            <input name="preferred_name" maxlength="255"
                                                value="{{isset($broker)?$broker->given_name:""}}"
                                                placeholder="Given Name" type="text" class="form-control">
                                            @if($errors->has('preferred_name'))
                                            <div class="error" style="color:red">{{$errors->first('preferred_name')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Middle Name</label>
                                            <input name="middle_name" maxlength="255"
                                                   value="{{isset($broker)?$broker->middle_name:""}}"
                                                   placeholder="Middle Name" type="text" class="form-control">
                                            @if($errors->has('middle_name'))
                                                <div class="error" style="color:red">{{$errors->first('middle_name')}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">DOB</label>
                                            <input name="dob" value="{{isset($broker)?$broker->dob:""}}" type="text"
                                                placeholder="dd/mm/yyyy" data-toggle="datepicker" class="form-control">
                                            @if($errors->has('dob'))
                                            <div class="error" style="color:red">{{$errors->first('dob')}}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Status</label>
                                                <select name="is_active" id="is_active" class="form-control">
                                                        <option value="1" @if (isset($broker) && $broker->is_active == 1) selected @endif>
                                                            Active
                                                        </option>
                                                        <option value="0" @if (isset($broker) && $broker->is_active == 0) selected @endif>
                                                            Inactive
                                                        </option>
                                                 </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-header">
                                Contact Details
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Work Phone</label>
                                            <input name="work_phone" value="{{isset($broker)?$broker->work_phone:""}}"
                                                placeholder="Work Phone" type="text"
                                                class="form-control input_int_number" maxlength="15">
                                            @if($errors->has('work_phone'))
                                            <div class="error" style="color:red">{{$errors->first('work_phone')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Home Phone</label>
                                            <input name="home_phone" value="{{isset($broker)?$broker->home_phone:""}}"
                                                placeholder="Home Phone" type="text"
                                                class="form-control input_int_number" maxlength="15">
                                            @if($errors->has('home_phone'))
                                            <div class="error" style="color:red">{{$errors->first('home_phone')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Mobile Phone</label>
                                            <input name="mobile_phone"
                                                value="{{isset($broker)?$broker->mobile_phone:""}}"
                                                placeholder="Mobile Phone" type="text"
                                                class="form-control input_int_number" maxlength="15">
                                            @if($errors->has('mobile_phone'))
                                            <div class="error" style="color:red">{{$errors->first('mobile_phone')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    {{--<div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Fax</label>
                                            <input name="fax" value="{{isset($broker)?$broker->fax:""}}"
                                                placeholder="Fax" type="text" class="form-control input_int_number"
                                                maxlength="15">
                                            @if($errors->has('fax'))
                                            <div class="error" style="color:red">{{$errors->first('fax')}}</div>
                                            @endif
                                        </div>
                                    </div>--}}
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Email</label>
                                            <input name="email" placeholder="Email"
                                                value="{{isset($broker)?$broker->email:""}}" type="email"
                                                class="form-control" maxlength="255">
                                            @if($errors->has('email'))
                                            <div class="error" style="color:red">{{$errors->first('email')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Web</label>
                                            <input name="web" placeholder="Web"
                                                value="{{isset($broker)?$broker->web:''}}" type="text"
                                                class="form-control" maxlength="255">
                                            @if($errors->has('web'))
                                            <div class="error" style="color:red">{{$errors->first('web')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-header">
                                Address
                            </div>
                            <div class="card-body">
                                <div class="form-row">

                                    <div class="col-md-12 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Business</label>
                                            <input type="text" name="business" id="business" class="form-control"
                                                value="{{isset($broker)?$broker->business:""}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">State</label>
                                            <select name="state" id="state" class="multiselect-dropdown ">
                                                <option value="">Select State</option>
                                                @if(count($states)>0)
                                                @foreach($states as $state)
                                                <option value="{{$state->id}}" {{isset($broker) && $broker->state ==
                                                                        $state->id ? 'selected="selected"' : ''}}>
                                                    {{$state->name}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('state'))
                                            <div class="error" style="color:red">{{$errors->first('state')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">City</label>
                                            <input name="city" value="{{isset($broker)?$broker->city:""}}"
                                                placeholder="City" type="text" class="form-control alpha-num" >
                                            @if($errors->has('city'))
                                            <div class="error" style="color:red">{{$errors->first('city')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Postal Code</label>
                                            <input name="postal_code" value="{{isset($broker)?$broker->pincode:""}}"
                                                placeholder="Postal Code" type="text" class="form-control alpha-num"
                                                maxlength="10">
                                            @if($errors->has('postal_code'))
                                            <div class="error" style="color:red">{{$errors->first('postal_code')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-header">
                                Other
                            </div>
                            <div class="card-body">
                                <div class="form-row">


                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">ABN</label>
                                            <input type="text" name="abn" id="abn" class="form-control alpha-num" value="{{(isset($broker) && $broker->abn != '' ?
                                                       $broker->abn : '')}}" maxlength="250">

                                            @if($errors->has('abn'))
                                            <div class="error" style="color:red">{{$errors->first('abn')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Start Date</label>
                                            <input type="text" placeholder="dd/mm/yyyy" data-toggle="datepicker"
                                                name="start_date" id="start_date" class="form-control
                                                " value="{{(isset($broker) && $broker->start_date != '' ?
                                                       $broker->start_date : '')}}" maxlength="15">

                                            @if($errors->has('start_date'))
                                            <div class="error" style="color:red">{{$errors->first('start_date')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">End Date</label>
                                            <input type="text" placeholder="dd/mm/yyyy" data-toggle="datepicker"
                                                name="end_date" id="end_date" class="form-control
                                                " value="{{(isset($broker) && $broker->end_date != '' ?
                                                       $broker->end_date : '')}}" maxlength="15">

                                            @if($errors->has('end_date'))
                                            <div class="error" style="color:red">{{$errors->first('end_date')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                   <!-- <div class="col-md-4 col-sm-12">
                                        <div class="position-relative form-check form-check-inline">
                                            <label>&nbsp;</label>

                                            <label class="custom-switch" style="margin-top: 30px">
                                                <input value="1" id="subject_to_gst" @if(isset($broker) &&
                                                    $broker->subject_to_gst == 1) checked
                                                @endif name="subject_to_gst" type="checkbox"
                                                class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Subject To GST?</span>
                                            </label>
                                            @if($errors->has('subject_to_gst'))
                                            <div class="error" style="color:red">{{$errors->first('subject_to_gst')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header">
                                Bank Detail
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label  font-weight-bold">Bank Name</label>
                                            <input type="text" name="bank" id="bank" class="form-control"
                                                placeholder="Bank Name" value="{{(isset($broker) && $broker->bank != '' ?
                                                       $broker->bank : '')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Account Name</label>
                                            <input type="text" name="acc_name" id="acc_name" class="form-control"
                                                placeholder="Account Name" value="{{(isset($broker) && $broker->account_name != '' ?
                                                       $broker->account_name : '')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label  font-weight-bold">BSB</label>
                                            <input type="text" name="bsb" id="bsb" class="form-control"
                                                placeholder="BSB" value="{{(isset($broker) && $broker->bsb != '' ?
                                                       $broker->bsb : '')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="position-relative form-group">
                                            <label class="form-label  font-weight-bold">Account Number</label>
                                            <input type="text" name="acc_number" id="acc_number"
                                                class="form-control input_int_number" placeholder="Account Number" value="{{(isset($broker) && $broker->account_number != '' ?
                                                       $broker->account_number : '')}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header">

                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="position-relative form-group">
                                            <textarea name="note" id="note" cols="30" rows="5" placeholder="Note"
                                                class="form-control w-100">{{isset($broker)?$broker->note:""}}</textarea>
                                            @if($errors->has('note'))
                                            <div class="error" style="color:red">{{$errors->first('note')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="step-1" class="tab-pane tabs-animation fade " role="tabpanel">
                        <div id="accordion" class="accordion-wrapper mb-3">
                            <div class="card">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="50%">Broker</th>
                                                        <th width="10%">Upfront %</th>
                                                        <th width="10%">Trail %</th>
                                                        <th width="10%">Comm per Deal</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="task_tbody">
                                                    @if(isset($broker,$broker->referrors) && count($broker->referrors) >
                                                    0)
                                                    @foreach($broker->referrors as $tkey => $tval)
                                                    <tr id="task_rw_{{$tkey}}">
                                                        <input type="hidden" name="referrors[{{$tkey}}][old_id]"
                                                            id="referrors_{{$tkey}}_old_id" value="{{$tval->id}}" />
                                                        <td><select name="referrors[{{$tkey}}][referror]"
                                                                id="referrors_{{$tkey}}_referror" class="form-control">
                                                                <option value=""></option>
                                                                @foreach($refferors as $refferor)
                                                                <option value="{{$refferor->id}}" {{ $refferor->id ==
                                                                                    $tval->referror ?
                                                                                    'selected="selected"' : ''

                                                                                    }}>{{$refferor->display_name}}
                                                                </option>
                                                                @endforeach
                                                            </select></td>
                                                        <td><input type="text" name="referrors[{{$tkey}}][upfront]"
                                                                class="
                                                            form-control number-input" data-min="0" data-max="100"
                                                                id="referrors_{{$tkey}}_upfront" placeholder="Upfront"
                                                                maxlength="255" value="{{$tval->upfront}}" /></td>
                                                        <td><input type="text" name="referrors[{{$tkey}}][trail]" class="
                                                            form-control number-input" id="referrors_{{$tkey}}_trail"
                                                                placeholder="Trail" maxlength="255"
                                                                value="{{$tval->trail}}" /></td>
                                                        <td><input type="text"
                                                                name="referrors[{{$tkey}}][comm_per_deal]"
                                                                class=" form-control number-input" data-min="0"
                                                                data-max="100" id="referrors_{{$tkey}}_comm_per_deal"
                                                                placeholder="Comm per deal"
                                                                value="{{$tval->comm_per_deal}}" /></td>
                                                        <td>
                                                            <button class="btn btn-danger"
                                                                onclick="return removeReferror(this)"
                                                                data-id="{{$tkey}}"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr id="task_rw_0">
                                                        <td><select name="referrors[0][referror]"
                                                                id="referrors_0_referror"
                                                                class="form-control referrors_sel_cl">
                                                                <option value=""></option>
                                                                @foreach($refferors as $refferor)
                                                                <option value="{{$refferor->id}}">
                                                                    {{$refferor->display_name}}</option>
                                                                @endforeach
                                                            </select></td>
                                                        <td><input type="text" name="referrors[0][upfront]" class="
                                                            form-control number-input" data-min="0" data-max="100"
                                                                id="referrors_0_upfront" placeholder="Upfront"
                                                                maxlength="255" /></td>
                                                        <td><input type="text" name="referrors[0][trail]" class="
                                                            form-control number-input" id="referrors_0_trail"
                                                                placeholder="Trail" maxlength="255" /></td>
                                                        <td><input type="text" name="referrors[0][comm_per_deal]"
                                                                class=" form-control number-input" data-min="0"
                                                                data-max="100" id="referrors_0_comm_per_deal"
                                                                placeholder="Comm per deal" /></td>
                                                        <td></td>
                                                    </tr>
                                                
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-5">
                                    <div class="float-right">
                                        <button type="button" class="btn btn-primary" id="add_new_referror"
                                            onclick="addNewReferror(this)">Add new Referror
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



            </div>
        </div>

    </div>
    <div class="divider"></div>
    <div class="clearfix">
        {{--<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset </button>--}}
        {{-- <button type="button" id="" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary" onclick="return previousnext(1)">Next</button> --}}
        
        <button type="submit" id="finish-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-success">Finish</button>
        
        {{-- <button type="button" id="" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary" onclick="return previousnext(0)">Previous</button> --}}
    </div>
    </form>
</div>
</div>
</div>
</div>
@endsection

@push('script-section')
<script type="text/javascript" src="{{asset('front-assets/vendors/smartwizard/dist/js/jquery.smartWizard.min.js')}}">
</script>
<script type="text/javascript" src="{{asset('front-assets/js/form-components/form-wizard.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}">
</script>
<script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>
<script>
    var referror_string = '';
        @foreach($refferors as $refferor)
            referror_string += '<option value="{{$refferor->id}}">{{$refferor->display_name}}</option>';
        @endforeach

        $('#isIndividual').click(function () {
            //var checker = $("#isIndividual").toggle(this.checked);
        });
    function previousnext(step)
    {
        var currentActive = parseInt(jQuery('a.nav-link.active').attr('data-counter'));

        if(parseInt(step) == 1){

            if(currentActive != 1)
            {
                var newStep = currentActive+1;

                jQuery('a.nav-link').removeClass('active');
                jQuery('a.nav-link-'+newStep).trigger('click');
            }
        }else if(parseInt(step) == 0)
        {
            if(currentActive != 0)
            {
                var newStep = currentActive-1;
                jQuery('a.nav-link').removeClass('active');
                jQuery('a.nav-link-'+newStep).trigger('click');
            }
        }
    }
        jQuery(document).ready(function () {

            var cur = $("#search_for");
            Searchtype(cur);

            $("#state").select2({

                placeholder: "Select State",
            });
            jQuery('#parent_broker').select2({

                placeholder: "Select Parent Broker",
            });
            jQuery('.referrors_sel_cl').select2({

                placeholder: "Select Broker",
            });
            jQuery('#bdm').select2({

                placeholder: "Select BDM",
            });
            @if(isset($broker) && $broker->state > 0)
                jQuery('#city option').attr('disabled','disabled');
                jQuery('#city option[value=""]').removeAttr('disabled')
                jQuery('#city option[data-state_id="{{$broker->state}}"]').removeAttr('disabled');
                $("#city").select2({

                    placeholder: "Select City",
                });
               @else
               $("#city").select2({

                   placeholder: "Select City",
               });
            @endif


            jQuery('body').on('change','#state',function(){
                var currentState = jQuery(this).val();
                if(currentState  != '')
                {
                    jQuery('#city option').attr('disabled','disabled');
                    jQuery('#city option[value=""]').removeAttr('disabled')
                    jQuery('#city option[data-state_id="'+currentState+'"]').removeAttr('disabled');
                    jQuery('#city').val('');
                    jQuery('#city').select2({

                        placeholder: "Select City",
                    })
                }else{
                    jQuery('#city option').attr('disabled','disabled');
                    jQuery('#city').select2({

                        placeholder: "Select City",
                    })
                }
            })
        });


        var task_counter = {{ isset($broker,$broker->referrors) && count($broker->referrors) > 0 ? count
        ($broker->referrors) : 2  }};



        $("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
            if (stepNumber == 1) {
                $('#finish-btn').show();
                $('#reset-btn').hide();
                $('#next-btn').hide();
            } else {
                $('#finish-btn').hide();
                $('#reset-btn').show();
                $('#next-btn').show();
            }
        });

        function addNewReferror(current) {
            var task_tr = '<tr id="task_rw_'+task_counter+'"> <td><select name="referrors['+task_counter+'][referror]" ' +
                'id="referrors_'+task_counter+'_referror" class="form-control' +
                'referrors_sel_cl" > <option ' +
                'value=""></option>'+referror_string+'</select></td><td><input ' +
                'type="text" name="referrors['+task_counter+'][upfront]" class=" form-control number-input" ' +
                'data-min="0" data-max="100" id="referrors_'+task_counter+'_upfront" placeholder="Upfront" ' +
                'maxlength="255"/></td><td><input type="text" name="referrors['+task_counter+'][trail]" class=" form-control number-input" id="referrors_'+task_counter+'_trail" placeholder="Trail" maxlength="255"/></td><td><input type="text" name="referrors['+task_counter+'][comm_per_deal]" class=" form-control number-input" data-min="0" data-max="100" id="referrors_'+task_counter+'_comm_per_deal" placeholder="Comm per deal"/></td><td> <button class="btn btn-danger" onclick="return removeReferror(this)" data-id="'+task_counter+'"><i class="fa fa-trash"></i></button> </td></tr>';
            jQuery('#task_tbody').append(task_tr);
            jQuery('#referrors_'+task_counter+'_referror').select2({

                placeholder: "Select Referrer",
            });
            task_counter++;

        }

        function removeReferror(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#task_rw_' + row_id).remove()
            }
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
                        var url = "/" + data.id;
                        setTimeout(function(){
                            //window.location="{{route('admin.contact.list')}}"
                            window.location = "{{url('admin/brokers/view')}}"+url;
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

        function Searchtype(cur) {
            var option = $('option:selected', cur).attr('data-value');

            if(option == 'broker_staff') {
                $("#parent").show();
                $("#isParent").val(1);
            }
            else {
                $("#isParent").val(0);
                $("#parent").hide();
            }
        }
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script loaded.');

            var brokerTypeDropdown = document.getElementById('individual');
            //var industryDropdown = document.getElementById('client_industry');
            var companyFieldsInputs = document.querySelectorAll('.company-fields input');

            function handleClientTypeChange() {
                var isIndividual = brokerTypeDropdown.value.toLowerCase() === 'individual';

                companyFieldsInputs.forEach(input => input.disabled = isIndividual);
                industryDropdown.disabled = isIndividual;

                console.log('Client Type:', brokerTypeDropdown.value);
                console.log('Input fields and Industry dropdown disabled:', isIndividual);
            }

            if (brokerTypeDropdown && companyFieldsInputs.length > 0) {
                console.log('Elements found. Setting up event listener.');
                brokerTypeDropdown.addEventListener('change', handleClientTypeChange);

                // Call the function on page load
                handleClientTypeChange();
            } else {
                console.error(
                    'Error: Client Type dropdown, Industry dropdown, or Company fields inputs not found.');
            }
        });
    </script>
@if(Session::has('message'))
<script>
    successMessage("{{ Session::get('message') }}")
</script>
@endif
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush
