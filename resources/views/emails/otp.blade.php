<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #0B1A2B, #142A4A); padding: 24px; text-align: center; }
        .logo { font-size: 24px; font-weight: 700; }
        .logo span.ren { color: #5b8af5; }
        .logo span.to  { color: #FF7A00; }
        .body { padding: 32px; text-align: center; }
        .greeting { font-size: 15px; color: #444; margin-bottom: 8px; }
        .message { font-size: 13px; color: #777; margin-bottom: 24px; }
        .otp-box { background: #f0f4ff; border: 2px dashed #2D4DA3; border-radius: 10px; padding: 20px; margin-bottom: 24px; }
        .otp-code { font-size: 40px; font-weight: 700; letter-spacing: 12px; color: #2D4DA3; }
        .expire { font-size: 12px; color: #e53e3e; margin-top: 8px; }
        .footer { background: #f5f5f5; padding: 16px; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <span class="ren">Ren</span><span class="to">to</span>
            </div>
        </div>
        <div class="body">
            <p class="greeting">Hi, <strong>{{ $adminName }}</strong>!</p>
            <p class="message">Gunakan kode OTP berikut untuk login ke Rento Admin Portal. Kode berlaku selama <strong>5 menit</strong>.</p>
            <div class="otp-box">
                <div class="otp-code">{{ $otpCode }}</div>
                <p class="expire">⏱ Kode akan kadaluarsa dalam 5 menit</p>
            </div>
            <p style="font-size:12px;color:#999;">Jika kamu tidak merasa melakukan login, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Rento Administrator. All rights reserved.
        </div>
    </div>
</body>
</html>