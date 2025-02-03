@extends('layout.master1')

@section('title')
    SI PIRANG | Ubah Fasilitas
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Ubah Fasilitas</h3>
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
                                    @if (session()->has('RuanganIsExist'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('RuanganIsExist') }}</strong>
                                    </div>
                                    @endif
                                    <form method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Nama Fasilitas</label>
                                            <input type="text" class="form-control" name="nama" value="{{  $data['nama'] }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Foto:</label><br>
                                            <img src="{{ $data['foto'] }}" alt="" width="250px">
                                        </div>
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="foto">
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input type="text" class="form-control" name="jumlah" value="{{  $data['jumlah'] }}">
                                        </div>
                                        <button class="btn-primary btn">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection
