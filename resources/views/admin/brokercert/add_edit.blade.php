@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($taskdata))
        {{$broker->given_name}} :: Edit Broker Certification
    @else
        {{$broker->given_name}} :: Add Broker Certification
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">

                <div>
                    @if(isset($taskdata))
                        {{$broker->given_name}} :: Edit Broker Certification
                    @else
                        {{$broker->given_name}} :: Add Broker Certification
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form method="post"
                          action="{{isset($taskdata)?route('admin.brokercert.update', [encrypt
                          ($taskdata->id),
                          encrypt($broker->id)]):route('admin.brokercert.post',encrypt($broker->id))}}" onsubmit="return saveForm(this)">
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
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Certificate</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($certifications as $certification)
                                            <option value="{{$certification->id}}"
                                                {{isset($taskdata) &&
                                                $taskdata->type == $certification->id ?
                                                'selected="selected"' : '' }}>{{$certification->name}}</option>
                                        @endforeach

                                    </select>
                                    @if($errors->has('type'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('type')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-check form-check-inline">
                                    <label>&nbsp;</label>
                                    <label class="form-check-label" style="padding-top: 35px;">

                                        <input type="checkbox" data-onstyle="success"
                                               data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                               data-off="No" data-size="mini" value="1"
                                               id="required"
                                               @if(isset($taskdata) && $taskdata->required == 1) checked
                                               @endif name="required"
                                               class="" >
                                        Required
                                    </label>
                                    @if($errors->has('required'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('required')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-check form-check-inline">
                                    <label>&nbsp;</label>
                                    <label class="form-check-label" style="padding-top: 35px;">

                                        <input type="checkbox" data-onstyle="success"
                                               data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                               data-off="No" data-size="mini" value="1"
                                               id="held"
                                               @if(isset($taskdata) && $taskdata->held == 1) checked
                                               @endif name="held"
                                               class="" >
                                        Held
                                    </label>
                                    @if($errors->has('held'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('held')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                               maxlength="255" value="{{isset($taskdata) ? $taskdata->expiry_date : ''}}"/>
                                    @if($errors->has('expiry_date'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('expiry_date')}}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12"></div>
                            <div class="clearfix clear"></div>
                            <button class="mt-1 btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-section')
    <script>
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
                        setTimeout(function(){
                            window.location="{{route('admin.brokercert.list', encrypt($broker->id))}}"
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
    </script>
    @if(Session::has('message'))
        <script>
            successMessage("{{ Session::get('message') }}")
        </script>
    @endif
@endpush

