<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Sistem Penjualan Bakso Woow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #f97316;
            --brand-dark: #c2410c;
            --ink: #111827;
            --muted: #667085;
            --line: #e6ebf2;
            --surface: #ffffff;
            --page: #f4f7fb;
        }

        * {
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at 18% 18%, rgba(249, 115, 22, 0.18), transparent 28%),
                linear-gradient(135deg, #fff7ed 0%, #f4f7fb 48%, #eaf2ff 100%);
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(380px, 480px);
        }

        .brand-panel {
            display: flex;
            align-items: center;
            padding: clamp(2rem, 6vw, 5rem);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .brand-content {
            max-width: 640px;
        }

        .brand-mark {
            display: inline-grid;
            width: 54px;
            height: 54px;
            place-items: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #fb923c, #ef4444);
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            box-shadow: 0 18px 40px rgba(249, 115, 22, 0.28);
            margin-bottom: 1.5rem;
        }

        .brand-title {
            font-size: clamp(2.15rem, 5vw, 4.5rem);
            line-height: 1;
            font-weight: 900;
            margin: 0;
        }

        .brand-text {
            color: var(--muted);
            max-width: 520px;
            margin: 1rem 0 0;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .feature-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
            margin-top: 2rem;
        }

        .feature-item {
            border: 1px solid rgba(17, 24, 39, 0.08);
            background: rgba(255, 255, 255, 0.68);
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .feature-item strong {
            display: block;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .feature-item span {
            color: var(--muted);
            font-size: 0.84rem;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.72);
            border-left: 1px solid rgba(17, 24, 39, 0.08);
            backdrop-filter: blur(16px);
        }

        .login-card {
            width: 100%;
            max-width: 410px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 22px 55px rgba(15, 23, 42, 0.13);
        }

        .card-title {
            font-size: 1.45rem;
            font-weight: 850;
            margin-bottom: 0.35rem;
        }

        .card-subtitle {
            color: var(--muted);
            margin-bottom: 1.35rem;
        }

        .form-label {
            font-weight: 750;
            color: #334155;
            font-size: 0.9rem;
        }

        .form-control {
            min-height: 46px;
            border-radius: 8px;
            border-color: var(--line);
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.16);
            background: #fff;
        }

        .btn-login {
            min-height: 46px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            font-weight: 800;
            box-shadow: 0 14px 26px rgba(249, 115, 22, 0.26);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #fb923c, #9a3412);
        }

        .login-footer {
            color: var(--muted);
            font-size: 0.82rem;
            text-align: center;
            margin: 1.1rem 0 0;
        }

        @media (max-width: 960px) {
            .login-page {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                padding-bottom: 1rem;
            }

            .form-panel {
                border-left: 0;
                align-items: flex-start;
            }
        }

        @media (max-width: 640px) {
            .feature-row {
                grid-template-columns: 1fr;
            }

            .brand-panel,
            .form-panel {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<main class="login-page">
    <section class="brand-panel">
        <div class="brand-content">
            <div class="brand-mark">BW</div>
            <h1 class="brand-title">Bakso Woow</h1>
            <p class="brand-text">
                Sistem penjualan yang membantu kasir dan admin memantau menu, transaksi, laporan, dan stok bahan baku dalam satu tempat.
            </p>

            <div class="feature-row">
                <div class="feature-item">
                    <strong>Transaksi cepat</strong>
                    <span>Catat pesanan dan cetak struk.</span>
                </div>
                <div class="feature-item">
                    <strong>Laporan jelas</strong>
                    <span>Pantau pendapatan harian.</span>
                </div>
                <div class="feature-item">
                    <strong>Data rapi</strong>
                    <span>Kelola menu dan bahan baku.</span>
                </div>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <div class="login-card">
            <h2 class="card-title">Masuk ke dashboard</h2>
            <p class="card-subtitle">Gunakan akun admin atau kasir yang sudah terdaftar.</p>

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100">Login</button>
            </form>

            <p class="login-footer">Sistem Penjualan Bakso Woow</p>
        </div>
    </section>
</main>

</body>
</html>
