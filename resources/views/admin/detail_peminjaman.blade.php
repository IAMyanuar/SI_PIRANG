@extends('layout.master1')

@section('title')
    SI PIRANG | Detail Peminjaman
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Detail Peminjaman</h3>
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
                                                        <h3>{{ $datapeminjam['nama_user'] }}</h3>
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
                                                        <h3>{{ $datapeminjam['nim'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">NO.TELP</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ $datapeminjam['telp'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Email</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ $datapeminjam['email'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Nama Lembaga</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ $datapeminjam['nama_lembaga'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal Dan waktu mulai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ date('d-m-Y', strtotime($datapeminjam['tgl_mulai'])) }} | {{ date('H:i', strtotime($datapeminjam['jam_mulai'])) }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal Dan waktu selesai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ date('d-m-Y', strtotime($datapeminjam['tgl_selesai'])) }} | {{ date('H:i', strtotime($datapeminjam['jam_selesai'])) }}</h3>
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
                                                        <h3 class="">{{ $datapeminjam['kegiatan'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Ruangan</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="">{{ $datapeminjam['nama_ruangan'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Status</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    @if ($datapeminjam['status']=='submitted')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-secondary font-weight-bold">{{ $datapeminjam['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam['status']=='approved')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-success font-weight-bold">{{ $datapeminjam['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam['status']=='reject')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-danger font-weight-bold">{{ $datapeminjam['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam['status']=='in progress')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-warning font-weight-bold">{{ $datapeminjam['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam['status']=='completed')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-info font-weight-bold">{{ $datapeminjam['status'] }}</h3>
                                                    </div>
                                                    @endif

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Bukti Pendukung Peminjaman</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <img src="{{ asset('assets/images/bukti_pendukung/' . $datapeminjam['dokumen_pendukung']) }}" width="600">
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endsection
