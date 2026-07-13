<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sistem Penjualan Bakso Woow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --page-bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text-main: #17202a;
            --text-muted: #667085;
            --line: #e6ebf2;
            --brand: #f97316;
            --brand-dark: #c2410c;
            --nav: #111827;
            --sidebar-width: 270px;
            --radius: 8px;
            --shadow-sm: 0 12px 30px rgba(15, 23, 42, 0.08);
            --shadow-md: 0 18px 45px rgba(15, 23, 42, 0.12);
        }

        * {
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            background: var(--page-bg);
            color: var(--text-main);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            padding: 1.15rem 1rem;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent 32%),
                var(--nav);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-height: 60px;
            padding: 0.25rem 0.35rem 1.1rem;
            color: #fff;
            text-decoration: none;
            font-size: 1.22rem;
            font-weight: 850;
        }

        .brand-mark {
            display: inline-grid;
            width: 48px;
            height: 48px;
            place-items: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #fb923c, #ef4444);
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.28);
        }

        .sidebar-section-label {
            margin: 0.95rem 0 0.55rem;
            color: rgba(255, 255, 255, 0.48);
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            overflow-y: auto;
            padding-right: 0.15rem;
            scrollbar-width: thin;
        }

        .nav-pill {
            display: flex;
            align-items: center;
            gap: 0.78rem;
            min-height: 52px;
            padding: 0.78rem 0.85rem;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.76);
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 750;
            white-space: nowrap;
            border: 1px solid transparent;
            transition: 0.18s ease;
        }

        .nav-pill i {
            width: 22px;
            min-width: 22px;
            font-size: 1.28rem;
            line-height: 1;
            text-align: center;
        }

        .nav-pill:hover,
        .nav-pill.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .nav-pill.active {
            box-shadow: inset 3px 0 0 var(--brand);
        }

        .sidebar-footer {
            margin-top: auto;
            display: grid;
            gap: 0.9rem;
            padding-top: 1.1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.82rem;
            padding: 0.15rem 0.1rem;
            color: #fff;
            min-width: 0;
        }

        .user-avatar {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            display: inline-grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            font-weight: 800;
        }

        .user-name {
            min-width: 0;
            font-size: 0.98rem;
            font-weight: 750;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.56);
            font-size: 0.84rem;
            text-transform: capitalize;
        }

        .logout-btn {
            min-height: 42px;
            border-radius: 8px;
            font-weight: 750;
        }

        .app-main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        .app-content {
            width: 100%;
            max-width: 1580px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem 2rem;
        }

        .card,
        .table,
        .form-control,
        .form-select,
        .btn,
        .badge,
        .alert {
            border-radius: 8px;
        }

        .card {
            border-color: var(--line);
            box-shadow: var(--shadow-sm);
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: var(--text-main);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .page-head {
            margin-bottom: 1rem;
        }

        .page-head h1,
        .page-title {
            font-size: clamp(1.65rem, 2vw, 2.05rem);
            font-weight: 850;
        }

        .form-label {
            margin-bottom: 0.45rem;
            color: var(--text-main);
            font-size: 0.94rem;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            padding: 0.64rem 0.85rem;
            border-color: #d8e0eb;
            font-size: 0.96rem;
        }

        textarea.form-control {
            min-height: 112px;
        }

        .input-group > .form-control,
        .input-group > .form-select,
        .input-group > .btn {
            min-height: 46px;
        }

        .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.52rem 1rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .btn-sm {
            min-height: 36px;
            padding: 0.42rem 0.78rem;
            font-size: 0.88rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 26px;
            padding: 0.38rem 0.62rem;
            font-size: 0.8rem;
            font-weight: 750;
            line-height: 1;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table > :not(caption) > * > * {
            padding: 0.85rem 0.95rem;
        }

        .table thead th {
            font-size: 0.86rem;
            font-weight: 800;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table tbody td {
            font-size: 0.95rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        body.dark-mode {
            --page-bg: #0f172a;
            --surface: #111827;
            --surface-soft: #172033;
            --text-main: #f8fafc;
            --text-muted: #a8b3c7;
            --line: #263244;
            color: var(--text-main);
        }

        .dark-mode .card,
        .dark-mode .table,
        .dark-mode .modal-content {
            background-color: var(--surface);
            color: var(--text-main);
            border-color: var(--line);
        }

        .dark-mode .table thead {
            background-color: var(--surface-soft);
        }

        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: var(--surface-soft);
            color: var(--text-main);
            border-color: var(--line);
        }

        .dark-mode .text-muted {
            color: var(--text-muted) !important;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                position: sticky;
                inset: 0 0 auto 0;
                width: 100%;
                height: auto;
                padding: 0.75rem;
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .sidebar-brand {
                padding-bottom: 0.65rem;
            }

            .sidebar-section-label {
                display: none;
            }

            .sidebar-nav {
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding-bottom: 0.25rem;
                scrollbar-width: none;
            }

            .sidebar-nav::-webkit-scrollbar {
                display: none;
            }

            .nav-pill {
                min-height: 42px;
                padding: 0.55rem 0.75rem;
            }

            .sidebar-footer {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding-top: 0.7rem;
            }

            .theme-toggle {
                width: 42px;
                min-width: 42px;
            }

            .user-chip {
                flex: 1;
            }

            .user-name,
            .user-role {
                display: none;
            }

            .logout-btn {
                min-width: 84px;
            }

            .app-main {
                margin-left: 0;
            }

            .app-content {
                padding: 1rem 0.85rem 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .btn,
            .input-group > .btn {
                min-height: 40px;
            }

            .table > :not(caption) > * > * {
                padding: 0.72rem 0.75rem;
            }
        }
    </style>
</head>

<body>
<div class="app-shell">
    <aside class="app-sidebar">
        <a class="sidebar-brand" href="/dashboard">
            <span class="brand-mark">BW</span>
            <span>Bakso Woow</span>
        </a>

        <div class="sidebar-section-label">Navigasi</div>
        <nav class="sidebar-nav">
            <a href="/dashboard" class="nav-pill {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            @auth
                <a href="/transaksi" class="nav-pill {{ request()->is('transaksi*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Transaksi</span>
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="/produk" class="nav-pill {{ request()->is('produk*') ? 'active' : '' }}">
                        <i class="bi bi-cup-hot"></i>
                        <span>Menu</span>
                    </a>
                    <a href="/bahan-baku" class="nav-pill {{ request()->is('bahan-baku*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Bahan Baku</span>
                    </a>
                    <a href="/resep" class="nav-pill {{ request()->is('resep*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Resep</span>
                    </a>
                    <a href="/laporan" class="nav-pill {{ request()->is('laporan*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                @endif
            @endauth
        </nav>

        @auth
            <div class="sidebar-footer">
                <button onclick="toggleDarkMode()" id="themeBtn" class="theme-toggle" type="button" title="Ganti tema">
                    <i class="bi bi-moon-stars"></i>
                </button>

                <div class="user-chip">
                    <span class="user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                    <span class="d-block min-w-0">
                        <span class="user-name d-block">{{ auth()->user()->nama }}</span>
                        <span class="user-role d-block">{{ auth()->user()->role }}</span>
                    </span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm logout-btn">Logout</button>
                </form>
            </div>
        @endauth
    </aside>

    <main class="app-main">
        <div class="app-content">
            @yield('content')
        </div>
    </main>
</div>

<script>
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");

    let btn = document.getElementById("themeBtn");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
        btn.innerHTML = '<i class="bi bi-brightness-high"></i>';
    } else {
        localStorage.setItem("theme", "light");
        btn.innerHTML = '<i class="bi bi-moon-stars"></i>';
    }
}

window.onload = function () {
    let btn = document.getElementById("themeBtn");

    if (btn && localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
        btn.innerHTML = '<i class="bi bi-brightness-high"></i>';
    }
};
</script>

</body>
</html>
