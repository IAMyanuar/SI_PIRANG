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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Pengajuan Peminjaman</h3>
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
                        <p><span class="form-control bg-white border-0 custom-shadow custom-radius"id="tanggalwaktu"></span></p>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-actions">
                                        <div class="text-right mb-3">
                                            <a class="btn btn-success btn-rounded" href="/AjukanPeminjaman">ajukan peminjaman (+)</a>
                                        </div>
                                    </div>


                                    <div class="table-responsive table-bordered">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>no</th>
                                                    <th>Nama Peminjam</th>
                                                    <th>Program Studi</th>
                                                    <th>Nama Kegiatan</th>
                                                    <th>Waktu Mulai</th>
                                                    <th>Waktu Selesai</th>
                                                    <th>Pendukung Peminjaman</th>
                                                    <th>status</th>
                                                    <th>aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>heru</td>
                                                    <td>teknologi rekayasa</td>
                                                    <td>rapat triwulan KWU</td>
                                                    <td>05/07/2008 10:33 PM</td>
                                                    <td>05/07/2008 11:33 PM</td>
                                                    <td><button class=" btn btn-circle btn-success"><i class="fa fa-link"></button></td>
                                                    <td>pending</td>
                                                    <td>
                                                        <a href="/EditPeminjaman" class="btn btn-warning btn-rounded">Edit</a>
                                                        <a href="" class="btn btn-danger btn-rounded">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>heru</td>
                                                    <td>teknologi rekayasa</td>
                                                    <td>rapat triwulan Geni</td>
                                                    <td>07/07/2008 10:33 PM</td>
                                                    <td>07/07/2008 11:33 PM</td>
                                                    <td><button class=" btn btn-circle btn-success"><i class="fa fa-link"></button></td>
                                                    <td>ACC</td>
                                                    <td>
                                                        <a class="btn btn-primary btn-rounded" href="/BuktiPeminjaman">Bukti Peminjaman</a>
                                                    </td>
                                                </tr>





                                                {{-- <?php $nomor = 1; ?>
                                                <?php
                                                $ambil = $conn->query('SELECT * FROM produk LEFT JOIN kategori ON produk.id_kategori=kategori.id_kategori'); ?>
                                                <?php while($pecah= $ambil->fetch_assoc()){ ?>
                                                <tr>
                                                    <td><?php echo $nomor; ?></td>
                                                    <td><?php echo $pecah['nama_kategori']; ?></td>
                                                    <td><?php echo $pecah['nama_produk']; ?></td>
                                                    <td><?php echo $pecah['stok_produk']; ?></td>
                                                    <td>Rp<?php echo number_format($pecah['harga_produk']); ?></td>
                                                    <td><?php echo $pecah['berat']; ?> Gram</td>
                                                    <td>
                                                        <img src="../assets/images/foto_produk/<?php echo $pecah['foto_produk']; ?>"
                                                            width="100">

                                                    </td>
                                                    <td><?php echo $pecah['deskripsi_produk']; ?>
                                                    <td>
                                                        <a href="index.php?halaman=hapusproduk&id=<?php echo $pecah['id_produk']; ?>"
                                                            class="btn btn-rounded btn-danger">hapus</a><a
                                                            href="index.php?halaman=ubahproduk&id=<?php echo $pecah['id_produk']; ?>"
                                                            class="btn btn-rounded btn-warning text-white">ubah</a>
                                                    </td>
                                                </tr>
                                                <?php $nomor++; ?>
                                                <?php } ?> --}}
                                            </tbody>
                                        </table>
                                    </div>
@endsection
