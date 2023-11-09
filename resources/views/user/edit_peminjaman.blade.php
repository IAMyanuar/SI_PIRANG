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
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Ruangan</label>
                                            <select class="form-control" name="id_ruangan">
                                            <option value="">Pilih Ruangan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="nama">
                                        </div>
                                        <div class="form-group">
                                            <label>Program Studi</label>
                                            <input type="text" class="form-control" name="prodi">
                                        </div>
                                        <div class="form-group">
                                            <label>Nama kegiatan</label>
                                            <input type="text" class="form-control" name="kegiatan">
                                        </div>
                                        <div class="form-group">
                                            <label>Waktu Mulai</label>
                                            <input type="datetime-local" class="form-control" name="mulai">
                                        </div>
                                        <div class="form-group">
                                            <label>Waktu Selesai</label>
                                            <input type="datetime-local" class="form-control" name="selesai">
                                        </div>
                                        <div class="form-group">
                                            <label>Bukti Pendukung</label>
                                            <input type="file" name="bukti" class="form-control">
                                        </div>
                                        <button class="btn-primary btn" name="submit">Simpan</button>
                                    </form>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
