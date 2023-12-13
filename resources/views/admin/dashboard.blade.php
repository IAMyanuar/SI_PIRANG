@extends('layout.master1')

@section('title')
    SI PIRANG | Dashboard
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
                        <p><span class="form-control bg-white border-0 custom-shadow custom-radius"
                                id="tanggalwaktu"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- *************************************************************** -->
            <!-- Start First Cards -->
            <!-- *************************************************************** -->
            <div class="row">
                <div class="col-3">
                    <div class="card-body mb-3 card border-right ">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ count($ruangan) }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Jumlah Ruangan
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="home"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-3 card border-top">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanApprove }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Di Setujui
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="check-square"></i></span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-3">
                    <div class="card-body mb-3 card border-right">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $peminjamanTKF }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengajuan</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="layers"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card border-top mb-2">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanReject }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pegajuan Di Tolak
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="far fa-window-close" ></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Grafik Ruangan</h4>
                            <div>
                                <canvas id="bar-chart" height="150"></canvas>
                                <script>
                                    $(function() {
                                        "use strict";

                                        var ruangan = @php echo json_encode($ruangan); @endphp;
                                        var dataBulanIni = @php echo json_encode($dataBulanIni); @endphp;
                                        var dataBulanSebelumnya = @php echo json_encode($dataBulanSebelumnya); @endphp;
                                        var colors1 = @php echo json_encode($colors1); @endphp;
                                        var colors2 = @php echo json_encode($colors2); @endphp;
                                        var ctx = document.getElementById("bar-chart").getContext('2d');

                                        // Bar chart
                                        new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: ruangan,
                                                datasets: [
                                                    {
                                                        label: "Peminjaman Bulan Sebelumnya",
                                                        backgroundColor: colors1,
                                                        data: dataBulanSebelumnya
                                                    },
                                                    {
                                                        label: "Peminjaman Bulan Ini",
                                                        backgroundColor: colors2,
                                                        data: dataBulanIni
                                                    }
                                                ]
                                            },
                                            options: {
                                                legend: {
                                                    display: true
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Data Ruangan Yang Di Gunakan Perbulannya'
                                                }
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                </div>


                {{-- <div class="card border-right ml-2 col-3"> --}}

                {{-- </div> --}}


        </div>


    @endsection
