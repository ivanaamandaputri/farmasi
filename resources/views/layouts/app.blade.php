<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard SIFON</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('backend/dist/css/styles.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css">
    <script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>

    <style>
        .custom-card {
            background-color: #f8f9fa;
            /* Warna latar belakang soft */
            border-radius: 8px;
            /* Sudut membulat */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Efek bayangan */
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="#">SIFON</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-lg-0 me-lg-0 order-1 me-4" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar -->
        <ul class="navbar-nav ms-auto">
            <ul class="navbar-nav ms-md-0 me-lg-4 me-3 ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin keluar?')) { document.getElementById('logout-form').submit(); }">
                                Keluar
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </ul>

        <!-- Form Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading"></div>
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Menu</div>

                        <!-- Hanya tampil untuk Admin -->
                        @if (Auth::user()->level == 'admin')
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseMasterData" aria-expanded="false"
                                aria-controls="collapseMasterData">
                                <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                                Master Data
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseMasterData" aria-labelledby="headingMasterData"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ request()->is('user') ? 'active' : '' }}" href="/user">
                                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                        User
                                    </a>
                                    <a class="nav-link {{ request()->is('obat') ? 'active' : '' }}" href="/obat">
                                        <div class="sb-nav-link-icon"><i class="fas fa-pills"></i></div>
                                        Data Obat
                                    </a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseTransaksi" aria-expanded="false"
                                aria-controls="collapseTransaksi">
                                <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                                Transaksi
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseTransaksi" aria-labelledby="headingTransaksi"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ request()->is('pengajuan') ? 'active' : '' }}"
                                        href="{{ route('pengajuan.order') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                        Order Masuk
                                    </a>
                                </nav>
                            </div>


                            <a class="nav-link {{ request()->is('laporan') ? 'active' : '' }}" href="/laporan">
                                <div class="sb-nav-link-icon"><i class="fas fa-print"></i></div>
                                Laporan
                            </a>
                            <a class="nav-link" href="#"
                                onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin keluar?')) { document.getElementById('logout-form').submit(); }">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                    </div>
                </div>
                @endif

                <!-- Menu Transaksi untuk Operator -->
                @if (Auth::user()->level == 'operator')
                    <a class="nav-link {{ request()->is('obat') ? 'active' : '' }}" href="{{ route('obat.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-pills"></i></div>
                        Data Obat
                    </a>
                    <a class="nav-link {{ request()->is('transaksi') ? 'active' : '' }}" href="/transaksi">
                        <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                        Order Obat
                    </a>
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin keluar?')) { document.getElementById('logout-form').submit(); }">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                        Keluar
                    </a>
                @endif

                <div class="sb-sidenav-footer" style="color: white;">
                    <div class="small">Masuk Sebagai</div>
                    <span class="fw-bold" style="font-size: 20px">{{ Auth::user()->level }}</span>
                </div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    @yield('content')
                </div>
            </main>
            <footer class="bg-light mt-auto py-4">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Umpeg Dinkes 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 500); // Delay for complete fade out
                }, 3000); // 3000ms = 3 seconds
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('backend/dist/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/dist/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/dist/js/datatables-simple-demo.js') }}"></script>
</body>

</html>
