@extends('layout.master2')

@section('title')
    dashboard
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Bukti Peminjaman</h3>
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
                                    <div class="form-actions">
                                        <div class="text-left">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Nama Peminjam</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>heru susanto</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">NIM</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>362258302024</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Program Studi</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>Teknologi Rekayasa Perangkat Lunak</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal Dan Waktu Mulai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>05/07/2008 22:33</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal Dan Waktu Selesai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>05/07/2008 23:33</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Kegiatan</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="">Rapat Tri Wulan UKM KWU</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Status</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-success font-weight-bold">ACC</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Bukti Pendukung Peminjaman</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <img src="{{ asset('assets/images/big/img1.jpg') }}" alt="">
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endsection
