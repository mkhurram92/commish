@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if (isset($contact) && !empty($contact))
        Edit Referror :: {{ $contact->surname }} {{ $contact->preferred_name }}
    @else
        Add Referror
    @endif
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                @if (isset($contact) && !empty($contact))
                    Edit Referrer :: {{ $contact->surname }} {{ $contact->preferred_name }}
                @else
                    Add
                    @if (Route::current()->getName() == 'referrer_add')
                        Referrer
                    @else
                        Referrer
                    @endif
                @endif
            </h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex">
                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                            width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg>
                        <span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('admin.contact.list') }}">Referrer</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($contact) && !empty($contact))
                        Edit Referrer :: {{ $contact->surname }} {{ $contact->preferred_name }}
                    @else
                        Add
                        @if (Route::current()->getName() == 'referrer_add')
                            Referrer
                        @else
                            Referrer
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
            <div class="main-card card mb-3">
                <div class="card-body">
                    <form method="post"
                        action="{{ isset($contact) ? route('admin.contact.update', encrypt($contact->id)) : route('admin.contact.post') }}"
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
                                                Basic information
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-4 col-sm-12" style="display:none;">
                                                    <div class="position-relative form-group">
                                                        <input type="hidden" name="search_for" value="2">
                                                        @if ($errors->has('search_for'))
                                                            <div class="error" style="color:red">
                                                                {{ $errors->first('search_for') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Referrer Type</label>
                                                            <select name="client_type" id="client_type"
                                                                class="form-control">
                                                                <option value="">Select Type</option>
                                                                <option value="Individual"
                                                                    @if (isset($contact) && $contact->individual == '1') selected @endif>
                                                                    Individual</option>
                                                                <option value="Company"
                                                                    @if (isset($contact) && $contact->individual == '2') selected @endif>
                                                                    Company</option>
                                                            </select>
                                                            @if ($errors->has('client_type'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('client_type') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-6 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Company
                                                                Name</label>
                                                            <input name="entity_name" maxlength="255"
                                                                value="{{ isset($contact) ? $contact->entity_name : '' }}"
                                                                placeholder="Company Name" type="text"
                                                                class="form-control">
                                                            @if ($errors->has('entity_name'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('entity_name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Trading/Business
                                                                Name</label>
                                                            <input name="trading"
                                                                value="{{ isset($contact) ? $contact->trading : '' }}"
                                                                placeholder="Trading/Business" type="text"
                                                                maxlength="255" class="form-control">
                                                            @if ($errors->has('trading'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('trading') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Trust
                                                                Name</label>
                                                            <input name="trust_name"
                                                                value="{{ isset($contact) ? $contact->trust_name : '' }}"
                                                                placeholder="Trust Name" maxlength="255" type="text"
                                                                class="form-control">
                                                            @if ($errors->has('trust_name'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('trust_name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Industry</label>
                                                            <select name="client_industry" id="client_industry"
                                                                class="form-control">
                                                                <option value="" selected>Choose One</option>
                                                                @foreach ($industries as $industry)
                                                                    <option value="{{ $industry->id }}"
                                                                        {{ isset($contact) && $contact->client_industry == $industry->id ? 'selected="selected"' : '' }}>
                                                                        {{ $industry->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('client_industry'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('client_industry') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12 company-fields">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Principle
                                                                Contact</label>
                                                            <input name="principle_contact" maxlength="15"
                                                                value="{{ isset($contact) ? $contact->principle_contact : '' }}"
                                                                placeholder="Principle Contact" type="text"
                                                                class="form-control input_int_number">
                                                            @if ($errors->has('principle_contact'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('principle_contact') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Salutation</label>
                                                            <select name="role_title" id="role_title"
                                                                class="form-control">
                                                                <option value="mr"
                                                                    @if (isset($contact) && $contact->role_title == 'mr') selected @endif>
                                                                    Mr
                                                                </option>
                                                                <option value="miss"
                                                                    @if (isset($contact) && $contact->role_title == 'miss') selected @endif>
                                                                    Miss
                                                                </option>
                                                                <option value="mrs"
                                                                    @if (isset($contact) && $contact->role_title == 'mrs') selected @endif>
                                                                    Mrs
                                                                </option>
                                                                <option value="ms"
                                                                    @if (isset($contact) && $contact->role_title == 'ms') selected @endif>
                                                                    Ms
                                                                </option>
                                                                <option value="dr"
                                                                    @if (isset($contact) && $contact->role_title == 'dr') selected @endif>
                                                                    Dr
                                                                </option>
                                                                <option value="prof"
                                                                    @if (isset($contact) && $contact->role_title == 'prof') selected @endif>
                                                                    Prof.
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Surname</label>
                                                            <input name="surname" maxlength="150"
                                                                value="{{ isset($contact) ? $contact->surname : '' }}"
                                                                placeholder="Surname" type="text"
                                                                class="form-control">
                                                            @if ($errors->has('surname'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('surname') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Given
                                                                Name</label>
                                                            <input name="preferred_name" maxlength="255"
                                                                value="{{ isset($contact) ? $contact->preferred_name : '' }}"
                                                                placeholder="Given Name" type="text"
                                                                class="form-control">
                                                            @if ($errors->has('preferred_name'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('preferred_name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Middle
                                                                Name</label>
                                                            <input name="middle_name" maxlength="255"
                                                                value="{{ isset($contact) ? $contact->middle_name : '' }}"
                                                                placeholder="Middle Name" type="text"
                                                                class="form-control">
                                                            @if ($errors->has('middle_name'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('middle_name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">DOB</label>
                                                            <input name="dob"
                                                                value="{{ isset($contact) ? $contact->dob : '' }}"
                                                                type="text" placeholder="dd/mm/yyyy"
                                                                data-toggle="datepicker" class="form-control">
                                                            @if ($errors->has('dob'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('dob') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Status</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="1"
                                                                    @if (isset($contact) && $contact->status == 1) selected @endif>
                                                                    Active
                                                                </option>
                                                                <option value="0"
                                                                    @if (isset($contact) && $contact->status == 0) selected @endif>
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
                                                            <label class="form-label font-weight-bold">Work
                                                                Phone</label>
                                                            <input name="work_phone"
                                                                value="{{ isset($contact) ? $contact->work_phone : '' }}"
                                                                placeholder="Work Phone" type="text"
                                                                class="form-control input_int_number" maxlength="15">
                                                            @if ($errors->has('work_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('work_phone') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Home
                                                                Phone</label>
                                                            <input name="home_phone"
                                                                value="{{ isset($contact) ? $contact->home_phone : '' }}"
                                                                placeholder="Home Phone" type="text"
                                                                class="form-control input_int_number" maxlength="15">
                                                            @if ($errors->has('home_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('home_phone') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Mobile
                                                                Phone</label>
                                                            <input name="mobile_phone"
                                                                value="{{ isset($contact) ? $contact->mobile_phone : '' }}"
                                                                placeholder="Mobile Phone" type="text"
                                                                class="form-control input_int_number" maxlength="15">
                                                            @if ($errors->has('mobile_phone'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('mobile_phone') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Email</label>
                                                            <input name="email" placeholder="Email"
                                                                value="{{ isset($contact) ? $contact->email : '' }}"
                                                                type="email" class="form-control" maxlength="255">
                                                            @if ($errors->has('email'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('email') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Web</label>
                                                            <input name="web" placeholder="Web"
                                                                value="{{ isset($contact) ? $contact->web : '' }}"
                                                                type="text" class="form-control" maxlength="255">
                                                            @if ($errors->has('web'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('web') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">ABN</label>
                                                            <input name="abn" placeholder="ABN"
                                                                value="{{ isset($contact) ? $contact->abn : '' }}"
                                                                type="text" class="form-control" maxlength="255">
                                                            @if ($errors->has('abn'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('abn') }}
                                                                </div>
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
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label
                                                                class="form-label font-weight-bold">Unit/Level/Street</label>
                                                            <input type="text" placeholder="Unit/Level/Street"
                                                                name="street_number" id="street_number"
                                                                value="{{ isset($contact) ? $contact?->withAddress[1]?->unit ?? '' : '' }}"
                                                                class="form-control" maxlength="250" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                name</label>
                                                            <input type="text" placeholder="Street name"
                                                                name="street_name" id="street_name"
                                                                value="{{ isset($contact) ? $contact?->withAddress[1]?->street_name ?? '' : '' }}"
                                                                class="form-control" maxlength="250" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">City</label>
                                                            <input type="text" placeholder="City" name="suburb"
                                                                id="suburb" class="form-control"
                                                                value="{{ isset($contact) ? $contact?->withAddress[1]?->city ?? '' : '' }}"
                                                                maxlength="250" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">State</label>
                                                            <select name="state" id="state"
                                                                class="multiselect-dropdown">
                                                                <option value="">Select State</option>
                                                                @if (count($states) > 0)
                                                                    @foreach ($states as $state)
                                                                        <option value="{{ $state->id }}"
                                                                            {{ isset($contact) && isset($contact->withAddress[1]->state) && $contact->withAddress[1]->state == $state->id ? 'selected="selected"' : '' }}>
                                                                            {{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if ($errors->has('state'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('state') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Postal
                                                                Code</label>
                                                            <input id="postal_code" name="postal_code"
                                                                value="{{ isset($contact) ? $contact?->withAddress[1]?->postal_code ?? '' : '' }}"
                                                                placeholder="Postal Code" type="number"
                                                                class="form-control alpha-num" maxlength="10">
                                                            @if ($errors->has('postal_code'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('postal_code') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-2">
                                            <div class="card-header">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            Postal Address
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="custom-control-inline">
                                                                <input type="checkbox"
                                                                    onchange="return checkasresidentials(this)"
                                                                    value="1" name="same_as_residential"
                                                                    id="same_as_residential" class="custom-control-input"
                                                                    {{ isset($contact) && $contact->same_as_residential == 1 ? 'checked="checked"' : '' }} />
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
                                                            <label
                                                                class="form-label font-weight-bold">Unit/Level/Street</label>
                                                            <input type="text" placeholder="Unit/Level/Street"
                                                                name="postal_street_number" id="postal_street_number"
                                                                value="{{ isset($contact) ? $contact?->withAddress[0]?->unit ?? '' : '' }}"
                                                                class="form-control" maxlength="250" />
                                                        </div>

                                                    </div>
                                                    <div class="col-md-8 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Street
                                                                name</label>
                                                            <input type="text" placeholder="Street name"
                                                                name="postal_street_name"
                                                                value="{{ isset($contact) ? $contact?->withAddress[0]?->street_name ?? '' : '' }}"
                                                                id="postal_street_name" class="form-control"
                                                                maxlength="250" />
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">City</label>
                                                            <input type="text" placeholder="City" name="postal_suburb"
                                                                value="{{ isset($contact) ? $contact?->withAddress[0]?->city ?? '' : '' }}"
                                                                id="postal_suburb" class="form-control"
                                                                maxlength="250" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">State</label>
                                                            <select name="mail_state" id="mail_state"
                                                                class="multiselect-dropdown">
                                                                <option value="">Select State</option>
                                                                @if (count($states) > 0)
                                                                    @foreach ($states as $state)
                                                                        <option value="{{ $state->id }}"
                                                                            {{ isset($contact) && isset($contact->withAddress[0]->state) && $contact->withAddress[0]->state == $state->id ? 'selected="selected"' : '' }}>
                                                                            {{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if ($errors->has('mail_state'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('mail_state') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Postal
                                                                Code</label>
                                                            <input id="mail_postal_code" name="mail_postal_code"
                                                                value="{{ isset($contact) ? $contact?->withAddress[0]?->postal_code ?? '' : '' }}"
                                                                placeholder="Postal Code" type="number"
                                                                class="form-control alpha-num" maxlength="10">
                                                            @if ($errors->has('mail_postal_code'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('mail_postal_code') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
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
                                                            <label class="form-label font-weight-bold">Bank Name</label>
                                                            <input name="bank"
                                                                value="{{ old('bank', isset($contact) ? $contact->bank : '') }}"
                                                                placeholder="Bank Name" type="text"
                                                                class="form-control alphaonly" maxlength="250">
                                                            @if ($errors->has('bank'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('bank') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Account Name</label>
                                                            <input name="acc_name"
                                                                value="{{ old('acc_name', isset($contact) ? $contact->acc_name : '') }}"
                                                                placeholder="Account Name" type="text"
                                                                class="form-control alpha-num" maxlength="255">
                                                            @if ($errors->has('acc_name'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('acc_name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>



                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">BSB</label>
                                                            <input name="bsb"
                                                                value="{{ old('bsb', isset($contact) ? $contact->bsb : '') }}"
                                                                placeholder="BSB" type="text"
                                                                class="form-control input_int_number" maxlength="250">
                                                            @if ($errors->has('bsb'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('bsb') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Account
                                                                Number</label>
                                                            <input name="acc_no"
                                                                value="{{ old('acc_no', isset($contact) ? $contact->acc_no : '') }}"
                                                                placeholder="Account Number" type="text"
                                                                class="form-control input_int_number" maxlength="250">
                                                            @if ($errors->has('acc_no'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('acc_no') }}</div>
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

                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Broker</label>
                                                            <select name="abp" id="abp" class="form-control">
                                                                <option value="" selected>Choose One</option>
                                                                @foreach ($abps as $abp)
                                                                    <option value="{{ $abp->id }}"
                                                                        {{ isset($contact) && $contact->abp == $abp->id ? 'selected="selected"' : '' }}>
                                                                        {{ $abp->trading }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('abp'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('abp') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="position-relative form-group">
                                                            <label class="form-label font-weight-bold">Subject to GST</label>
                                                            <select name="has_gst" id="has_gst" class="form-control">
                                                                <option value="1"
                                                                    @if (isset($contact) && $contact->has_gst == 1) selected @endif>
                                                                    Yes
                                                                </option>
                                                                <option value="0"
                                                                    @if (isset($contact) && $contact->has_gst == 0) selected @endif>
                                                                    No
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="position-relative form-group">
                                                            <textarea name="note" id="note" cols="30" rows="5" placeholder="Note"
                                                                class="form-control w-100">{{ isset($contact) ? $contact->note : '' }}</textarea>
                                                            @if ($errors->has('note'))
                                                                <div class="error" style="color:red">
                                                                    {{ $errors->first('note') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-1" class="tab-pane tabs-animation fade" role="tabpanel">
                                        <div id="accordion" class="accordion-wrapper mb-3">
                                            <div class="card">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="50%">Referrer</th>
                                                                        <th width="10%">Upfront %</th>
                                                                        <th width="10%">Trail %</th>
                                                                        <th width="10%">Comm per Deal</th>
                                                                        <th width="10%">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="task_tbody">
                                                                    @if (isset($broker, $broker->referrors) && count($broker->referrors) > 0)
                                                                        @foreach ($broker->referrors as $tkey => $tval)
                                                                            <tr id="task_rw_{{ $tkey }}">
                                                                                <input type="hidden"
                                                                                    name="referrors[{{ $tkey }}][old_id]"
                                                                                    id="referrors_{{ $tkey }}_old_id"
                                                                                    value="{{ $tval->id }}" />
                                                                                <td><select
                                                                                        name="referrors[{{ $tkey }}][referrer_id]"
                                                                                        id="referrors_{{ $tkey }}_referror"
                                                                                        class="form-control">
                                                                                        <option value=""></option>
                                                                                        @foreach ($referrers as $refferor)
                                                                                            <option
                                                                                                value="{{ $refferor->id }}"
                                                                                                {{ $refferor->id == $tval->referror ? 'selected="selected"' : '' }}>
                                                                                                {{ $refferor->refferor_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select></td>
                                                                                <td><input type="text"
                                                                                        name="referrors[{{ $tkey }}][upfront]"
                                                                                        class="form-control number-input"
                                                                                        data-min="0" data-max="100"
                                                                                        id="referrors_{{ $tkey }}_upfront"
                                                                                        placeholder="Upfront"
                                                                                        maxlength="255"
                                                                                        value="{{ $tval->upfront }}" />
                                                                                </td>
                                                                                <td><input type="text"
                                                                                        name="referrors[{{ $tkey }}][trail]"
                                                                                        class="form-control number-input"
                                                                                        id="referrors_{{ $tkey }}_trail"
                                                                                        placeholder="Trail"
                                                                                        maxlength="255"
                                                                                        value="{{ $tval->trail }}" />
                                                                                </td>
                                                                                <td><input type="text"
                                                                                        name="referrors[{{ $tkey }}][comm_per_deal]"
                                                                                        class="form-control number-input"
                                                                                        data-min="0" data-max="100"
                                                                                        id="referrors_{{ $tkey }}_comm_per_deal"
                                                                                        placeholder="Comm per deal"
                                                                                        value="{{ $tval->comm_per_deal }}" />
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-danger"
                                                                                        onclick="return removeReferror(this)"
                                                                                        data-id="{{ $tkey }}"><i
                                                                                            class="fa fa-trash"></i></button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr id="task_rw_0">
                                                                            <td><select name="referrors[0][referrer_id]"
                                                                                    id="referrors_0_referror"
                                                                                    class="form-control referrors_sel_cl">
                                                                                    <option value=""></option>
                                                                                    @foreach ($referrers as $refferor)
                                                                                        <option
                                                                                            value="{{ $refferor->id }}">
                                                                                            {{ $refferor->refferor_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select></td>
                                                                            <td><input type="text"
                                                                                    name="referrors[0][upfront]"
                                                                                    class="form-control number-input"
                                                                                    data-min="0" data-max="100"
                                                                                    id="referrors_0_upfront"
                                                                                    placeholder="Upfront"
                                                                                    maxlength="255" /></td>
                                                                            <td><input type="text"
                                                                                    name="referrors[0][trail]"
                                                                                    class="form-control number-input"
                                                                                    id="referrors_0_trail"
                                                                                    placeholder="Trail" maxlength="255" />
                                                                            </td>
                                                                            <td><input type="text"
                                                                                    name="referrors[0][comm_per_deal]"
                                                                                    class="form-control number-input"
                                                                                    data-min="0" data-max="100"
                                                                                    id="referrors_0_comm_per_deal"
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
                                                        <button type="button" class="btn btn-primary"
                                                            id="add_new_referror" onclick="addNewReferror(this)">Add More
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
                            {{-- <button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset
                                                    </button> --}}
                            <button type="submit" id="finish-btn"
                                class="btn-shadow btn-wide btn-pill btn btn-success float-right mr-3">
                                Finish
                            </button>
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
    <script type="text/javascript" src="{{ asset('front-assets/vendors/smartwizard/dist/js/jquery.smartWizard.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('front-assets/js/form-components/form-wizard.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front-assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('front-assets/js/form-components/datepicker.js') }}"></script>
    <script>
        var referror_string = '';
        @foreach ($referrers as $refferor)
            referror_string += '<option value="{{ $refferor->id }}">{{ $refferor->display_name }}</option>';
        @endforeach

        $('#isIndividual').click(function() {
            //var checker = $("#isIndividual").toggle(this.checked);
        });
        var relationstring = '';
        @foreach ($relations as $relation)
            relationstring += '<option value="{{ $relation->id }}">{{ $relation->name }}</option>';
        @endforeach

        var clientstring = '';
        @foreach ($clients as $client)
            clientstring += '<option value="{{ $client->id }}">{{ $client->display_name }}</option>';
        @endforeach

        $('#isIndividual').click(function() {
            //var checker = $("#isIndividual").toggle(this.checked);
        });

        function previousnext(step) {
            var currentActive = parseInt(jQuery('a.nav-link.active').attr('data-counter'));

            if (parseInt(step) == 1) {

                if (currentActive != 4) {
                    var newStep = currentActive + 1;

                    jQuery('a.nav-link').removeClass('active');
                    jQuery('a.nav-link-' + newStep).trigger('click');
                }
            } else if (parseInt(step) == 0) {
                if (currentActive != 0) {
                    var newStep = currentActive - 1;
                    jQuery('a.nav-link').removeClass('active');
                    jQuery('a.nav-link-' + newStep).trigger('click');
                }
            }
        }

        jQuery(document).ready(function() {

            jQuery('body').on('change', '#client_industry', function() {
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
            @if (isset($contact) && $contact->state > 0)
                jQuery('#city option').attr('disabled', 'disabled');
                jQuery('#city option[value=""]').removeAttr('disabled')
                jQuery('#city option[data-state_id="{{ $contact->state }}"]').removeAttr('disabled');
                $("#city").select2({
                    placeholder: "Select City",
                });
            @else
                $("#city").select2({
                    placeholder: "Select City",
                });
            @endif

            jQuery('body').on('keyup', '#street_number', function() {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_number').val(jQuery('#street_number').val())
                }
            })

            jQuery('body').on('keyup', '#street_name', function() {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_name').val(jQuery('#street_name').val())
                }
            });

            jQuery('body').on('keyup', '#street_type', function() {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_street_type').val(jQuery('#street_type').val())
                }
            });
            jQuery('body').on('keyup', '#suburb', function() {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#postal_suburb').val(jQuery('#suburb').val())
                }
            });
            jQuery('body').on('keyup', '#postal_code', function() {
                if (jQuery('#same_as_residential').is(':checked')) {
                    jQuery('#mail_postal_code').val(jQuery('#postal_code').val())
                }
            });
            jQuery('.referrors_sel_cl').select2({

                placeholder: "Select Referrer",
            });

            @if (isset($contact) && $contact->mail_state > 0)
                jQuery('#mail_city option').attr('disabled', 'disabled');
                jQuery('#mail_city option[value=""]').removeAttr('disabled')
                jQuery('#mail_city option[data-state_id="{{ $contact->mail_state }}"]').removeAttr('disabled');
                $("#mail_city").select2({
                    placeholder: "Select City",
                });
            @else
                $("#mail_city").select2({
                    placeholder: "Select City",
                });
            @endif


            jQuery('body').on('change', '#state', function() {
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
                        jQuery('#mail_state option[value="' + currentState + '"]').attr('selected',
                            'selected');
                        jQuery('#mail_state').select2({
                            placeholder: "Select State",
                        })
                        jQuery('#mail_city option').attr('disabled', 'disabled');
                        jQuery('#mail_city option[value=""]').removeAttr('disabled')
                        jQuery('#mail_city option[data-state_id="' + currentState + '"]').removeAttr(
                            'disabled');
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

            jQuery('body').on('change', '#mail_state', function() {
                var currentState = jQuery(this).val();
                if (currentState != '') {
                    jQuery('#mail_city option').attr('disabled', 'disabled');
                    jQuery('#mail_city option[value=""]').removeAttr('disabled')
                    jQuery('#mail_city option[data-state_id="' + currentState + '"]').removeAttr(
                    'disabled');
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

            jQuery('body').on('change', '#city', function() {
                var currentVal = jQuery(this).val();
                jQuery('#mail_city option[value="' + currentVal + '"]').attr('selected', 'selected');
                $("#mail_city").select2({
                    placeholder: "Select City",
                });
            });
        });

        var rel_counter =
            {{ isset($contact, $contact->relations) && count($contact->relations) > 0 ? count($contact->relations) : 2 }};
        var task_counter =
            {{ isset($contact, $contact->tasks) && count($contact->tasks) > 0 ? count($contact->tasks) : 2 }};

        function addNewRelation(current) {
            var htmlCon = '<div class="col-sm-12" id="rel_' + rel_counter +
                '"><div class="row"><div class="col-sm-4"><div ' +
                'class="form-group"><select  name="relationship[' + rel_counter + '][linked_to]" ' +
                'id="relationship_' + rel_counter + '_linked_to" class="form-control linked_to_selector"><option ' +
                'value="">Select ' +
                'Client</option>' + clientstring +
                '</select></div></div><div class="col-sm-4"><div class="form-group"><select ' +
                'name="relationship[' + rel_counter + '][relation]" id="relationship_' + rel_counter + '_relation" ' +
                'class="form-control"><option value="">Select Relation</option>' + relationstring +
                '</select></div></div><div ' +
                'class="col-sm-2"><div class="form-group text-center"><input type="checkbox" name="relationship[' +
                rel_counter + '][mailout]" value="1" type="checkbox" id="relationship_' + rel_counter +
                '_mailout"></div></div><div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removerelation (this)" data-id="' +
                rel_counter + '"><i class="fa fa-trash"></i> ' +
                '</button></div></div></div>';
            jQuery('#relation_conbody').append(htmlCon);
            jQuery('.linked_to_selector').select2({
                placeholder: "Select Linked To",
            })
            rel_counter++;
        }

        function removerelation(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#rel_' + row_id).remove()
            }
        }

        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
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
            var task_tr = '<tr id="task_rw_' + task_counter +
                '"><td><input type="text"  placeholder="dd/mm/yyyy"  data-toggle="datepicker" name="tasks[' + task_counter +
                '][follow_up_date]" class="task_followup_date form-control" id="tasks_' + task_counter +
                '_follow_up_date" placeholder="Follow Up Date" /></td><td><input type="text" name="tasks[' + task_counter +
                '][processor]" class=" form-control" id="tasks_' + task_counter +
                '_processor" placeholder="Processor"  /></td><td><input type="text" name="tasks[' + task_counter +
                '][details]" class=" form-control"id="tasks_' + task_counter +
                '_details"  placeholder="Detail" /></td><td><input type="text" name="tasks[' + task_counter +
                '][user]" class=" form-control" id="tasks_' + task_counter +
                '_user" placeholder="User"  /></td><td><button class="btn btn-danger" onclick="return removetask(this)" data-id="' +
                task_counter + '"><i class="fa fa-trash"></i></button></td></tr>';
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

        function addNewReferror(current) {
            var task_tr = '<tr id="task_rw_' + task_counter + '"> <td><select name="referrors[' + task_counter +
                '][referrer_id]" ' +
                'id="referrors_' + task_counter + '_referror" class="form-control' +
                'referrors_sel_cl" > <option ' +
                'value=""></option>' + referror_string + '</select></td><td><input ' +
                'type="text" name="referrors[' + task_counter + '][upfront]" class=" form-control number-input" ' +
                'data-min="0" data-max="100" id="referrors_' + task_counter + '_upfront" placeholder="Upfront" ' +
                'maxlength="255"/></td><td><input type="text" name="referrors[' + task_counter +
                '][trail]" class=" form-control number-input" id="referrors_' + task_counter +
                '_trail" placeholder="Trail" maxlength="255"/></td><td><input type="text" name="referrors[' + task_counter +
                '][comm_per_deal]" class=" form-control number-input" data-min="0" data-max="100" id="referrors_' +
                task_counter +
                '_comm_per_deal" placeholder="Comm per deal"/></td><td> <button class="btn btn-danger" onclick="return removeReferror(this)" data-id="' +
                task_counter + '"><i class="fa fa-trash"></i></button> </td></tr>';
            jQuery('#task_tbody').append(task_tr);
            jQuery('#referrors_' + task_counter + '_referror').select2({

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
                        var url = "/" + data.id;
                        setTimeout(function() {
                            //window.location = "{{ route('admin.contact.list') }}"
                            window.location = "{{ url('admin/referrer/view') }}" + url;
                        }, 3000);
                        hideLoader();

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);

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
    @if (Session::has('message'))
        <script>
            successMessage("{{ Session::get('message') }}")
        </script>
    @endif
    <style>
        .select2-container--bootstrap4,
        .select2-container--default {
            width: 100% !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                // Call the function on page load
                handleClientTypeChange();
            } else {
                console.error(
                    'Error: Client Type dropdown, Industry dropdown, or Company fields inputs not found.');
            }
        });
    </script>
@endpush
