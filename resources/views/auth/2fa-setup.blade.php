<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA – Rento</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0B1A2B 0%, #142A4A 100%);
        }
        .card {
            width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logo { font-size: 22px; font-weight: 700; margin-bottom: 16px; }
        .logo span.ren { color: #2D4DA3; }
        .logo span.to  { color: #FF7A00; }
        h1 { font-size: 18px; font-weight: 600; color: #1E1E1E; margin-bottom: 8px; }
        p  { font-size: 13px; color: #6B6B6B; margin-bottom: 20px; line-height: 1.6; }
        .qr-wrapper {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-wrapper img { width: 180px; height: 180px; }
        .steps {
            text-align: left;
            background: #f0f4ff;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1E1E1E;
            line-height: 2;
        }
        .btn {
            display: block;
            width: 100%;
            height: 44px;
            background: #2D4DA3;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .btn:hover { background: #253f8a; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span class="ren">Ren</span><span class="to">to</span></div>
        <h1>Setup Two-Factor Authentication</h1>
        <p>Scan QR code berikut menggunakan aplikasi <strong>Google Authenticator</strong> atau <strong>Authy</strong>.</p>

        <div class="qr-wrapper">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrCodeUrl) }}" alt="QR Code 2FA">
        </div>

        <div class="steps">
            <strong>Langkah:</strong><br>
            1. Install Google Authenticator di HP kamu<br>
            2. Tap tombol <strong>+</strong> → Scan QR Code<br>
            3. Scan QR di atas<br>
            4. Klik tombol di bawah untuk verifikasi
        </div>

        <a href="{{ route('2fa.verify') }}" class="btn">Lanjut ke Verifikasi →</a>
    </div>
</body>
</html>