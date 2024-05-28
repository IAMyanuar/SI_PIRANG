<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Si PIRANG</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
    <link href="{{ asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/prism/prism.css') }}">
</head>

<body>
    <nav class="landingpage-nav">
        <div class="container_landing">
            <div class="nav_brand">
                <img src="{{ asset('assets/images/poliwangi.png') }}" alt="Logo SI PIRANG" />
                <h4>POLITEKNIK<br />NEGERI BANYUWANGI</h4>
            </div>
            {{-- <ul class="list_link">
                <li><a href="index.html">Home</a></li>
                <li><a href="tatacara.html">Tata Cara</a></li>
            </ul> --}}
            <a href="/login" class="btn_login">Login</a>
        </div>
    </nav>
    <main class="landingpage">
        <div class="container_landing">
            <section class="left">
                <img src="{{ asset('assets/images/gedung.png') }}" alt="Gedung" />
            </section>
            <section class="right">
                <h3>Sistem Peminjaman Ruangan</h3>
                <p>
                    Temukan kemudahan peminjaman ruang! Booking ruang jadi lebih mudah
                    dan menyenangkan dengan aplikasi kami
                </p>
                <a href="/dashboard" class="tombol">Ajukan</a>
                <a href="{{ url('assets/TATACARA PEMINJAMAN RUANGAN.pdf') }}">Tata Cara</a>
            </section>
        </div>
        {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path
          fill="#005A8D"
          fill-opacity="1"
          d="M0,128L60,117.3C120,107,240,85,360,74.7C480,64,600,64,720,69.3C840,75,960,85,1080,80C1200,75,1320,53,1380,42.7L1440,32L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"
        ></path>
      </svg> --}}
    </main>
    <div class="jadwal_peminjaman">
        <h2 class="text-truncate text-dark conten-center text-center font-weight-bold">Daftar Pengajuan</h2>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-10">
                {{-- <div class="card"> --}}
                <div class="card-body calender-sidebar">
                    <div id="calendar"></div>
                </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-10 mt-5">
            <h2 class="text-truncate text-dark conten-center text-center font-weight-bold">Daftar Ruangan</h2>
            <div class="card-deck mt-4">
                @foreach ($dataruangan as $data)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img class="card-img-top img-fluid gambar-klik" src="{{ $data['foto'] }}" alt="Card image cap"
                            data-nama="{{ $data['nama'] }}" data-toggle="modal" data-target="#centermodal">
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
                            <img id="gambarModal">
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



    @include('layout.footer_script')
</body>

</html>
