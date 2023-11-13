@extends('layout.master1')

@section('title')
SI PIRANG | Data Ruangan
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Data Ruangan</h3>
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
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-actions">
                                        <div class="text-right  mb-3">
                                            <a href="/DataRuangan/TambahRuangan" type="button"
                                                class="btn btn-outline-primary btn-rounded"><i class="icon-plus"></i> Tambah
                                                Ruangan</a>
                                        </div>
                                    </div>


                                    <div class="table-responsive table-bordered">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>no</th>
                                                    <th>Nama Ruangan</th>
                                                    <th>Fasilitas</th>
                                                    <th>foto</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $item['nama'] }}</td>
                                                        <td>{{ $item['fasilitas'] }}</td>
                                                        <td><img src="{{ asset('assets/images/ruangan/' . $item['foto']) }}"
                                                                width="100"></td>
                                                        <td>
                                                            <a href="{{ url('/DataRuangan/UbahRuangan/' . $item['id']) }}"
                                                                class="btn btn-rounded btn-warning text-white">Ubah</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endsection
