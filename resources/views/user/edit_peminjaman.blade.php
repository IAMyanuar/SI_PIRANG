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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Peminjaman</h3>
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
                                @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    <strong>{{ session('error') }}</strong>
                                </div>
                            @endif
                                <div class="card-body">
                                    <form method="POST" action="{{ route('ubah_pengajuan',$datapeminjam['id']) }}"  enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label>Nama Lembaga</label>
                                            <input type="text" class="form-control" name="nama_lembaga" value="{{ $datapeminjam['nama_lembaga'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Kegiatan</label>
                                            <input type="text" class="form-control" name="kegiatan" value="{{ $datapeminjam['kegiatan'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Ruangan</label>
                                            <select class="form-control" name="id_ruangan" required>
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($data as $dataruangan)
                                                    <option value="{{ $dataruangan['id'] }}" {{ $datapeminjam['id_ruangan'] == $dataruangan['id'] ? 'selected' : '' }}>
                                                        {{ $dataruangan['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Waktu Mulai</label>
                                            <input type="datetime-local" class="form-control" name="tgl_mulai" value="{{ date('Y-m-d\TH:i', strtotime($datapeminjam['tgl_mulai'] . ' ' . $datapeminjam['jam_mulai'])) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Waktu Selesai</label>
                                            <input type="datetime-local" class="form-control" name="tgl_selesai" value="{{ date('Y-m-d\TH:i', strtotime($datapeminjam['tgl_selesai'] . ' ' . $datapeminjam['jam_selesai'])) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Bukti Pendukung</label>
                                            <input type="file" name="dokumen_pendukung" class="form-control" >
                                        </div>
                                        @if ($datapeminjam['dokumen_pendukung'] != null)
                                        <div class="form-group">
                                            <label>Bukti Pendukung: </label>
                                            <img src="{{asset('assets/images/bukti_pendukung/' . $datapeminjam['dokumen_pendukung']) }}" width="600">
                                        </div>
                                        @endif
                                        <button class="btn-primary btn" type="submit">Simpan</button>
                                    </form>
                                </div>
                            @endsection
