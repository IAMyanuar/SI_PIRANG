@extends('layout.master2')

@section('title')
    SI PIRANG | RIWAYAT PEMIJAMAN
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Riwayat Peminjaman</h3>
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
                                @if (session()->has('info'))
                                    <div class="alert alert-warning">
                                        <strong>{{ session('info') }}</strong>
                                    </div>
                                @endif
                                @if (session()->has('success'))
                                    <div class="alert alert-success">
                                        <strong>{{ session('success') }}</strong>
                                    </div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('error') }}</strong>
                                    </div>
                                @endif
                                <div class="card-body">
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
                                                    <th>status</th>
                                                    <th>aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($empty))
                                                    <tr>
                                                        <td colspan="10" class="text-center"><strong>
                                                                {{ $empty }}
                                                            </strong></td>
                                                    </tr>
                                                @else
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($datariwayat as $item)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $item['nama_user'] }}</td>
                                                            <td>{{ $item['nama_lembaga'] }}</td>
                                                            <td>{{ $item['kegiatan'] }}</td>
                                                            <td class="text-center">
                                                                {{ date('d-m-Y', strtotime($item['tgl_mulai'])) }}
                                                                <br>jam:{{ date('H:i', strtotime($item['jam_mulai'])) }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ date('d-m-Y', strtotime($item['tgl_selesai'])) }}
                                                                <br>jam:
                                                                {{ date('H:i', strtotime($item['jam_selesai'])) }}
                                                            </td>
                                                            <td>{{ $item['status'] }}</td>
                                                            <td>
                                                                <a class="btn btn-info btn-rounded" data-toggle="tooltip"
                                                                    data-placement="left" title=""
                                                                    data-original-title="Detail"
                                                                    href="{{ url('/peminjaman/detail/' . $item['id']) }}"><i
                                                                        class="fas fa-search-plus"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endsection
