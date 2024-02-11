@extends('layout.main')
@section('title')
    Contact Search
@endsection

{{--@section('page_title')--}}
{{--    Dashboard--}}
{{--@endsection--}}
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Contact Search</h5>
                    <form method="post" action="{{route('admin.setting.profile.edit')}}">
                        @csrf
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Search For</label>
                            <select name="search_for" id=""  class="form-control">
                                <option selected disabled>Choose One</option>
                                <option>Broker</option>
                                <option>Broker Staff</option>
                                <option>Client</option>
                                <option>Referror</option>
                            </select>
                            @if($errors->has('search_for'))
                                <div class="error"
                                     style="color:red">{{$errors->first('search_for')}}</div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">Surname/Entity</label>
                                    <input name="first_name" value=""
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">First Name</label>
                                    <input name="first_name" value=""
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Trading/Bus</label>
                            <input name="email" value="" type="email"
                                   class="form-control">
                            @if($errors->has('email'))
                                <div class="error"
                                     style="color:red">{{$errors->first('email')}}</div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">Address</label>
                                    <input name="first_name" value=""
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">City</label>
                                    <input name="first_name" value=""
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">State</label>
                                    <select name="state" id="" class="form-control">
                                        <option selected disabled>Choose One</option>
                                        <option value="">SA</option>
                                        <option value="">ACT</option>
                                        <option value="">NSW</option>
                                        <option value="">NT</option>
                                        <option value="">QLD</option>
                                        <option value="">TAS</option>
                                        <option value="">VIC</option>
                                        <option value="">WA</option>
                                    </select>
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">ZIP</label>
                                    <input name="first_name" value=""
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">ID</label>
                            <input name="first_name" value=""
                                   type="text" class="form-control">
                            @if($errors->has('search_for'))
                                <div class="error"
                                     style="color:red">{{$errors->first('search_for')}}</div>
                            @endif
                        </div>
                        <button type="submit" class="mt-1 btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
