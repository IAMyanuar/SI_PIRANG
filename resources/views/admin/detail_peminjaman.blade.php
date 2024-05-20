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
                                                        <h3>{{ $datapeminjam[0]['nama_user'] }}</h3>
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
                                                        <h3>{{ $datapeminjam[0]['nim'] }}</h3>
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
                                                        <h3>{{ $datapeminjam[0]['telp'] }}</h3>
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
                                                        <h3>{{ $datapeminjam[0]['email'] }}</h3>
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
                                                        <h3>{{ $datapeminjam[0]['nama_lembaga'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal dan Waktu Mulai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ date('d-m-Y', strtotime($datapeminjam[0]['tgl_mulai'])) }} | {{ date('H:i', strtotime($datapeminjam[0]['jam_mulai'])) }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Tanggal dan Waktu Selesai</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3>{{ date('d-m-Y', strtotime($datapeminjam[0]['tgl_selesai'])) }} | {{ date('H:i', strtotime($datapeminjam[0]['jam_selesai'])) }}</h3>
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
                                                        <h3 class="">{{ $datapeminjam[0]['kegiatan'] }}</h3>
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
                                                        <h3 class="">{{ $datapeminjam[0]['nama_ruangan'] }}</h3>
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
                                                        <h3 class="">{{ $datapeminjam[0]['nama_ruangan'] }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Fasilitas</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    @foreach ($datapeminjam[1] as $item)
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="">{{ $item['nama'] }}&nbsp;</h3>
                                                        <h3 class="">{{ $item['jumlah']}},&nbsp;</h3>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Status</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    @if ($datapeminjam[0]['status']=='terkirim')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-secondary font-weight-bold">{{ $datapeminjam[0]['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam[0]['status']=='disetujui')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-success font-weight-bold">{{ $datapeminjam[0]['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam[0]['status']=='ditolak')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-danger font-weight-bold">{{ $datapeminjam[0]['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam[0]['status']=='di prosess')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-warning font-weight-bold">{{ $datapeminjam[0]['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                    @if ($datapeminjam[0]['status']=='selesai')
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="text-info font-weight-bold">{{ $datapeminjam[0]['status'] }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                @if (!empty($datapeminjam[0]['feedback']))
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Feedback</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <h3 class="">{{ $datapeminjam[0]['feedback'] }}</h3>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h3 class="card-title">Bukti Pendukung / Ijin Kegiatan</h3>
                                                    </div>
                                                    <div class="row col-sm-1">
                                                        <h3 class="card-title">:</h3>
                                                    </div>
                                                    <div class="row col-auto mb-3">
                                                        <img src="{{ $datapeminjam[0]['dokumen_pendukung'] }}" width="600">
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endsection
