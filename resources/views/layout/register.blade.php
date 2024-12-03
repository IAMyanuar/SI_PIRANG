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
    <title>SI PIRANG | Daftar</title>
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
            style="no-repeat center center">
            <div class="col-lg-4 col-md-4 bg-white rounded-5 border-2 custom-shadow custom-radius">
                <div class="p-3">
                    <h3 class="mt-3 text-center text-dark font-weight-medium">Daftar</h3>
                    <p class="text-center">Masukkan data di bawah ini untuk Daftar</p>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Maaf!</strong> Terdapat kesalahan dengan inputan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">NIM/NIDN</label>
                                    <input class="form-control" name="nim" type="text"
                                        placeholder="Masukkan NIM/NIDN" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">Nama</label>
                                    <input class="form-control" name="nama" type="text"
                                        placeholder="Masukkan Nama" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">Email</label>
                                    <input class="form-control" name="email" type="email"
                                        placeholder="Masukkan Email" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">Kata Sandi</label>
                                    <input class="form-control" name="password" type="password"
                                        placeholder="Masukkan Kata Sandi" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">Telp</label>
                                    <input class="form-control" name="telp" type="telp"
                                        placeholder="Masukkan Telp" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark">Kartu Bukti Warga Poliwangi</label>
                                    <input class="form-control" name="foto_bwp" type="file" accept="image/*"
                                        placeholder="Masukkan Foto Bukti Warga Poliwangi" required>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center mt-5 mb-4">
                                <button class="btn btn-primary rounded-5 col-md-12" type="submit">Daftar</button>
                            </div>
                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    <strong>{{ session('error') }}</strong>
                                </div>
                            @endif
                            @if (session('success'))
                                <script>
                                    alert("{{ session('success') }}");
                                </script>
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
