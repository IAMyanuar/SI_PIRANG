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
                {{-- <div class="col-3"> --}}
                <div class="card-body mb-3 card border-right ">
                    <a href="/admin/DataRuangan">
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
                    </a>
                </div>
                <div class="card-body mb-3 card border-top">
                    <a href="/admin/accpeminjaman">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanSubmitted }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Belum
                                    Dikonfirmasi
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="clock"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="card-body mb-3 card border-top">
                    <a href="/admin/accpeminjaman">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanApprove }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Di Setujui
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="check-square"></i></span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- </div> --}}

                {{-- <div class="col-3"> --}}
                <div class="card-body mb-3 card border-top mb-2">
                    <a href="/admin/riwayat">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $peminjamanReject }}</h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pegajuan Di Tolak
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i class="far fa-window-close"></i></span>
                        </div>
                    </div>
                    </a>
                </div>
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
                {{-- </div> --}}


            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Grafik Pemakaian Ruangan</h4>
                    <div>
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <select id="year-select">
                            <!-- Tambahkan opsi untuk tahun saat ini -->
                            <option value="current">Tahun Ini</option>
                            <!-- Tambahkan opsi untuk tahun sebelumnya -->
                            <option value="previous">Tahun Sebelumnya</option>
                        </select>
                        <canvas id="line-chart" height="511" width="1422"
                            style="display: block; width: 1422px; height: 511px;" class="chartjs-render-monitor"></canvas>
                        <script>
                            $(function() {
                                "use strict";
                                var ctl = document.getElementById("line-chart").getContext('2d');
                                var labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                                    'Oktober', 'November', 'Desember'
                                ];

                                // Inisialisasi chart dengan data baru
                                var chart = new Chart(ctl, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: @php echo json_encode($grafiklineNew); @endphp,
                                    },
                                    options: {
                                        title: {
                                            display: true,
                                            text: 'Data Ruangan Yang Di Gunakan'
                                        },
                                        legend: {
                                            position: 'right',
                                        },
                                        tooltips: {
                                            callbacks: {
                                                label: function(tooltipItem, data) {
                                                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    label += tooltipItem.yLabel + ' dipindam';
                                                    return label;
                                                }
                                            }
                                        }
                                    }
                                });

                                // Tambahkan event listener untuk perubahan tahun
                                document.getElementById("year-select").addEventListener("change", function() {
                                    var selectedYear = this.value;
                                    updateChartData(chart, selectedYear);
                                });

                                function updateChartData(chart, selectedYear) {
                                    // Periksa tahun yang dipilih
                                    if (selectedYear === "previous") {
                                        // Mendapatkan data tahun sebelumnya
                                        chart.data.datasets = @php echo json_encode($grafiklineOld); @endphp;
                                    } else {
                                        // Mendapatkan data tahun baru
                                        chart.data.datasets = @php echo json_encode($grafiklineNew); @endphp;
                                    }

                                    chart.update(); // Perbarui grafik
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>


    @endsection
