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
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Dashboard</h3>
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
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-3">
                    <div class="card-body mb-3 card border-right ">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                    {{ count($datapeminjaman) }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengajuan <br> Peminjaman
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="layers"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-3 card border-top">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamandisetujui }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Di Setujui
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="check-square"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-3 card border-top">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanditolak }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Di Tolak
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="far fa-window-close"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <h4 class="card-title text-center mt-5">Jadwal Peminjaman Ruangan</h4>
                        <div class="card-body b-l calender-sidebar">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <h4 class="text-truncate text-dark conten-center text-center">Daftar Ruangan</h4>
                <div class="card-deck mt-4">
                    @foreach ($dataruangan as $data)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img class="card-img-top img-fluid gambar-klik" src="{{ $data['foto'] }}" alt="Card image cap" data-nama="{{ $data['nama'] }}"
                                data-toggle="modal" data-target="#centermodal">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $data['nama'] }}</h4>
                                    <p class="card-text flex-grow-1">{{ $data['fasilitas'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalNama"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body center">
                                <img id="gambarModal" >
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalNama = document.getElementById('modalNama');
                const gambarModal = document.getElementById('gambarModal');
                const gambarKlik = document.querySelectorAll('.gambar-klik');

                gambarKlik.forEach(gambar => {
                    gambar.addEventListener('click', function () {
                        const src = this.src;
                        const nama = this.getAttribute('data-nama');

                        modalNama.textContent = nama;
                        gambarModal.src = src;
                    });
                });
            });
        </script>
    @endsection
