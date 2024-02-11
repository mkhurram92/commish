<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Commish | Forgot Password</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="Commish">
    <!-- Disable tap highlight on IE -->
    <meta name="Commish" content="no">
    <link rel="stylesheet" href="{{asset('front-assets/vendors/@fortawesome/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('front-assets/vendors/ionicons-npm/css/ionicons.css')}}">
    <link rel="stylesheet" href="{{asset('front-assets/vendors/linearicons-master/dist/web-font/style.css')}}">
    <link rel="stylesheet"
          href="{{asset('front-assets/vendors/pixeden-stroke-7-icon-master/pe-icon-7-stroke/dist/pe-icon-7-stroke.css')}}">
    <link href="{{asset('front-assets/styles/css/base.css')}}" rel="stylesheet">
</head>
<style>
    .notify-popup {
        position: absolute;
        right: 0;
        padding: 10px 30px 10px 10px;
        border-radius: 7px;
        top: 35px;
        z-index: 1;
    }
</style>
<body>
<div class="app-container app-theme-white body-tabs-shadow">
    @if ($message = Session::get('success'))
        <div class="alert notify-popup alert-success mb-2" id="alert-success-message" role="alert">
            <strong>Success! </strong> {{$message}}
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert notify-popup alert-danger mb-2" id="alert-error-message" role="alert">
            <strong>Error! </strong> {{$message}}
        </div>
    @endif
    <div class="app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-6">
                    <div class="app-logo-inverse mx-auto mb-3"></div>
                    <div class="modal-dialog w-100">
                        <div class="modal-content">
                            <form class="" method="post" action="{{route('reset.password.change', encrypt($user->id))}}">
                                @csrf
                                <div class="modal-header">
                                    <div class="h5 modal-title">
                                        Forgot your Password?
                                        <h6 class="mt-1 mb-0 opacity-8">
                                            <span>Use  the form below to recover it.</span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="exampleEmail" class="">Email</label>
                                                    <input name="password" id="exampleEmail"
                                                           placeholder="Password here..." type="password"
                                                           class="form-control">
                                                    @if($errors->has('password'))
                                                        <div class="error"
                                                             style="color:red">{{$errors->first('password')}}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="exampleEmail" class="">Email</label>
                                                    <input name="retype_password" id="exampleEmail"
                                                           placeholder="Re type password here..." type="password"
                                                           class="form-control">
                                                    @if($errors->has('retype_password'))
                                                        <div class="error"
                                                             style="color:red">{{$errors->first('retype_password')}}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <h6 class="mb-0">
                                        <a href="{{route('login')}}" class="text-primary">Sign in existing account</a>
                                    </h6>
                                </div>
                                <div class="modal-footer clearfix">
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
{{--                    <div class="text-center text-white opacity-8 mt-3">--}}
{{--                        Copyright © Commish {{\Illuminate\Support\Carbon::now()->format('Y')}}--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@if ($message = Session::get('success'))
    <script>
        setTimeout(function () {
            document.getElementById('alert-success-message').style.display = 'none'
        }, 3000);
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        setTimeout(function () {
            document.getElementById('alert-error-message').style.display = 'none'
        }, 3000);
    </script>
@endif
</body>
</html>
