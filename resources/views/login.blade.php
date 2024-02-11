<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>

    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta content="Commish " name="description">
    <meta content="Mirza" name="author">
    <meta name="keywords" content="Commi$h"/>

    <!-- Title -->
    <title>Commish | Login</title>

    <!--Favicon -->
    <link rel="icon" href="{{url('assets/images/commish-favicon.png')}}" type="image/png"/>

    <!-- Bootstrap css -->
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />

    <!-- Style css -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />

    <!-- Dark css -->
    <link href="{{asset('assets/css/dark.css')}}" rel="stylesheet" />

    <!-- Skins css -->
    <link href="{{asset('assets/css/skins.css')}}" rel="stylesheet" />

    <!-- Animate css -->
    <link href="{{asset('assets/css/animated.css')}}" rel="stylesheet" />

    <!---Icons css-->
    <link href="{{asset('assets/plugins/web-fonts/icons.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/web-fonts/font-awesome/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/web-fonts/plugin.css')}}" rel="stylesheet" />

</head>

<body class="h-100vh page-style1 light-mode default-sidebar">
<div class="page">
    <div class="page-single">
        <div class="p-5">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-lg-9 col-xl-8">
                            <div class="card-group mb-0">
                                <div class="card p-4 page-content">
                                    <div class="card-body page-single-content" style="margin-top: 0;padding-top: 0">
                                        <div class="w-100">
                                           <!--<div class="text-center">
                                               <img src="{{url('assets/images/logo-commish.png')}}" class="header-brand-img desktop-lgo" alt="Commish  logo">
                                           </div>-->
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
                                            <div class="">
                                                <center>
                                                    <h1 class="mb-2">Login</h1>
                                                    <h6 class="text-muted">Sign In to your account</h6><br />
                                                </center>
                                            </div>
                                                <form method="post" action="{{route('auth.login')}}">
                                                    @csrf

                                            <div class="input-group mb-3">
                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z" opacity=".3"/><circle cx="12" cy="8" opacity=".3" r="2"/><path d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/></svg></span>
                                                <input name="username" id="exampleEmail" required
                                                       placeholder="Username here..." type="text" class="form-control">
                                                @if($errors->has('username'))
                                                    <div class="error"
                                                         style="color:red">{{$errors->first('username')}}</div>
                                                @endif
                                            </div>
                                            <div class="input-group mb-4">
                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><g fill="none"><path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/></g><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg></span>
                                                <input name="password" id="examplePassword" required
                                                       placeholder="Password here..." type="password"
                                                       class="form-control">
                                                @if($errors->has('password'))
                                                    <div class="error"
                                                         style="color:red">{{$errors->first('password')}}</div>
                                                @endif
                                            </div>
                                            <div class="input-group mb-4">
                                                <span class="input-group-addon">
                                                <svg class="svg-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24">
                                                    <g fill="none">
                                                        <path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/>
                                                    </g>
                                                    <path d="M14 2.5c0 0.828-2.686 1.5-6 1.5s-6-0.672-6-1.5c0-0.828 2.686-1.5 6-1.5s6 0.672 6 1.5z"></path>
                                                    <path d="M8 5c-3.3 0-6-0.7-6-1.5v3c0 0.8 2.7 1.5 6 1.5s6-0.7 6-1.5v-3c0 0.8-2.7 1.5-6 1.5z"></path>
                                                    <path d="M8 9c-3.3 0-6-0.7-6-1.5v3c0 0.8 2.7 1.5 6 1.5s6-0.7 6-1.5v-3c0 0.8-2.7 1.5-6 1.5z"></path>
                                                    <path d="M8 13c-3.3 0-6-0.7-6-1.5v3c0 0.8 2.7 1.5 6 1.5s6-0.7 6-1.5v-3c0 0.8-2.7 1.5-6 1.5z"></path>
                                                </svg>
                                                </span>
                                                    
                                                <select name="db" id="db" class="form-control">
                                                	<option value="newdata" selected>Active Data</option>
                                                    <option value="olddata">Inactive Data</option>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-lg btn-primary btn-block"><i
                                                            class="fe fe-arrow-right"></i> Login</button>
                                                </div>

                                            </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card text-white bg-primary py-5 d-md-down-none page-content mt-0">
                                    <div class="card-body text-center justify-content-center page-single-content">
                                        <img src="{{asset('assets/images/pattern/login.png')}}" alt="img">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jquery js-->
<script src="{{asset('assets/js/vendors/jquery-3.5.1.min.js')}}"></script>

<!-- Bootstrap4 js-->
<script src="{{asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!--Othercharts js-->
<script src="{{asset('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

<!-- Circle-progress js-->
<script src="{{asset('assets/js/vendors/circle-progress.min.js')}}"></script>

<!-- Jquery-rating js-->
<script src="{{asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>

</body>
</html>
