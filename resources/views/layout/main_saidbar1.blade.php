<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/dashboard" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/konfirmasiuserbaru" aria-expanded="false"><i data-feather="user" class="feather-icon"></i><span class="hide-menu">Konirmasi User Baru</span><span
                    class="badge badge-primary notify-no rounded-circle">2</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/kalender" aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span class="hide-menu">Kalender</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/accpeminjaman" aria-expanded="false"><i class="icon-check"></i><span class="hide-menu">Konfirmasi Peminjaman</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/riwayat" aria-expanded="false"><i class="icon-notebook"></i><span class="hide-menu">Riwayat</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/DataRuangan" aria-expanded="false"><i class="far fa-building"></i><span class="hide-menu">Data Ruangan</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/DataFasilitas" aria-expanded="false"><i class="fas fa-box-open"></i><span class="hide-menu">Data Fasilitas</span></a></li>
                <form action="{{ route('logout') }}" method="post">
                @csrf
                <li class="sidebar-item"><button class="sidebar-link sidebar-link btn btn-no-outline" aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span class="hide-menu">Logout</span></button></li>
                </form>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
