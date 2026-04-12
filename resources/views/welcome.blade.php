<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RentalApp — Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F7F8FC;
            color: #1E1E1E;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 64px;
            background: white;
            border-bottom: 1px solid #E5E5E5;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo {
            width: 36px;
            height: 36px;
            background: #2D4DA3;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .nav-brand-name {
            font-size: 17px;
            font-weight: 700;
            color: #1E1E1E;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login {
            height: 38px;
            padding: 0 20px;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            background: white;
            font-family: Inter, sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #1E1E1E;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background 0.15s;
        }

        .btn-login:hover { background: #F5F5F5; }

        .btn-dashboard {
            height: 38px;
            padding: 0 20px;
            border: none;
            border-radius: 8px;
            background: #2D4DA3;
            font-family: Inter, sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background 0.15s;
        }

        .btn-dashboard:hover { background: #253f8a; }

        /* ── Hero ── */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 24px 60px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #EEF2FF;
            color: #2D4DA3;
            border: 1px solid #C7D2FE;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 28px;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            color: #1E1E1E;
            line-height: 1.15;
            letter-spacing: -1.5px;
            max-width: 680px;
            margin-bottom: 12px;
        }

        .hero h1 span {
            color: #2D4DA3;
        }

        .hero-sub {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: 18px;
            color: #6B6B6B;
            max-width: 480px;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-primary {
            height: 48px;
            padding: 0 32px;
            background: #2D4DA3;
            border: none;
            border-radius: 10px;
            font-family: Inter, sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .btn-primary:hover { background: #253f8a; }

        .btn-secondary {
            height: 48px;
            padding: 0 28px;
            background: white;
            border: 1px solid #E5E5E5;
            border-radius: 10px;
            font-family: Inter, sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: #1E1E1E;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background 0.15s;
        }

        .btn-secondary:hover { background: #F5F5F5; }

        /* ── Stats Strip ── */
        .stats-strip {
            display: flex;
            justify-content: center;
            gap: 0;
            background: white;
            border-top: 1px solid #E5E5E5;
            border-bottom: 1px solid #E5E5E5;
        }

        .stat-item {
            flex: 1;
            max-width: 220px;
            padding: 28px 24px;
            text-align: center;
            border-right: 1px solid #E5E5E5;
        }

        .stat-item:last-child { border-right: none; }

        .stat-item .val {
            font-size: 30px;
            font-weight: 800;
            color: #2D4DA3;
            letter-spacing: -1px;
            margin-bottom: 4px;
        }

        .stat-item .lbl {
            font-size: 13px;
            color: #9E9E9E;
            font-weight: 500;
        }

        /* ── Feature Cards ── */
        .features {
            padding: 64px 48px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .features-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #1E1E1E;
            margin-bottom: 32px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .feature-card {
            background: white;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            padding: 24px;
            transition: box-shadow 0.2s;
        }

        .feature-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .feature-icon.blue   { background: #EEF2FF; }
        .feature-icon.green  { background: #F0FDF4; }
        .feature-icon.orange { background: #FFF7ED; }
        .feature-icon.red    { background: #FEF2F2; }
        .feature-icon.purple { background: #F5F3FF; }
        .feature-icon.teal   { background: #F0FDFA; }

        .feature-card h3 {
            font-size: 15px;
            font-weight: 700;
            color: #1E1E1E;
            margin-bottom: 6px;
        }

        .feature-card p {
            font-size: 13px;
            color: #6B6B6B;
            line-height: 1.6;
        }

        /* ── Footer ── */
        footer {
            text-align: center;
            padding: 24px;
            font-size: 13px;
            color: #9E9E9E;
            border-top: 1px solid #E5E5E5;
            background: white;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav>
        <a href="/" class="nav-brand">
            <div class="nav-logo">📦</div>
            <span class="nav-brand-name">RentalApp</span>
        </a>
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-dashboard">
                    Go to Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Log in</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <div class="hero">
        <div class="hero-badge">
            ✦ Rental Management System
        </div>
        <h1>Kelola Bisnis Rental<br>Anda dengan <span>Mudah</span></h1>
        <p class="hero-sub">Pantau produk, transaksi, pelanggan, dan denda keterlambatan — semua dalam satu dashboard.</p>
        <div class="hero-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">
                    Buka Dashboard
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Masuk Sekarang
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ url('/dashboard') }}" class="btn-secondary">Lihat Demo</a>
            @endauth
        </div>
    </div>

    {{-- Stats Strip --}}
    <div class="stats-strip">
        <div class="stat-item">
            <div class="val">100%</div>
            <div class="lbl">Cloud-based via Supabase</div>
        </div>
        <div class="stat-item">
            <div class="val">Real-time</div>
            <div class="lbl">Data selalu up-to-date</div>
        </div>
        <div class="stat-item">
            <div class="val">Multi-role</div>
            <div class="lbl">Superadmin & Admin</div>
        </div>
        <div class="stat-item">
            <div class="val">CSV</div>
            <div class="lbl">Export laporan kapan saja</div>
        </div>
    </div>

    {{-- Features --}}
    <div class="features">
        <div class="features-title">Semua yang Anda Butuhkan</div>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon blue">📊</div>
                <h3>Dashboard Overview</h3>
                <p>Pantau revenue, active rentals, total pelanggan, dan penalty dalam satu tampilan ringkas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon green">📦</div>
                <h3>Manajemen Produk</h3>
                <p>Kelola inventaris produk rental lengkap dengan kategori, stok, kondisi, dan harga sewa.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon orange">👥</div>
                <h3>Manajemen Pelanggan</h3>
                <p>Data pelanggan lengkap termasuk nomor KTP, kontak darurat, dan riwayat transaksi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon red">⚠️</div>
                <h3>Penalties & Returns</h3>
                <p>Lacak keterlambatan pengembalian dan kerusakan barang. Kirim reminder email otomatis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon purple">📋</div>
                <h3>Laporan Transaksi</h3>
                <p>Lihat seluruh histori transaksi dengan filter status dan export ke CSV kapan saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon teal">🔐</div>
                <h3>Multi-role Admin</h3>
                <p>Superadmin dan admin dengan kontrol akses berbeda untuk keamanan data bisnis Anda.</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer>
        &copy; {{ date('Y') }} RentalApp · Powered by Laravel & Supabase
    </footer>

</body>
</html>