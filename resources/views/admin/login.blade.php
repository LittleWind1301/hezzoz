<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <style>
        @import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
        .login-block{
            background: #DE6262;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to bottom, #FFB88C, #DE6262);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to bottom, #FFB88C, #DE6262); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            float:left;
            width:100%;
            padding : 200px 0;
            height: 800px;
        }

        .container{background:#fff; border-radius: 10px; box-shadow:15px 20px 0px rgba(0,0,0,0.1);}
        .carousel-inner{border-radius:0 10px 10px 0;}
        .carousel-caption{text-align:left; left:5%;}
        .login-sec{padding: 50px 30px; position:relative;}
        .login-sec .copy-text{position:absolute; width:80%; bottom:20px; font-size:13px; text-align:center;}
        .login-sec .copy-text i{color:#FEB58A;}
        .login-sec .copy-text a{color:#E36262;}
        .login-sec h2{margin-bottom:30px; font-weight:800; font-size:30px; color: #DE6262;}
        .login-sec h2:after{content:" "; width:100px; height:5px; background:#FEB58A; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
        .btn-login{background: #DE6262; color:#fff; font-weight:600;}
        .banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
        .banner-text h2{color:#fff; font-weight:600;}
        .banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
        .banner-text p{color:#fff;}
    </style>
</head>
<body>
<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">Login Now</h2>
                <form class="login-form" id="login-form"  action="{{route('login')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="">
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input name="remember_me" type="checkbox" class="form-check-input">
                            <small>Remember Me</small>
                        </label>
                        <button type="submit" class="btn btn-login float-right">Submit</button>
                    </div>
                    <br>
                    @include('admin.alert')
                </form>
                <div class="copy-text">Created with <i class="fa fa-heart"></i> by Grafreez</div>
            </div>
            <div class="col-md-8 banner-sec">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="{{asset('adminlte/dist/img/1.png')}}" alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>


{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport"--}}
{{--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--    <title>Document</title>--}}

{{--    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">--}}

{{--    <!------ Include the above in your HEAD tag ---------->--}}
{{--    <style>--}}
{{--        body {--}}
{{--            margin: 0;--}}
{{--            padding: 0;--}}
{{--            background-color: #17a2b8;--}}
{{--            height: 100vh;--}}
{{--        }--}}
{{--        #login .container #login-row #login-column #login-box {--}}
{{--            margin-top: 120px;--}}
{{--            max-width: 600px;--}}
{{--            height: 320px;--}}
{{--            border: 1px solid #9C9C9C;--}}
{{--            background-color: #EAEAEA;--}}
{{--        }--}}
{{--        #login .container #login-row #login-column #login-box #login-form {--}}
{{--            padding: 20px;--}}
{{--        }--}}
{{--        #login .container #login-row #login-column #login-box #login-form #register-link {--}}
{{--            margin-top: -85px;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="login">--}}
{{--    <h3 class="text-center text-white pt-5">Login form</h3>--}}
{{--    <div class="container">--}}
{{--        <div id="login-row" class="row justify-content-center align-items-center">--}}
{{--            <div id="login-column" class="col-md-6">--}}
{{--                <div id="login-box" class="col-md-12">--}}
{{--                    --}}
{{--                    <form id="login-form" class="form" action="{{route('admin.login')}}" method="post">--}}
{{--                        @csrf--}}
{{--                        <h3 class="text-center text-info">Login</h3>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="email" class="text-info">Email:</label><br>--}}
{{--                            <input type="text" name="email" id="email" class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="password" class="text-info">Password:</label><br>--}}
{{--                            <input type="password" name="password" id="password" class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="remember-me" class="text-info"><span>Remember me</span> <span><input--}}
{{--                                        id="remember-me" name="remember-me" type="checkbox"></span></label><br>--}}
{{--                            <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">--}}
{{--                        </div>--}}
{{--                        @include('admin.alert')--}}

{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--</body>--}}

{{--<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
{{--</html>--}}


