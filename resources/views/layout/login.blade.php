<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <title>SI PIRANG | Login</title>
    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background: no-repeat center center;">
                <div class="col-lg-4 col-md-4 bg-white rounded-5 border-2 custom-shadow custom-radius">
                    <div class="p-3">
                        <h3 class="mt-3 text-center text-dark font-weight-medium">Sign In</h3>
                        <p class="text-center">Insert Your Username And Password.</p>
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="icon-user" > </label><label class="text-dark"> Username</label>
                                        <input class="form-control" name="nim" type="text"
                                            placeholder="Input Your Username">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="icon-lock"></label><label class="text-dark"> Password</label>
                                        <input class="form-control" name="password" type="password"
                                            placeholder="Input Your Password" >
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button name="submit" class="btn btn-block btn-primary">Sign In</button>
                                </div>
                                <div class="col-lg-12 text-center mt-4">
                                    Don't Have Account? <a href="{{ url('/daftar') }}" class="text-danger">Sign Up</a>
                                </div>
                                @if ($errors->has('error'))
                                <div class="alert alert-danger col-lg-12 text-center mt-2">
                                    <strong>{{ $errors->first('error') }}</strong>
                                </div>
                            @endif
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }} "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }} "></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader ").fadeOut();
    </script>
</body>
</html>
