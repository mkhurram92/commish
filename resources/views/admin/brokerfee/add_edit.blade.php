@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($taskdata))
        {{$broker->given_name}} :: Edit Broker Fee
    @else
        {{$broker->given_name}} :: Add Broker Fee
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">

                <div>
                    @if(isset($taskdata))
                        {{$broker->given_name}} :: Edit Broker Fee
                    @else
                        {{$broker->given_name}} :: Add Broker Fee
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
                          action="{{isset($taskdata)?route('admin.brokerfee.update', [encrypt
                          ($taskdata->id),
                          encrypt($broker->id)]):route('admin.brokerfee.post',encrypt($broker->id))}}" onsubmit="return saveForm(this)">
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
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Fee Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($fee_types as $fee_type)
                                            <option value="{{$fee_type->id}}"
                                                {{isset($taskdata) &&
                                                $taskdata->type == $fee_type->id ?
                                                'selected="selected"' : '' }}>{{$fee_type->name}}</option>
                                        @endforeach

                                    </select>
                                    @if($errors->has('type'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('type')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Frequency</label>
                                    <select name="frequency" id="frequency" class="form-control" required>
                                        <option selected value="">Choose Frequency</option>
                                        @foreach($frequencies as $key => $frequency)
                                            <option value="{{$key}}"
                                                {{isset($taskdata) &&
                                                $taskdata->frequency == $key ?
                                                'selected="selected"' : '' }}>{{$frequency}}</option>
                                        @endforeach

                                    </select>
                                    @if($errors->has('frequency'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('frequency')}}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Due Date</label>
                                        <input type="date" name="due_date" id="due_date" class="form-control"
                                               maxlength="255" value="{{isset($taskdata) ? $taskdata->due_date : ''}}"/>
                                    @if($errors->has('due_date'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('due_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label  class="form-label font-weight-bold">Amount</label>
                                    <input name="amount" id="amount" type="text" class="form-control
                                    text-lowercase number-input" placeholder="Amount" required value="{{ isset
                                    ($taskdata->amount)
                                    ?$taskdata->amount:'' }}">
                                    @if($errors->has('amount'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('amount')}}</div>
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
                            window.location="{{route('admin.brokerfee.list', encrypt($broker->id))}}"
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

