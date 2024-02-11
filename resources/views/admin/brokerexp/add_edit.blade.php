@extends('layout.main')

@push('style-section')
@endpush
@section('title')
    @if(isset($expdata))
        Edit Broker Expense
    @else
        Add Broker Expense
    @endif
@endsection
@section('page_title_con')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    @if(isset($expdata))
                    Edit Broker Expense::{{$broker->given_name}}
                @else
                    Add Broker Expense
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
                          action="{{isset($expdata) && !empty($expdata) ? route('admin.brokerexp.update', [encrypt
                          ($expdata->id),
                          encrypt($broker->id)]):route('admin.brokerexp.post', encrypt($broker->id))}}" onsubmit="return saveForm(this)">
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
                        <div class="position-relative form-group">
                            <label for="exampleSelect" class="">Broker Name :</label>
                            <b>{{ $broker->given_name }}</b>

                        </div>
                        <div class="form-row">
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label for="exampleSelect" class="form-label font-weight-bold">Expense Type</label>
                                <select name="expense_type_id" id="expense_type_id" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach ($exptypes as $exptype)
                                        <option value="{{$exptype->id}}" {{ isset($expdata->expense_type_id) && $expdata->expense_type_id == $exptype->id?'selected':'' }}>{{$exptype->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('expense_type_id'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('expense_type_id')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Order Date</label>
                                <input name="ordered_date" id="ordered_date" type="date" class="form-control text-lowercase" required value="{{ isset($expdata->ordered_date)?$expdata->ordered_date:'' }}">
                                @if($errors->has('ordered_date'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('ordered_date')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Broker Charged</label>
                                <input name="broker_charged" id="broker_charged" type="date" class="form-control text-lowercase" value="{{ isset($expdata->broker_charged)?$expdata->broker_charged:'' }}">
                                @if($errors->has('broker_charged'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('broker_charged')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Broker Paid</label>
                                <input name="broker_paid" id="broker_paid" type="date" class="form-control text-lowercase"  value="{{ isset($expdata->broker_paid)?$expdata->broker_paid:'' }}">
                                @if($errors->has('broker_paid'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('broker_paid')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Base Cost</label>
                                <input name="base_cost" id="base_cost" type="text" class="form-control text-lowercase
number-input" required value="{{ isset($expdata->base_cost)?$expdata->base_cost:'' }}">
                                @if($errors->has('base_cost'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('base_cost')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Markup</label>
                                <input name="markup" id="markup" type="text" class="form-control number-input text-lowercase"
                                       required value="{{ isset($expdata->markup)?$expdata->markup:'' }}">
                                @if($errors->has('markup'))
                                    <div class="error"
                                         style="color:red">{{$errors->first('markup')}}</div>
                                @endif
                            </div>

                        </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label  class="form-label font-weight-bold">Broker Charge</label>
                                    <input name="broker_charge" id="broker_charge" type="text" class="form-control
                                    text-lowercase number-input" required value="{{ isset($expdata->broker_charge)?$expdata->broker_charge:'' }}">
                                    @if($errors->has('broker_charge'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('broker_charge')}}</div>
                                    @endif
                                </div>
                            </div>
                        <div class="col-sm-12">
                            <div class="position-relative form-group">
                                <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                                <textarea name="detail" id="detail" class="form-control">{{ isset($expdata->detail)
                                ?$expdata->detail:'' }}</textarea>
                            </div>
                        </div>
                        </div>
                        <button class="mt-1 btn btn-primary">Submit</button>
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
                            window.location="{{route('admin.brokerexp.list', encrypt($broker->id))}}"
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
