<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Qassir_IN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
            position: sticky;
            top: 0;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #343a40;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        .content-area {
            padding: 20px;
        }

        .menu-card {
            transition: all 0.3s;
        }

        .menu-card:hover {
            transform: scale(1.02);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>

@php
    // Halaman yang dianggap milik pelanggan (tanpa sidebar)
    $isPelangganPage = request()->is('selforder') ||
                       request()->is('selforder/konfirmasi/*') ||
                       request()->is('selforder/invoice/*');
@endphp

<div class="container-fluid">
    <div class="row flex-nowrap">
        {{-- Tampilkan sidebar hanya jika bukan halaman pelanggan --}}
        @unless ($isPelangganPage)
            <nav class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3">
                    <a href="/" class="d-flex align-items-center pb-3 mb-3 text-decoration-none">
                        <span class="fs-5 fw-bold text-dark">Qassir_IN</span>
                    </a>

                    <ul class="nav nav-pills flex-column mb-auto w-100">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i data-lucide="home"></i> <span class="ms-2">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                                <i data-lucide="shopping-cart"></i> <span class="ms-2">Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('menu.index') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                                <i data-lucide="package"></i> <span class="ms-2">Kelola Produk</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('riwayat') }}" class="nav-link {{ request()->routeIs('riwayat') ? 'active' : '' }}">
                                <i data-lucide="history"></i> <span class="ms-2">Riwayat</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.selforders') }}" class="nav-link {{ request()->routeIs('admin.selforders*') ? 'active' : '' }}">
                                <i data-lucide="clipboard-list"></i> <span class="ms-2">Self Order Masuk</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.qrcode') }}" class="nav-link {{ request()->routeIs('admin.qrcode') ? 'active' : '' }}">
                                <i data-lucide="qr-code"></i> <span class="ms-2">QR Code</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        @endunless

        <!-- Konten Utama -->
        <main class="col py-3 content-area">
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap JS & Lucide Init -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();
</script>

@stack('scripts')
</body>
</html>
