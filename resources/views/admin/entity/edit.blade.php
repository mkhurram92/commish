@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Entity Detail
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Entity Detail</h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex">
                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                            width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg>
                        <span class="breadcrumb-icon"> Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Entity Detail</li>
            </ol>
        </div>
    </div>
@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card card mb-3">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.entity.update') }}" onsubmit="return saveForm(this)">
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
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="name" class="font-weight-bold form-label">Name</label>
                                    <input name="name" id="name" placeholder="Name" type="text"
                                        class="form-control" value="{{ $entity->name }}">
                                    @if ($errors->has('name'))
                                        <div class="error" style="color:red">{{ $errors->first('name') }}</div>
                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="contact_person" class="font-weight-bold form-label">Contact Person</label>
                                    <input name="contact_person" id="contact_person" placeholder="Contact Person"
                                        type="text" class="form-control" value="{{ $entity->contact_person }}">
                                    @if ($errors->has('contact_person'))
                                        <div class="error" style="color:red">{{ $errors->first('contact_person') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="phone_no" class="font-weight-bold form-label">Phone No</label>
                                    <input name="phone_no" id="phone_no" placeholder="Phone No" type="text"
                                        class="form-control" value="{{ $entity->phone_no }}">
                                    @if ($errors->has('phone_no'))
                                        <div class="error" style="color:red">{{ $errors->first('phone_no') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="email" class="font-weight-bold form-label">Email</label>
                                    <input name="email" id="email" placeholder="Email" type="email"
                                        class="form-control" value="{{ $entity->email }}">
                                    @if ($errors->has('email'))
                                        <div class="error" style="color:red">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="web" class="font-weight-bold form-label">Web</label>
                                    <input name="web" id="web" placeholder="Web" type="text"
                                        class="form-control" value="{{ $entity->web }}">
                                    @if ($errors->has('web'))
                                        <div class="error" style="color:red">{{ $errors->first('web') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="abn" class="font-weight-bold form-label">ABN</label>
                                    <input name="abn" id="abn" placeholder="abn" type="text"
                                        class="form-control" value="{{ $entity->abn }}">
                                    @if ($errors->has('abn'))
                                        <div class="error" style="color:red">{{ $errors->first('abn') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="street_address_level" class="font-weight-bold form-label">Level /
                                        Unit</label>
                                    <input name="street_address_level" id="street_address_level" type="text"
                                        placeholder="Level / Unit" class="form-control"
                                        value = "{{ $entity->street_address_level }}">
                                    @if ($errors->has('street_address_level'))
                                        <div class="error" style="color:red">
                                            {{ $errors->first('street_address_level') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="street_address_number" class="font-weight-bold form-label">Street
                                        Number</label>
                                    <input name="street_address_number" id="street_address_number"
                                        placeholder="Street Number" type="text" class="form-control"
                                        value="{{ $entity->street_address_number }}">
                                    @if ($errors->has('street_address_number'))
                                        <div class="error" style="color:red">
                                            {{ $errors->first('street_address_number') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="street_address_name" class="font-weight-bold form-label">Street
                                        Name</label>
                                    <input name="street_address_name" id="street_address_name" placeholder="Street Name"
                                        class="form-control" value = "{{ $entity->street_address_name }}">
                                    @if ($errors->has('street_address_name'))
                                        <div class="error" style="color:red">{{ $errors->first('street_address_name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="state" class="font-weight-bold form-label">State</label>
                                    <select name="state" id="state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                {{ $entity->state == $state->id ? 'selected' : '' }}>{{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('state'))
                                        <div class="error" style="color:red">{{ $errors->first('state') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="postcode" class="font-weight-bold form-label">Suburb</label>
                                    <input name="suburb" id="suburb" placeholder="Suburb" type="text"
                                        class="form-control" value="{{ $entity->suburb }}">
                                    @if ($errors->has('suburb'))
                                        <div class="error" style="color:red">{{ $errors->first('suburb') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="postcode" class="font-weight-bold form-label">Post Code</label>
                                    <input name="postcode" id="postcode" placeholder="postcode" type="text"
                                        class="form-control" value="{{ $entity->pincode }}">
                                    @if ($errors->has('postcode'))
                                        <div class="error" style="color:red">{{ $errors->first('postcode') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-success mt-1">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-section')
    <script>
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
                        setTimeout(function() {
                            window.location = "{{ route('admin.entity.edit') }}"
                        }, 3000);
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
    @if (Session::has('message'))
        <script>
            successMessage("{{ Session::get('message') }}")
        </script>
    @endif
@endpush
