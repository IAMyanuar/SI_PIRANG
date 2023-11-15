<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/dashboard" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dasboard</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/kalender" aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span class="hide-menu">Kalender</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/PengajuanPeminjaman"aria-expanded="false"><i class="far fa-paper-plane"></i><span class="hide-menu">Pengajuan Peminjaman</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/riwayat"aria-expanded="false"><i class="icon-notebook"></i><span class="hide-menu">Riwayat Peminjaman</span></a></li>
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
