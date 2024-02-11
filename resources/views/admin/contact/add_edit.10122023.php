@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($contact) && !empty($contact))
        Edit Client::{{$contact->preferred_name}}
    @else
        Add Client
    @endif
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                @if(isset($contact) && !empty($contact))
                    Edit Contact::{{$contact->preferred_name}}
                @else
                    Add
                    @if (Route::current()->getName() == 'referrer_add')
                        Referrer
                    @else
                        Client
                    @endif

                @endif
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex">
                        <svg class="svg-icon"
                             xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none"/>
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/>
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/>
                        </svg>
                        <span
                            class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item " aria-current="page">
                    <a href="{{route('admin.contact.list') }}">Clients</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> @if(isset($contact) && !empty($contact))
                        Edit Client::{{$contact->preferred_name}}
                    @else
                        Add
                        @if (Route::current()->getName() == 'referrer_add')
                            Referrer
                        @else
                            Client
                        @endif
                    @endif
                </li>
            </ol>
        </div>
    </div>
    <!--End Page header-->

@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form method="post"
                          action="{{isset($contact)?route('admin.contact.update', encrypt($contact->id)):route('admin.contact.post')}}"
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
                            <div class="tab-menu-heading">
                                <div class="tabs-menu ">
                                    <!-- Tabs -->

                                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav panel-tabs">
                                        <li class="nav-item">
                                            <a role="tab" class="nav-link nav-link-0 active" id="tab-0"
                                               data-toggle="tab"
                                               href="#step-0" data-counter="0">
                                                <span>Client Info</span>
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a role="tab" class="nav-link  nav-link-1" id="tab-1" data-toggle="tab"
                                               href="#step-1"
                                               data-counter="1">
                                                <span>Referred To</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a role="tab" class="nav-link  nav-link-2" id="tab-2" data-toggle="tab"
                                               href="#step-2"
                                               data-counter="2">
                                                <span>Relationship</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a role="tab" class="nav-link  nav-link-3" id="tab-3" data-toggle="tab"
                                               href="#step-3"
                                               data-counter="3">
                                                <span>Source Of Client</span>
                                            </a>

                                        </li> --}}
                                       <!-- <li>
                                            <a role="tab" class="nav-link  nav-link-4" id="tab-4" data-toggle="tab"
                                               href="#step-4"
                                               data-counter="4">
                                                <span>Tasks</span>
                                            </a>

                                        </li>-->
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div id="step-0" class="tab-pane tabs-animation fade show active" role="tabpanel">
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Basic Information
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-12" style="display:none;">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Type</label>
                                                            <select name="search_for" id="search_for"
                                                                    class="form-control"
                                                                    required readonly="readonly" style="display:none;">
                                                                <option selected disabled>Choose Type</option>
                                                                <option value="1" @if((isset($contact) && $contact->
                                                                search_for == "1") || Route::current()->getName() !=
                                                                'referrer_add')
                                                                selected @endif>
                                                                    Client
                                                                </option>
                                                                <option value="2" @if((isset($contact) && $contact->
                                                                search_for == "2") || Route::current()->getName() ==
                                                                'referrer_add')
                                                                selected @endif>
                                                                    Referror
                                                                </option>
                                                            </select>
                                                            @if($errors->has('search_for'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('search_for')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="position-relative form-group">
                                                            <label
                                                                class="form-label font-weight-bold">Client Type</label>
                                                            <select name="client_type" id="client_type"
                                                                    class="form-control">
                                                                <option value="">Select Type</option>
                                                                <option value="Individual">Individual</option>
                                                                <option value="">Company / Business</option>
                                                            </select>
                                                            @if($errors->has('client_type'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('client_type')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row company-fields">
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Company
                                                                Name</label>
                                                            <input name="entity_name" maxlength="255"
                                                                   value="{{isset($contact)?$contact->entity_name:""}}"
                                                                   placeholder="Company Name" type="text"
                                                                   class="form-control">
                                                            @if($errors->has('entity_name'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('entity_name')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label
                                                                class="form-label font-weight-bold">Trading/Business Name</label>
                                                            <input name="trading"
                                                                   value="{{isset($contact)?$contact->trading:""}}"
                                                                   placeholder="Trading/Business Name" type="text"
                                                                   maxlength="255"
                                                                   class="form-control">
                                                            @if($errors->has('trading'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('trading')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Trust
                                                                Name</label>
                                                            <input name="trust_name"
                                                                   value="{{isset($contact)?$contact->trust_name:""}}"
                                                                   placeholder="Trust Name" maxlength="255" type="text"
                                                                   class="form-control">
                                                            @if($errors->has('trust_name'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('trust_name')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Industry</label>
                                                            <select name="client_industry" id="client_industry"
                                                                    class="form-control">
                                                                <option value="" selected>Choose One</option>
                                                                @foreach($industries as $industry)
                                                                    <option value="{{$industry->id}}" {{(isset($contact) &&
                                                                $contact->client_industry == $industry->id ? 'selected="selected"' : '')
                                                                }}>{{$industry->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            @if($errors->has('client_industry'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('client_industry')}}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Principle
                                                                Contact</label>
                                                            <input name="principle_contact" maxlength="15"
                                                                   value="{{isset($contact)?$contact->principle_contact:""}}"
                                                                   placeholder="Principle Contact" type="text"
                                                                   class="form-control input_int_number">
                                                            @if($errors->has('principle_contact'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('principle_contact')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="position-relative form-group">
                                                            <label
                                                                class="form-label font-weight-bold">Salutation</label>
                                                            <select name="role_title" id="role_title"
                                                                    class="form-control">
                                                                <option value="dr" @if(isset($contact) && $contact->
                                                                role_title == "dr") selected @endif>
                                                                    Dr
                                                                </option>
                                                                <option value="mr" @if(isset($contact) && $contact->
                                                                role_title == "mr") selected @endif>
                                                                    Mr
                                                                </option>
                                                                <option value="miss" @if(isset($contact) && $contact->
                                                                role_title == "miss") selected @endif>
                                                                    Miss
                                                                </option>
                                                                <option value="mrs" @if(isset($contact) && $contact->
                                                                role_title == "mrs") selected @endif>
                                                                    Mrs
                                                                </option>
                                                                <option value="ms" @if(isset($contact) && $contact->
                                                                role_title == "ms") selected @endif>
                                                                    Ms
                                                                </option>
                                                                <option value="prof" @if(isset($contact) && $contact->
                                                                role_title == "prof") selected @endif>
                                                                    Prof.
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Surname</label>
                                                            <input name="surname" maxlength="150" value="{{isset($contact)
                                                        ?$contact->surname:""}}" placeholder="Surname" type="text"
                                                                   class="form-control">
                                                            @if($errors->has('surname'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('surname')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Given
                                                                Name</label>
                                                            <input name="preferred_name" maxlength="255"
                                                                   value="{{isset($contact)?$contact->preferred_name:""}}"
                                                                   placeholder="Given Name" type="text"
                                                                   class="form-control">
                                                            @if($errors->has('preferred_name'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('preferred_name')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Middle
                                                                Name</label>
                                                            <input name="middle_name" maxlength="255"
                                                                   value="{{isset($contact)?$contact->middle_name:""}}"
                                                                   placeholder="Middle Name" type="text"
                                                                   class="form-control">
                                                            @if($errors->has('middle_name'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('middle_name')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">DOB</label>
                                                            <input name="dob"
                                                                   value="{{isset($contact)?$contact->dob:""}}"
                                                                   type="text" placeholder="dd/mm/yyyy"
                                                                   data-toggle="datepicker" class="form-control">
                                                            @if($errors->has('dob'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('dob')}}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>


                                                </div>


                                            </div>
                                        </div>

                                        <div class="mb-2 card">
                                            <div class="card-header">
                                                Contact Details
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Work
                                                                Phone</label>
                                                            <input name="work_phone"
                                                                   value="{{isset($contact)?$contact->work_phone:""}}"
                                                                   placeholder="Work Phone" type="text"
                                                                   class="form-control input_int_number" maxlength="15">
                                                            @if($errors->has('work_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('work_phone')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Home
                                                                Phone</label>
                                                            <input name="home_phone"
                                                                   value="{{isset($contact)?$contact->home_phone:""}}"
                                                                   placeholder="Home Phone" type="text"
                                                                   class="form-control input_int_number" maxlength="15">
                                                            @if($errors->has('home_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('home_phone')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Mobile
                                                                Phone</label>
                                                            <input name="mobile_phone"
                                                                   value="{{isset($contact)?$contact->mobile_phone:""}}"
                                                                   placeholder="Mobile Phone" type="text"
                                                                   class="form-control input_int_number" maxlength="15">
                                                            @if($errors->has('mobile_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{$errors->first('mobile_phone')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Email</label>
                                                            <input name="email" placeholder="Email"
                                                                   value="{{isset($contact)?$contact->email:""}}"
                                                                   type="email"
                                                                   class="form-control" maxlength="255">
                                                            @if($errors->has('email'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('email')}}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Web</label>
                                                            <input name="web" placeholder="Web"
                                                                   value="{{isset($contact)?$contact->web:''}}"
                                                                   type="text"
                                                                   class="form-control" maxlength="255">
                                                            @if($errors->has('web'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('web')}}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="mb-2 card">
                                            <div class="card-header">
                                                Address
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Unit/Level/Street</label>
                                                            <input type="text" placeholder="Unit/Level/Street"
                                                                   name="street_number" id="street_number"
                                                                   value="{{isset($contact)?$contact?->withAddress?->unit:''}}"
                                                                   class="form-control"
                                                                   maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                name</label>
                                                            <input type="text" placeholder="Street name"
                                                                   name="street_name" id="street_name"
                                                                   value="{{isset($contact)?$contact?->withAddress?->street_name:''}}"
                                                                   class="form-control"
                                                                   maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                type</label>
                                                            <input type="text" placeholder="Street type"
                                                                   name="street_type" id="street_type"
                                                                   value="{{isset($contact)?$contact?->withAddress?->street_type:''}}"
                                                                   class="form-control"
                                                                   maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">City</label>
                                                            <input type="text" placeholder="City" name="suburb"
                                                                   id="suburb" class="form-control"
                                                                   value="{{isset($contact)?$contact?->withAddress?->city:''}}"
                                                                   maxlength="250"/>
                                                        </div>

                                                    </div>
                                                
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">State</label>
                                                            <select name="state" id="state"
                                                                    class="multiselect-dropdown ">
                                                                <option value="">Select State</option>
                                                                @if(count($states)>0)
                                                                    @foreach($states as $state)
                                                                        <option value="{{$state->id}}" 
                                                                            {{isset($contact) && $contact?->withAddress?->state == $state->id ? 'selected="selected"' : ''}}>
                                                                            {{$state->name}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if($errors->has('state'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('state')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Postal
                                                                Code</label>
                                                            <input id="postal_code" name="postal_code"
                                                                   value="{{isset($contact)?$contact?->withAddress?->postal_code:''}}"
                                                                   placeholder="Postal Code"
                                                                   type="text" class="form-control alpha-num"
                                                                   maxlength="10">
                                                            @if($errors->has('postal_code'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('postal_code')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                  
                                        <div class="mb-2 card">
                                            <div class="card-header">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            Postal Address
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="custom-control-inline">
                                                                <input type="checkbox" onchange="return checkasresidentials(this)" value="1" name="same_as_residential"
                                                                    id="same_as_residential"
                                                                    class="custom-control-input" {{isset($contact) &&
                                                                            $contact->same_as_residential == 1 ?
                                                                            'checked="checked"':''}} />
                                                                <label class="custom-control-label"
                                                                       for="same_as_residential">Same as Address</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Unit/Level/Street</label>
                                                            <input type="text" placeholder="Unit/Level/Street"
                                                                   name="postal_street_number"
                                                                   id="postal_street_number"
                                                                   value="{{isset($contact)?$contact->postal_street_number:''}}"
                                                                   class="form-control" maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                name</label>
                                                            <input type="text" placeholder="Street name"
                                                                   name="postal_street_name"
                                                                   value="{{isset($contact)?$contact->postal_street_name:''}}"
                                                                   id="postal_street_name"
                                                                   class="form-control" maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                type</label>
                                                            <input type="text" placeholder="Street type"
                                                                   name="postal_street_type"
                                                                   value="{{isset($contact)?$contact->postal_street_type:''}}"
                                                                   id="postal_street_type"
                                                                   class="form-control" maxlength="250"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">City</label>
                                                            <input type="text" placeholder="City" name="postal_suburb"
                                                                   value="{{isset($contact)?$contact->postal_suburb:''}}"
                                                                   id="postal_suburb"
                                                                   class="form-control" maxlength="250"/>
                                                        </div>
                                                    </div>
                                       
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">State</label>
                                                            <select name="mail_state" id="mail_state"
                                                                    class="multiselect-dropdown ">
                                                                <option value="">Select State</option>
                                                                @if(count($states)>0)
                                                                    @foreach($states as $state)
                                                                        <option value="{{$state->id}}" {{isset($contact) && $contact->mail_state ==
                                                                        $state->id ? 'selected="selected"' : ''}}>
                                                                            {{$state->name}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if($errors->has('mail_state'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('mail_state')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Postal
                                                                Code</label>
                                                            <input id="mail_postal_code" name="mail_postal_code"
                                                                   value="{{isset($contact)?$contact->mail_postal_code:""}}"
                                                                   placeholder="Postal Code"
                                                                   type="text" class="form-control alpha-num"
                                                                   maxlength="10">
                                                            @if($errors->has('mail_postal_code'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('mail_postal_code')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Other
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Broker</label>
                                                            <select name="abp" id="abp" class="form-control">
                                                                <option value="" selected>Choose One</option>
                                                                @foreach($abps as $abp)
                                                                    <option value="{{$abp->id}}" {{(isset($contact) &&
                                                                $contact->abp == $abp->id ? 'selected="selected"' : '')
                                                                }}>{{$abp->trading}}</option>
                                                                @endforeach
                                                            </select>
                                                            @if($errors->has('abp'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('abp')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="position-relative form-group">
                                                    <textarea name="note" id="note" cols="30" rows="5" placeholder="Notes"
                                                      class="form-control w-100">{{isset($contact)?$contact->note:""}}</textarea>
                                                            @if($errors->has('note'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('note')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div id="step-1" class="tab-pane tabs-animation fade" role="tabpanel">
                                        <div id="accordion" class="accordion-wrapper mb-3">
                                            <div class="card">
                                                <div class="card-body" id="client_referrals_main">
                                                    
                                                    @if(isset($client_referrals) && count($client_referrals) > 0)
                                                        <?php //$i = 1; ?>
                                                        @foreach($client_referrals as $rkey => $client_referral)
                                                            <div class="form-row" id="referred_body_{{$i}}">
                                                                <div class="col-md-5 col-sm-12">
                                                                    <div class="position-relative form-group">
                                                                        <label class="form-label font-weight-bold">Referred To</label>
                                                                        <input name="client_referrals[{{ $rkey }}][referred_to]"
                                                                               value="{{$client_referral->referred_to}}"
                                                                               placeholder="1 Referred To" type="text"
                                                                               class="form-control alpha-num" maxlength="255">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-12">
                                                                    <input type="hidden"
                                                                           name="client_referrals[{{$rkey}}][old_id]"
                                                                           value="{{$client_referral->id}}"/>
                                                                    <div class="position-relative form-group">
                                                                        <label
                                                                            class="form-label font-weight-bold">Service</label>
                                                                        <select name="client_referrals[{{ $rkey }}][service_id]" id="service_id_{{ $rkey }}"
                                                                                class="form-control">
                                                                            <option value="" selected disabled>Choose One
                                                                            </option>
                                                                            @foreach($services as $service)
                                                                                <option value="{{$service->id}}" @if($client_referral->
                                                        service_id
                                                        == $service->id) selected @endif>
                                                                                    {{$service->name}}
                                                                                </option>
                                                                            @endforeach

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 col-sm-12">
                                                                    <div class="position-relative form-group">
                                                                        <label class="form-label font-weight-bold">Date</label>
                                                                        <input name="client_referrals[{{ $rkey }}][date]" id="date_{{$rkey}}"
                                                                               value="{{isset($client_referral->date)?date('m/d/Y',strtotime($client_referral->date)):""}}"
                                                                               type="text"
                                                                               placeholder="dd/mm/yyyy" data-toggle="datepicker"
                                                                               class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-sm-12">
                                                                    <div class="position-relative form-group">
                                                                        <label class="form-label font-weight-bold">Notes</label>
                                                                        <textarea name="client_referrals[{{ $rkey }}][notes]" id="notes_{{ $rkey }}" cols="30" rows="3"
                                                                                  placeholder="Note"
                                                                                  class="form-control w-100">{{isset($client_referral)?$client_referral->notes:""}}</textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removeReferral(this)" data-id="{{$i}}"><i class="fa fa-trash"></i>
                                                                </div>
                                                            </div>
                                                            <?php $i++; ?>
                                                        @endforeach
                                                    @else
                                                        <div class="form-row" id="referred_body_0">
                                                            <div class="col-md-5 col-sm-12">
                                                                <div class="position-relative form-group">
                                                                    <label class="form-label font-weight-bold">Referred To</label>
                                                                    <input name="client_referrals[0][referred_to]"
                                                                           value=""
                                                                           placeholder="Referred To" type="text"
                                                                           class="form-control alpha-num" maxlength="255">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                                <div class="position-relative form-group">
                                                                    <label
                                                                        class="form-label font-weight-bold">Service</label>
                                                                    <select name="client_referrals[0][service_id]" id="service_id_0"
                                                                            class="form-control">
                                                                        <option value="" selected disabled>Choose One
                                                                        </option>
                                                                        @foreach($services as $service)
                                                                            <option value="{{$service->id}}">
                                                                                {{$service->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 col-sm-12">
                                                                <div class="position-relative form-group">
                                                                    <label class="form-label font-weight-bold">Date</label>
                                                                    <input name="client_referrals[0][date]" id="date_0"
                                                                           type="text" placeholder="dd/mm/yyyy"
                                                                           data-toggle="datepicker"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="position-relative form-group">
                                                                    <label class="form-label font-weight-bold">Notes</label>
                                                                    <textarea name="client_referrals[0][notes]" id="notes_0" cols="30" rows="3"
                                                                              placeholder="Note"
                                                                              class="form-control w-100"></textarea>
                                                                </div>
                                                            </div>

                                                        <!--<div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removeReferral(this)" data-id="1"><i class="fa fa-trash"></i>
                                                        </div>-->
                                                        </div>
                                                    @endif

                                                </div>
                                                <div class="col-sm-12 mb-5">
                                                    <div class="float-right">
                                                        <button type="button" class="btn btn-primary"
                                                                id="add_new_relation"
                                                                onclick="addNewReferral(this)">Add More
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-2" class="tab-pane tabs-animation fade " role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="relationship_container">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-4 text-center">
                                                                <label class="form-label font-weight-bold">Linked
                                                                    to</label>
                                                            </div>
                                                            <div class="col-sm-4 text-center">
                                                                <label
                                                                    class="form-label font-weight-bold">Relation</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="relation_conbody" id="relation_conbody">
                                                        @if(isset($contact,$contact->relations) && count($contact->relations) >0 )

                                                            @foreach($contact->relations as $rkey => $crelation )

                                                                <div class="col-sm-12 relation_child"
                                                                     id="rel_{{$rkey}}">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <input type="hidden"
                                                                                       name="relationship[{{$rkey}}][old_id]"
                                                                                       value="{{$crelation->id}}"/>
                                                                                <select
                                                                                    name="relationship[{{$rkey}}][linked_to]"
                                                                                    id="relationship_{{$rkey}}_linked_to"
                                                                                    class="form-control linked_to_selector">
                                                                                    <option value="">Select Client
                                                                                    </option>
                                                                                    @foreach($clients as $client)
                                                                                        <option value="{{$client->id}}" {{(isset
                                                                                ($crelation) && $crelation->relation_with == $client->id ?
                                                        'selected="selected"' :'')}}>{{$client->surname.' '.$client->first_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <select
                                                                                    name="relationship[{{$rkey}}][relation]"
                                                                                    id="relationship_{{$rkey}}_relation"
                                                                                    class="form-control">
                                                                                    <option value="">Select Relation
                                                                                    </option>
                                                                                    @foreach($relations as $relation)
                                                                                        <option
                                                                                            value="{{$relation->id}}" {{ (isset
                                                                                ($crelation) && $crelation->relation ==
                                                                                 $relation->id ?
                                                        'selected="selected"' :'') }}>{{$relation->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <button type="button" class="btn btn-danger"
                                                                                    onclick="removerelation
                                                       (this)" data-id="{{$rkey}}"><i class="fa fa-trash"></i></button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-sm-12 relation_child" id="rel_0">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[0][linked_to]"
                                                                                    id="relationship_0_linked_to"
                                                                                    class="form-control linked_to_selector relationship_0_linked_to">
                                                                                <option value="">Select Client</option>
                                                                                @foreach($clients as $client)
                                                                                    <option
                                                                                        value="{{$client->id}}">{{$client->surname.' '.$client->first_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[0][relation]"
                                                                                    id="relationship_0_relation"
                                                                                    class="form-control relationship_0_relation">
                                                                                <option value="">Select Relation
                                                                                </option>
                                                                                @foreach($relations as $relation)
                                                                                    <option
                                                                                        value="{{$relation->id}}">{{$relation->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-danger"
                                                                                onclick="removerelation
                                                       (this)" data-id="0"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                         <!--    <div class="col-sm-12 relation_child" id="rel_1">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[1][linked_to]"
                                                                                    id="relationship_1_linked_to"
                                                                                    class="form-control linked_to_selector">
                                                                                <option value="">Select Client</option>
                                                                                @foreach($clients as $client)
                                                                                    <option
                                                                                        value="{{$client->id}}">{{$client->display_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[1][relation]"
                                                                                    id="relationship_1_relation"
                                                                                    class="form-control">
                                                                                <option value="">Select Relation
                                                                                </option>
                                                                                @foreach($relations as $relation)
                                                                                    <option
                                                                                        value="{{$relation->id}}">{{$relation->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-danger"
                                                                                onclick="removerelation
                                                       (this)" data-id="1"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        @endif


                                                    </div>

                                                </div>
                                                <div class="col-sm-12 mb-5">
                                                    <div class="float-right">
                                                        <button type="button" class="btn btn-primary"
                                                                id="add_new_relation"
                                                                onclick="addNewRelation(this)">Add new Relation
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-3" class="tab-pane tabs-animation fade " role="tabpanel">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Referred By</label>
                                                        <select name="referrer_type"
                                                                id="referrer_type"
                                                                class="form-control linked_to_selector">
                                                            <option value="">Select Client</option>
                                                            <option {{  isset($contact) &&$contact->referrer_type==1?'selected':'' }} value="1">Referrer</option>
                                                            <option {{  isset($contact) &&$contact->referrer_type==2?'selected':'' }} value="2">Mutual Rewards Client</option>
                                                            <option {{  isset($contact) &&$contact->referrer_type==3?'selected':'' }} value="3">Staff Referral </option>
                                                            <option {{  isset($contact) &&$contact->referrer_type==4?'selected':'' }} value="4">Social Media</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                 <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type==1?'':'hidden' }}" id="other_referrer_div">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Referrer</label>
                                                        <!--<input name="other_referrer" id="other_referrer" class="form-control" value="{{ isset($contact)?$contact->other_referrer:'' }}">-->
                                                        <select name="other_referrer" id="other_referrer"
                                                                class="form-control linked_to_selector">
                                                            <option value="">Select Referrer</option>
                                                            @foreach($referrers as $referrer)
                                                                <option value="{{$referrer->id}}" {{(isset($contact) &&
                                                                    $contact->other_referrer !='' &&
                                                                    $contact->other_referrer == $referrer->id )
                                                                    ? 'selected="selected"' : ''}}>
                                                                    {{$referrer->first_name.' '.$referrer->surname}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type==2?'':'hidden' }}" id="from_clients">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Existing Clients</label>
                                         
                                                        <select name="client_id" id="client_id"
                                                                class="form-control linked_to_selector">
        
                                                            <option value="">Select Client</option>
                                                                @foreach($clients as $client)
                                                                    <option value="{{$client->id}}" {{(isset($contact) &&
                                                                        $contact->referrer_id !='' &&
                                                                        $contact->referrer_id == $client->id )
                                                                        ? 'selected="selected"' : ''}}>
                                                                        {{$client->surname.' '.$client->first_name}}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type==3?'':'hidden' }}" id="from_referrer">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Staff Referral</label>
                                                   
                                                        <select name="referrer_id" id="referrer_id"
                                                                class="form-control linked_to_selector">
                                                            <option value="">Select Staff Referral</option>
                                                            @foreach($processors as $processor)
                                                                <option value="{{$processor->id}}" {{(isset($contact) &&
                                                                    $contact->referrer_id !='' &&
                                                                    $contact->referrer_id == $processor->id )
                                                                    ? 'selected="selected"' : ''}}>
                                                                    {{$processor->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4 {{ isset($contact) && $contact->referrer_type==4?'':'hidden' }}" id="from_social_media">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Social Media</label>
                                                        <!--<input name="social_media_link" id="social_media_link" class="form-control" value="{{ isset($contact)?$contact->social_media_link:'' }}">-->
                                                         <select name="social_media_link" id="social_media_link"
                                                                class="form-control linked_to_selector">
                                                            <option value="">Select Social Media</option>
                                                            <option {{  isset($contact) &&$contact->social_media_link==1?'selected':'' }} value="1">Facebook</option>
                                                            <option {{  isset($contact) &&$contact->social_media_link==2?'selected':'' }} value="2">Twitter</option>
                                                            <option {{  isset($contact) &&$contact->social_media_link==3?'selected':'' }} value="3">Instagram </option>
                                                            <option {{  isset($contact) &&$contact->social_media_link==4?'selected':'' }} value="4">Youtube</option>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Referrer Relationship</label>
                                                        <select name="refferor_relation_to_client"
                                                                id="refferor_relation_to_client" class="form-control">
                                                            <option value="">Select Relation</option>
                                                            @foreach($relations as $relation)
                                                                <option {{ isset($contact)&&$contact->refferor_relation_to_client==$relation->id?'selected':'' }} value="{{$relation->id}}">{{$relation->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">Notes:</label>
                                                <textarea class="form-control" id="refferor_note" name="refferor_note"
                                                          rows="4"> {{ isset($contact) && $contact->refferor_note ?
                                                          $contact->refferor_note : ''
                                                          }}</textarea>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div id="step-4" class="tab-pane tabs-animation fade " role="tabpanel">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Followup Date</th>
                                                            <th>Processor</th>
                                                            <th>Details</th>
                                                            
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="task_tbody">
                                                        @if(isset($contact,$contact->tasks) && count($contact->tasks) > 0)
                                                            @foreach($contact->tasks as $tkey => $tval)
                                                                <tr id="task_rw_{{$tkey}}">
                                                                    <td><input type="hidden"
                                                                               name="tasks[{{$tkey}}][old_id]"
                                                                               value="{{$tval->id}}"/><input type="text"
                                                                               data-toggle="datepicker" placeholder="dd/mm/yyyy"
                                                                                                             name="tasks[{{$tkey}}][followup_date]"
                                                                                                             class="task_followup_date form-control"
                                                                                                             id="tasks_{{$tkey}}_follow_up_date"
                                                                                                             placeholder="Follow Up Date"
                                                                                                             value="{{$tval->followup_date}}"/>
                                                                                                             
                                                                                                            </td>
                                                                    <td>
                                                                        
                                                                    <!--<input type="text"
                                                                               name="tasks[{{$tkey}}][processor]"
                                                                               class="
                                                                                 form-control alpha-num" id="tasks_{{$tkey}}_processor"
                                                                               placeholder="Processor" maxlength="250"
                                                                               value="{{$tval->processor}}"/>-->

                                                                               <select name="tasks[{{$tkey}}][processor]" id="tasks_{{$tkey}}_processor" class="multiselect-dropdown form-control">
                                                                                    <option value="" >Select Processor</option> 
                                                                                    @if(count($processors)>0)
                                                                                        @foreach($processors as $processor)
                                                                                            <option value="{{$processor->id}}" {{isset
                                                                        ($tval) && $tval->processor ==
                                                                $processor->name ? 'selected="selected"' : ''}}>
                                                                                                {{$processor->name}}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            
                                                                            </td>
                                                                    <td><input type="text"
                                                                               name="tasks[{{$tkey}}][details]" class="
                                                            form-control" id="tasks_{{$tkey}}_details"
                                                                               placeholder="Detail" maxlength="255"
                                                                               value="{{$tval->details}}"/></td>
                                                                    <td>
                                                                        <button class="btn btn-danger"
                                                                                onclick="return removetask(this)"
                                                                                data-id="{{$tkey}}"><i
                                                                                class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr id="task_rw_0">
                                                                <td><input type="text"
                                                                               data-toggle="datepicker" placeholder="dd/mm/yyyy" name="tasks[0][followup_date]"
                                                                           class="task_followup_date form-control"
                                                                           id="tasks_0_follow_up_date"
                                                                           placeholder="Follow Up Date"/></td>
                                                                <td><!--<input type="text" name="tasks[0][processor]"
                                                                           class="
                                                             form-control alpha-num" id="tasks_0_processor"
                                                                           placeholder="Processor" maxlength="250"/>-->

                                                                           <select name="tasks[0][processor]" id="tasks_0_processor" class="multiselect-dropdown form-control">
                                                                                    <option value="" >Select Processor</option> 
                                                                                    @if(count($processors)>0)
                                                                                        @foreach($processors as $processor)
                                                                                            <option value="{{$processor->id}}" {{isset
                                                                        ($tval) && $tval->processor ==
                                                                $processor->name ? 'selected="selected"' : ''}}>
                                                                                                {{$processor->name}}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                </td>
                                                                <td><input type="text" name="tasks[0][details]" class="
                                                            form-control" id="tasks_0_details" placeholder="Detail"
                                                                           maxlength="255"/></td>
                                                                
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
                                                <button type="button" class="btn btn-primary" id="add_new_task"
                                                        onclick="addNewTask(this)">Add new Task
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="clearfix">
                            {{--<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset
                                                    </button>--}}
                            {{-- <button type="button"
                                    class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary"
                                    onclick="return previousnext(1)">
                                Next
                            </button> --}}
                            <button type="submit" id="finish-btn"
                                    class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-success">
                                Finish
                            </button>
                            {{-- <button type="button"
                                    class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary"
                                    onclick="return previousnext(0)">
                                Previous
                            </button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-section')
    <style>
        .card_header_sec {
            display: block;
            width: 100%;
            border-bottom: none;
            text-align: center;
            padding-top: 10px;
        }
    </style>
    <script type="text/javascript"
            src="{{asset('front-assets/vendors/smartwizard/dist/js/jquery.smartWizard.min.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/form-wizard.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>
    <script>
        var relationstring = '';
        @foreach($relations as $relation)
            relationstring += '<option value="{{$relation->id}}">{{$relation->name}}</option>';
        @endforeach

        var clientstring = '';
        @foreach($clients as $client)
            clientstring += '<option value="{{$client->id}}">{{$client->surname.' '.$client->first_name}}</option>';
        @endforeach

        var processorstring = '';
        @foreach($processors as $processor)
            processorstring += '<option value="{{$processor->id}}">{{$processor->name}}</option>';
        @endforeach



        $('#isIndividual').click(function () {
            //var checker = $("#isIndividual").toggle(this.checked);
        });

        // function previousnext(step) {
        //     var currentActive = parseInt(jQuery('a.nav-link.active').attr('data-counter'));

        //     if (parseInt(step) == 1) {

        //         if (currentActive != 4) {
        //             var newStep = currentActive + 1;

        //             jQuery('a.nav-link').removeClass('active');
        //             jQuery('a.nav-link-' + newStep).trigger('click');
        //         }
        //     } else if (parseInt(step) == 0) {
        //         if (currentActive != 0) {
        //             var newStep = currentActive - 1;
        //             jQuery('a.nav-link').removeClass('active');
        //             jQuery('a.nav-link-' + newStep).trigger('click');
        //         }
        //     }
        // }

        jQuery(document).ready(function () {

            jQuery('body').on('change', '#client_industry', function () {
                var cuid = jQuery(this).val();
                var cuname = jQuery('#client_industry option[value="' + cuid + '"]').text();
                console.log(cuname);
                if (cuname == 'Other') {
                    jQuery('#other_industry').removeAttr('readonly');
                } else {
                    jQuery('#other_industry').val('');
                    jQuery('#other_industry').attr('readonly', 'readonly');
                }
            })

            $("#state").select2({
                placeholder: "Select State",
            });
            $("#refferor_relation_to_client").select2({
                placeholder: "Select Option",
            });
            $("#abp").select2({
                placeholder: "Select Broker",
            });
            $("#client_industry").select2({
                placeholder: "Select Industry",
            });
            $("#mail_state").select2({
                placeholder: "Select State",
            });
            jQuery('.linked_to_selector').select2({
                placeholder: "Select Linked To",
            })
            @if(isset($contact) && $contact->state > 0)
            jQuery('#city option').attr('disabled', 'disabled');
            jQuery('#city option[value=""]').removeAttr('disabled')
            jQuery('#city option[data-state_id="{{$contact->state}}"]').removeAttr('disabled');
            $("#city").select2({
                placeholder: "Select City",
            });
            @else
            $("#city").select2({
                placeholder: "Select City",
            });
            @endif

            jQuery('body').on('keyup', '#street_number', function () {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_number').val(jQuery('#street_number').val())
                }
            })

            jQuery('body').on('keyup', '#street_name', function () {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_name').val(jQuery('#street_name').val())
                }
            });

            jQuery('body').on('keyup', '#street_type', function () {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_type').val(jQuery('#street_type').val())
                }
            });
            jQuery('body').on('keyup', '#suburb', function () {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_suburb').val(jQuery('#suburb').val())
                }
            });
            jQuery('body').on('keyup', '#postal_code', function () {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#mail_postal_code').val(jQuery('#postal_code').val())
                }
            });


            @if(isset($contact) && $contact->mail_state > 0)
            jQuery('#mail_city option').attr('disabled', 'disabled');
            jQuery('#mail_city option[value=""]').removeAttr('disabled')
            jQuery('#mail_city option[data-state_id="{{$contact->mail_state}}"]').removeAttr('disabled');
            $("#mail_city").select2({
                placeholder: "Select City",
            });
            @else
            $("#mail_city").select2({
                placeholder: "Select City",
            });
            @endif


            jQuery('body').on('change', '#state', function () {
                var currentState = jQuery(this).val();
                if (currentState != '') {
                    jQuery('#city option').attr('disabled', 'disabled');
                    jQuery('#city option[value=""]').removeAttr('disabled')
                    jQuery('#city option[data-state_id="' + currentState + '"]').removeAttr('disabled');
                    jQuery('#city').val('');
                    jQuery('#city').select2({
                        placeholder: "Select City",
                    })
                    if (jQuery('#same_as_residential').is(':checked')) {
                        jQuery('#mail_state option[value="' + currentState + '"]').attr('selected', 'selected');
                        jQuery('#mail_state').select2({
                            placeholder: "Select State",
                        })
                        jQuery('#mail_city option').attr('disabled', 'disabled');
                        jQuery('#mail_city option[value=""]').removeAttr('disabled')
                        jQuery('#mail_city option[data-state_id="' + currentState + '"]').removeAttr('disabled');
                        jQuery('#mail_city').val('');
                        jQuery('#mail_city').select2({
                            placeholder: "Select City",
                        })
                    }
                } else {
                    jQuery('#city option').attr('disabled', 'disabled');
                    jQuery('#city').select2({
                        placeholder: "Select City",
                    })
                    if (jQuery('#same_as_residential').is(':checked')) {
                        jQuery('#mail_state').val('')
                        jQuery('#mail_state').select2({
                            placeholder: "Select State",
                        })
                        jQuery('#mail_city option').attr('disabled', 'disabled');
                        jQuery('#mail_city').select2({
                            placeholder: "Select City",
                        })
                    }
                }
            })

            jQuery('body').on('change', '#mail_state', function () {
                var currentState = jQuery(this).val();
                if (currentState != '') {
                    jQuery('#mail_city option').attr('disabled', 'disabled');
                    jQuery('#mail_city option[value=""]').removeAttr('disabled')
                    jQuery('#mail_city option[data-state_id="' + currentState + '"]').removeAttr('disabled');
                    jQuery('#mail_city').val('');
                    jQuery('#mail_city').select2({
                        placeholder: "Select City",
                    })
                } else {
                    jQuery('#mail_city option').attr('disabled', 'disabled');
                    jQuery('#mail_city').select2({
                        placeholder: "Select City",
                    })
                }
            })

            jQuery('body').on('change', '#city', function () {
                var currentVal = jQuery(this).val();
                jQuery('#mail_city option[value="' + currentVal + '"]').attr('selected', 'selected');
                $("#mail_city").select2({
                    placeholder: "Select City",
                });
            });
        });

        var rel_counter = {{ (isset($contact,$contact->relations) && count($contact->relations) >0) ? count
        ($contact->relations) : 2  }};
        var task_counter = {{ isset($contact,$contact->tasks) && count($contact->tasks) > 0 ? count($contact->tasks)
        : 2  }};
        var client_referral = {{ isset($client_referrals) && count($client_referrals) > 0 ? count($client_referrals)+1 : 2  }};

        
        //alert(client_referral);
        function addNewRelation(current) {
            var htmlCon = '<div class="col-sm-12" id="rel_' + rel_counter + '"><div class="row"><div class="col-sm-4"><div ' +
                'class="form-group"><select  name="relationship[' + rel_counter + '][linked_to]" ' +
                'id="relationship_' + rel_counter + '_linked_to" class="form-control linked_to_selector"><option ' +
                'value="">Select ' +
                'Client</option>' + clientstring + '</select></div></div><div class="col-sm-4"><div class="form-group"><select ' +
                'name="relationship[' + rel_counter + '][relation]" id="relationship_' + rel_counter + '_relation" ' +
                'class="form-control relationship_'+rel_counter+'_relation"><option value="">Select Relation</option>' + relationstring + '</select></div></div><div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removerelation (this)" data-id="' + rel_counter + '"><i class="fa fa-trash"></i> ' +
                '</button></div></div></div>';
            jQuery('#relation_conbody').append(htmlCon);
            jQuery('#relationship_' + rel_counter + '_linked_to').select2({
                placeholder: "Select Linked To",
            })
            jQuery('#relationship_' + rel_counter + '_relation').select2({
                placeholder: "Select Option",
            })
            rel_counter++;
        }
        jQuery('.relationship_0_linked_to:last').select2({
            placeholder: "Select Option",
        })
        jQuery('.relationship_0_relation:last').select2({
            placeholder: "Select Option",
        })

        function removerelation(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#rel_' + row_id).remove()
            }
        }
        function addNewReferral(current) {
            //alert(client_referral);
            var htmlCon='';
            htmlCon+='<div class="form-row" id="referred_body_'+ client_referral +'">';
            htmlCon+='<div class="col-md-5 col-sm-12">';
            htmlCon+='<div class="position-relative form-group">';
            htmlCon+='<label class="form-label font-weight-bold">Referred To</label>';
            htmlCon+='<input name="client_referrals['+ client_referral +'][referred_to]"'+
                               'placeholder="Referred To" type="text"'+
                               'class="form-control alpha-num" maxlength="255">';
            htmlCon+='</div>';
            htmlCon+='</div>';
            htmlCon+='<div class="col-md-4 col-sm-12">';
            htmlCon+='<div class="position-relative form-group">';
            htmlCon+='<label class="form-label font-weight-bold">Service</label>';
            htmlCon+='<select name="client_referrals['+ client_referral +'][service_id]" id="service_id_'+ client_referral +'" class="form-control">';
            htmlCon+='<option value="" selected disabled>Choose One';
            htmlCon+='</option>';
                            @foreach($services as $service)
                                htmlCon+='<option value="{{$service->id}}">';
            htmlCon+='{{$service->name}}';
                                htmlCon+='</option>';
                            @endforeach
                                htmlCon+='</select>';
            htmlCon+='</div>';
            htmlCon+='</div>';
            htmlCon+='<div class="col-md-3 col-sm-12">';
            htmlCon+='<div class="position-relative form-group">';
            htmlCon+='<label class="form-label font-weight-bold">Date</label>';
            htmlCon+='<input name="client_referrals['+ client_referral +'][date]" id="date_'+ client_referral +'"'+
                               'type="text" placeholder="dd/mm/yyyy"'+
                               'data-toggle="datepicker"'+
                               'class="form-control">';
            htmlCon+='</div>';
            htmlCon+='</div>';
            htmlCon+='<div class="col-md-12 col-sm-12">';
            htmlCon+='<div class="position-relative form-group">';
            htmlCon+='<label class="form-label font-weight-bold">Notes</label>';
            htmlCon+='<textarea name="client_referrals['+ client_referral +'][notes]" id="notes_'+ client_referral +'" cols="30" rows="3"'+
                                  'placeholder="Note"'+
                                  'class="form-control w-100"></textarea>';
            htmlCon+='</div>';
            htmlCon+='</div>';
            htmlCon+='<div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removeReferral (this)" data-id="' + client_referral + '"><i class="fa fa-trash"></i>';
            htmlCon+='</div>'+ client_referral;
            jQuery('#client_referrals_main').append(htmlCon);
            $('[data-toggle="datepicker"]').datepicker({
                format: 'dd/mm/yyyy',
                autoHide: true
            });
            client_referral++;
        }

        function removeReferral(current) {
            var row_id = jQuery(current).attr('data-id');
            //alert(row_id);
            if (typeof row_id != "undefined") {
                jQuery('#referred_body_' + row_id).remove()
                client_referral--;
            }
        }
        jQuery("#referrer_type").on("change",function (){
           if(jQuery(this).val()==1){
               jQuery("#referrer_id").val("");
               jQuery("#social_media_link").val("");
               if(jQuery("#from_referrer").hasClass("hidden")==false){
                   jQuery("#from_referrer").addClass("hidden");
               }
               if(jQuery("#from_clients").hasClass("hidden")==false){
                   jQuery("#from_clients").addClass("hidden");
               }
               if(jQuery("#from_social_media").hasClass("hidden")==false){
                   jQuery("#from_social_media").addClass("hidden");
               }
               jQuery("#other_referrer_div").removeClass("hidden")
               
           } else if(jQuery(this).val()==2){
               jQuery("#referrer_id").val("");
               jQuery("#other_referrer").val("");
               jQuery("#from_social_media").val("");
                if(jQuery("#from_referrer").hasClass("hidden")==false){
                    jQuery("#from_referrer").addClass("hidden");
                }
                if(jQuery("#from_social_media").hasClass("hidden")==false){
                    jQuery("#from_social_media").addClass("hidden");
                }
                if(jQuery("#other_referrer_div").hasClass("hidden")==false){
                    jQuery("#other_referrer_div").addClass("hidden");
                }
               jQuery("#from_clients").removeClass("hidden")
           } else if(jQuery(this).val()==4){
               jQuery("#referrer_id").val("");
               jQuery("#other_referrer").val("");
               jQuery("#from_social_media").val("");
                if(jQuery("#from_referrer").hasClass("hidden")==false){
                    jQuery("#from_referrer").addClass("hidden");
                }
                if(jQuery("#from_clients").hasClass("hidden")==false){
                    jQuery("#from_clients").addClass("hidden");
                }
                if(jQuery("#other_referrer_div").hasClass("hidden")==false){
                    jQuery("#other_referrer_div").addClass("hidden");
                }
               jQuery("#from_social_media").removeClass("hidden")
            }else{
               jQuery("#social_media_link").val("");
               jQuery("#other_referrer").val("");
               if(jQuery("#from_social_media").hasClass("hidden")==false){
                   jQuery("#from_social_media").addClass("hidden");
               }
               if(jQuery("#other_referrer_div").hasClass("hidden")==false){
                   jQuery("#other_referrer_div").addClass("hidden");
               }
               if(jQuery("#from_clients").hasClass("hidden")==false){
                   jQuery("#from_clients").addClass("hidden");
               }
               
               jQuery("#from_referrer").removeClass("hidden")
           }
        });
        $("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
            if (stepNumber == 2) {
                $('#finish-btn').show();
                $('#reset-btn').hide();
                $('#next-btn').hide();
            } else {
                $('#finish-btn').hide();
                $('#reset-btn').show();
                $('#next-btn').show();
            }
        });

        function addNewTask(current) {
            var task_tr = '<tr id="task_rw_' + task_counter + '"><td><input type="text"  autocomplete="off" placeholder="dd/mm/yyyy"  data-toggle="datepicker" name="tasks[' + task_counter +
                '][followup_date]" class="task_followup_date form-control" id="tasks_' + task_counter + '_follow_up_date" placeholder="Follow Up Date" /></td><td><select name="tasks[' + task_counter + '][processor]" id="tasks_' + task_counter + '_processor" class="multiselect-dropdown form-control"><option value="" >Select Processor</option>' + processorstring + '</select></td><td><input type="text" name="tasks[' + task_counter + '][details]" class=" form-control"id="tasks_' + task_counter + '_details"  placeholder="Detail" /></td><td><button class="btn btn-danger" onclick="return removetask(this)" data-id="' + task_counter + '"><i class="fa fa-trash"></i></button></td></tr>';
            jQuery('#task_tbody').append(task_tr);
            task_counter++;
            $('[data-toggle="datepicker"]').datepicker({
                format: 'dd/mm/yyyy',
                autoHide: true
            });
        }

        function removetask(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#task_rw_' + row_id).remove()
            }
        }

        function saveForm(current) {
           // alert(jQuery(current).attr('action'));
            showLoader();
            
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                data: $("form").serialize(),
                success: function (data) {
                    //console.log('gg');
                    //console.log(data);
                    //alert('jjj');

                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        //console.log(data.error);
                        hideLoader();

                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        var url = "/" + data.id;
                        setTimeout(function () {
                            window.location = "{{url('admin/contact/view')}}"+url;
                        }, 3000);
                        hideLoader();

                    }
                }, error: function (jqXHR, textStatus, errorThrown) {

                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        //console.log(respo.message);
                        //errorMessage(respo.message);
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

        function checkasresidentials(current) {
            //alert('gg');
            if (jQuery(current).is(':checked')) {
                jQuery('#postal_street_number').val(jQuery('#street_number').val());
                jQuery('#postal_street_name').val(jQuery('#street_name').val());
                jQuery('#postal_street_type').val(jQuery('#street_type').val());
                jQuery('#postal_suburb').val(jQuery('#suburb').val());
                var currentState = jQuery('#state').val();
                jQuery('#mail_state').val(currentState);
                jQuery('#mail_state').select2({
                    placeholder: "Select State",
                })
                if (currentState != '') {
                    jQuery('#mail_city option').attr('disabled', 'disabled');
                    jQuery('#mail_city option[value=""]').removeAttr('disabled')
                    jQuery('#mail_city option[data-state_id="' + currentState + '"]').removeAttr('disabled');
                    jQuery('#mail_city').val(jQuery('#city').val());
                    jQuery('#mail_city').select2({
                        placeholder: "Select City",
                    })

                } else {
                    jQuery('#mail_city option').attr('disabled', 'disabled');
                    jQuery('#mail_city').select2({
                        placeholder: "Select City",
                    })
                }
                jQuery('#mail_postal_code').val(jQuery('#postal_code').val());

            } else {
                jQuery('#postal_street_number').val('');
                jQuery('#postal_street_name').val('');
                jQuery('#postal_street_type').val('');
                jQuery('#postal_suburb').val('');
                jQuery('#mail_state').val('');
                jQuery('#mail_state').select2({
                    placeholder: "Select State",
                })
                jQuery('#mail_city').val('');
                jQuery('#mail_city option').attr('disabled', 'disabled');
                jQuery('#mail_city').select2({
                    placeholder: "Select City",
                })

                jQuery('#mail_postal_code').val('');
            }
        }
    </script>
    @if(Session::has('message'))
        <script>
            successMessage("{{ Session::get('message') }}")
        </script>
    @endif
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Script loaded.');
    
            var clientTypeDropdown = document.getElementById('client_type');
            var industryDropdown = document.getElementById('client_industry');
            var companyFieldsInputs = document.querySelectorAll('.company-fields input');
    
            function handleClientTypeChange() {
                var isIndividual = clientTypeDropdown.value.toLowerCase() === 'individual';
    
                companyFieldsInputs.forEach(input => input.disabled = isIndividual);
                industryDropdown.disabled = isIndividual;
    
                console.log('Client Type:', clientTypeDropdown.value);
                console.log('Input fields and Industry dropdown disabled:', isIndividual);
            }
    
            if (clientTypeDropdown && industryDropdown && companyFieldsInputs.length > 0) {
                console.log('Elements found. Setting up event listener.');
                clientTypeDropdown.addEventListener('change', handleClientTypeChange);
            } else {
                console.error('Error: Client Type dropdown, Industry dropdown, or Company fields inputs not found.');
            }
        });
    </script>


    <style>
        .select2-container--bootstrap4,
        .select2-container--default {
            width: 100% !important;
        }
        .hidden{
            display: none !important;
        }
    </style>

@endpush
