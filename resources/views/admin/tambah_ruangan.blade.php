@extends('layout.master1')

@section('title')
    SI PIRANG | Tambah Ruangan
@stop

@section('css')

@stop

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Ruangan</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href=""> </a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <p><span class="form-control bg-white border-0 custom-shadow custom-radius"id="tanggalwaktu"></span>
                        </p>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
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
                                    @if (session()->has('RuanganIsExist'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('RuanganIsExist') }}</strong>
                                    </div>
                                @endif
                                    <form method="post" action="{{ route('tambah_ruangan') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Ruangan</label>
                                            <input type="text" class="form-control" name="nama">
                                        </div>
                                        <div class="form-group">
                                            <label>Fasilitas</label>
                                            <input type="text" class="form-control" name="fasilitas">
                                        </div>
                                        <div class="form-group">
                                            <label>Foto Ruangan</label>
                                            <input type="file" class="form-control" name="foto" accept="image/*">
                                        </div>
                                        <button class="btn-primary btn" type="submit">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection
