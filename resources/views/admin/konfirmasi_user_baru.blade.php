@extends('layout.master1')

@section('title')
    SI PIRANG | Konfirmasi User
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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Data User</h3>
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
                                    </div>


                                    <div class="table-responsive table-bordered">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Nomer Telp</th>
                                                    <th>Foto BWP</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                    $no = 1;
                                                @endphp
                                                @if (empty($data))
                                                    <tr>
                                                        <td colspan="10" class="text-center"><strong>Tidak ada
                                                                akun user yang harus di konfirmasi</strong></td>
                                                    </tr>
                                                @endif
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $item['nama'] }}</td>
                                                        <td>{{ $item['email'] }}</td>
                                                        <td>{{ $item['telp'] }}</td>
                                                        <td><img src="{{ $item['foto_bwp'] }}" width="100"></td>
                                                        <td>
                                                            <form method="POST"
                                                                action="{{ route('update-status-user', ['id' => $item['id'], 'status_user' => 'terkonfirmasi']) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-success btn-rounded"
                                                                    data-toggle="tooltip" data-placement="left"
                                                                    title="" data-original-title="Setujui Akun"
                                                                    type="submit"><i class="fas fa-check"></i></button>
                                                            </form>
                                                            <form method="POST"
                                                                action="{{ route('update-status-user', ['id' => $item['id'], 'status_user' => 'tidak_dikonfirmasi']) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-danger btn-rounded"
                                                                    data-toggle="tooltip" data-placement="left"
                                                                    title="" data-original-title="Tolak Akun"
                                                                    type="submit"><i class="fas fa-times"></i></button>
                                                            </form>
                                                            {{-- <a class="btn btn-info btn-rounded" data-toggle="tooltip"
                                                                data-placement="left" title=""
                                                                data-original-title="Detail"
                                                                href="{{ url('/admin/..' . $item['id']) }}"><i
                                                                    class="fas fa-search-plus"></i></a> --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endsection
