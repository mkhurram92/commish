@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($taskdata))
        {{$broker->given_name}} :: Edit Broker Task
    @else
        {{$broker->given_name}} :: Add Broker Task
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">

                <div>
                    @if(isset($taskdata))
                        {{$broker->given_name}} :: Edit Broker Task
                    @else
                        {{$broker->given_name}} :: Add Broker Task
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
                          action="{{isset($taskdata)?route('admin.brokertsk.update', [encrypt
                          ($taskdata->id),
                          encrypt($broker->id)]):route('admin.brokertsk.post',encrypt($broker->id))}}" onsubmit="return saveForm(this)">
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
                                    <label class="form-label font-weight-bold">Person to Followup</label>
                                        <input type="text" name="person_to_followup" id="person_to_followup"
                                               class="form-control" maxlength="255" placeholder="Person to Followup"
                                               value="{{isset($taskdata) ? $taskdata->person_to_followup : ''}}"/>
                                    @if($errors->has('person_to_followup'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('person_to_followup')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Date</label>
                                        <input type="date" name="followup_date" id="followup_date" class="form-control" maxlength="255" value="{{isset($taskdata) ? $taskdata->followup_date : ''}}"/>
                                    @if($errors->has('followup_date'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('followup_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Completed Date</label>
                                        <input type="date" name="completed_date" id="completed_date" class="form-control" maxlength="255" {{isset($taskdata) ? $taskdata->completed_date : ''}}/>
                                    @if($errors->has('completed_date'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('completed_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($statuses as $status)
                                                <option value="{{$status->id}}"
                                                    {{isset($taskdata) &&
                                                    $taskdata->status == $status->id ?
                                                    'selected="selected"' : '' }}>{{$status->name}}</option>
                                            @endforeach

                                    </select>
                                    @if($errors->has('status'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('status')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                                    <textarea name="detail" id="detail" class="form-control">{{ isset($taskdata->detail)
                                    ?$taskdata->detail:'' }}</textarea>
                                </div>
                            </div>
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
                            window.location="{{route('admin.brokertsk.list', encrypt($broker->id))}}"
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

