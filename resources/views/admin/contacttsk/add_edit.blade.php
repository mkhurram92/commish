@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($taskdata))
        {{$contact->display_name}} :: Edit Contact Task
    @else
        {{$contact->display_name}} :: Add Contact Task
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">

                <div>
                    @if(isset($taskdata))
                        {{$contact->display_name}} :: Edit Contact Task
                    @else
                        {{$contact->display_name}} :: Add Contact Task
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
                          action="{{isset($taskdata)?route('admin.contacttsk.update', [encrypt
                          ($taskdata->id),
                          encrypt($contact->id)]):route('admin.contacttsk.post',encrypt($contact->id))}}"
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

                        <div class="form-row">

                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Date</label>
                                        <input type="text" data-toggle="datepicker"  placeholder="dd/mm/yyyy" name="followup_date"
                                               id="followup_date" class="form-control"
                                               maxlength="255" value="{{isset($taskdata) ? $taskdata->followup_date : ''}}"/>
                                    @if($errors->has('followup_date'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('followup_date')}}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="processor" class="form-label font-weight-bold">Processor</label>
                                    <select name="processor" id="processor" class="multiselect-dropdown form-control">
                                        <option value="" >Select Processor</option>
                                        @if(count($processors)>0)
                                            @foreach($processors as $processor)
                                                <option value="{{$processor->id}}"
                                                    {{isset($taskdata) &&  $taskdata->processor ==
                                                    $processor->id ? 'selected="selected"' : ''}}>
                                                    {{$processor->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label for="user" class="form-label font-weight-bold">User</label>
                                    <input type="text" maxlength="250" name="user" id="user" class="form-control" value="{{ isset($taskdata->user)
                                        ?$taskdata->user:'' }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                                    <textarea name="detail" id="detail" class="form-control">{{ isset($taskdata->details)
                                    ?$taskdata->details:'' }}</textarea>
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
    <script type="text/javascript" src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>
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
                            window.location="{{route('admin.contacttsk.list', encrypt($contact->id))}}"
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

