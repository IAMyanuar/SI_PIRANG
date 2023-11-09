<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/dashboard" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/kalender" aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span class="hide-menu">Kalender</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/accpeminjaman" aria-expanded="false"><i class="icon-check"></i><span class="hide-menu">ACC Peminjaman</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/laporan" aria-expanded="false"><i class="icon-notebook"></i><span class="hide-menu">Laporan</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/admin/DataRuangan" aria-expanded="false"><i class="far fa-building"></i><span class="hide-menu">Data Ruangan</span></a></li>
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
