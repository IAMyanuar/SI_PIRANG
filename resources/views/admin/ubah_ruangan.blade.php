@extends('layout.master1')

@section('title')
    SI PIRANG | Ubah Ruangan
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Ubah Ruangan</h3>
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
                                            <label>Nama Ruangan</label>
                                            <input type="text" class="form-control" name="nama" value="{{  $data['nama'] }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Fasilitas</label>
                                            <input type="text" class="form-control" name="fasilitas" value="{{  $data['fasilitas'] }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Foto Ruangan:</label><br>
                                            <img src="{{ asset('assets/images/ruangan/' . $data['foto']) }}" alt="" width="500px">
                                        </div>
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="foto">
                                        </div>
                                        <button class="btn-primary btn" >Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection
