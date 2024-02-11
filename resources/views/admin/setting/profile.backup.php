@extends('layout.main')
@section('title')
    Profile
@endsection
@section('page_title_con')

    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Edit Profile
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">                             Edit Profile
                </li>
            </ol>
        </div>
    </div>
@endsection
{{--@section('page_title')--}}
{{--    Dashboard--}}
{{--@endsection--}}
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Edit Profile</h5>
                    <form method="post" action="{{route('admin.setting.profile.edit')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">First Name</label>
                                    <input name="first_name" value="{{auth()->user()->fname}}"
                                           type="text" class="form-control">
                                    @if($errors->has('first_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('first_name')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">Last Name</label>
                                    <input name="last_name" value="{{auth()->user()->lname}}"
                                           type="text" class="form-control">
                                    @if($errors->has('last_name'))
                                        <div class="error"
                                             style="color:red">{{$errors->first('last_name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Email</label>
                            <input name="email" value="{{auth()->user()->email}}" type="email"
                                   class="form-control">
                            @if($errors->has('email'))
                                <div class="error"
                                     style="color:red">{{$errors->first('email')}}</div>
                            @endif
                        </div>
                        <div class="position-relative form-group">
                            <label for="image" class="">Profile Image</label><br>
                            <input name="image" type="file">
                            @if($errors->has('image'))
                                <div class="error"
                                     style="color:red">{{$errors->first('image')}}</div>
                            @endif
                        </div>
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Username</label>
                            <input name="username" value="{{auth()->user()->username}}" type="text"
                                   class="form-control">
                            @if($errors->has('username'))
                                <div class="error"
                                     style="color:red">{{$errors->first('username')}}</div>
                            @endif
                        </div>


                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">Password</label>
                            <input name="password" placeholder="******" 
                                   type="password" class="form-control">
                            @if($errors->has('password'))
                                <div class="error"
                                     style="color:red">{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">Retype Password</label>
                            <input name="retype_password" placeholder="******"
                                   type="password" class="form-control">
                            @if($errors->has('retype_password'))
                                <div class="error"
                                     style="color:red">{{$errors->first('retype_password')}}</div>
                            @endif
                        </div>
                        <button type="submit" class="mt-1 btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
