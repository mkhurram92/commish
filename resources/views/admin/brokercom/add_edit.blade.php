@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($taskdata))
        {{$broker->given_name}} :: Edit Broker Commission Model
    @else
        {{$broker->given_name}} :: Add Broker Commission Model
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">

                <div>
                    @if(isset($taskdata))
                        {{$broker->given_name}} :: Edit Broker Commission Model
                    @else
                        {{$broker->given_name}} :: Add Broker Commission Model
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
                          action="{{isset($taskdata)?route('admin.brokercom.update', [encrypt
                          ($taskdata->id),
                          encrypt($broker->id)]):route('admin.brokercom.post',encrypt($broker->id))}}" onsubmit="return saveForm(this)">
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
                                                <option value="{{$commission_model->id}}">{{ $commission_model->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                 <div class="col-sm-3">
                                    <div class="position-relative form-group">
                                        <label  class="form-label font-weight-bold">Upfront(%)</label>
                                        <input name="upfront_per" id="upfront_per" type="text" class="form-control
                                        text-lowercase number-input" data-max="100" data-min="0" placeholder="Upfront" required value="{{ isset
                                        ($taskdata->upfront_per)
                                        ?$taskdata->upfront_per:'' }}">
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
                                        text-lowercase number-input" data-max="100" data-min="0" placeholder="Trail" required value="{{ isset
                                        ($taskdata->trail_per)
                                        ?$taskdata->trail_per:'' }}">
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
                                        text-lowercase number-input" placeholder="Flat Fee Chrg" required value="{{ isset
                                        ($taskdata->flat_fee_chrg)
                                        ?$taskdata->flat_fee_chrg:'' }}">
                                        @if($errors->has('flat_fee_chrg'))
                                            <div class="error"
                                                 style="color:red">{{$errors->first('flat_fee_chrg')}}</div>
                                        @endif
                                    </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="position-relative form-group">
                                        <label  class="form-label font-weight-bold">BDM Flat Fee (%)</label>
                                        <input name="bdm_flat_fee_per" id="bdm_flat_fee_per" type="text" class="form-control
                                        text-lowercase number-input" placeholder="BDM Flat Fee Percent" required value="{{ isset
                                        ($taskdata->bdm_flat_fee_per)
                                        ?$taskdata->bdm_flat_fee_per:'' }}">
                                        @if($errors->has('bdm_flat_fee_per'))
                                            <div class="error"
                                                 style="color:red">{{$errors->first('bdm_flat_fee_per')}}</div>
                                        @endif
                                    </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="position-relative form-group">
                                        <label  class="form-label font-weight-bold">BDM Upfront (%)</label>
                                        <input name="bdm_upfront_per" id="bdm_upfront_per" type="text" class="form-control
                                        text-lowercase number-input" placeholder="BDM Upfront Percent" required value="{{ isset
                                        ($taskdata->bdm_upfront_per)
                                        ?$taskdata->bdm_upfront_per:'' }}">
                                        @if($errors->has('bdm_upfront_per'))
                                            <div class="error"
                                                 style="color:red">{{$errors->first('bdm_upfront_per')}}</div>
                                        @endif
                                    </div>
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
                                            <tr>
                                                <td>{{ ($key + 1) }}</td>
                                                <td>{{($institute->name != '') ? $institute->name : $institute->code}}</td>
                                                <td><input type="hidden" name="institutes_model[{{$institute->id}}][id]" value="{{ $institute->id }}" class="form-control" data-max="100" /><input type="text" class="form-control upfront_amnt number-input" placeholder="Upfront" data-max="100" name="institutes_model[{{$institute->id}}][upfront]" id="institutes_model_{{$institute->id}}_upfront"  /></td>
                                                <td><input type="text" class="form-control trail_amnt number-input" data-max="100" name="institutes_model[{{$institute->id}}][trail]" id="institutes_model_{{$institute->id}}_trail" placeholder="Trail"  /></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                                 <div class="col-sm-12"></div>
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

        jQuery(document).ready(function(){

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
                {
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
                            location.reload()
                        },1000);
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

