<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Murojaat tasdiqlandi</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #1d4ed8; padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: #bfdbfe; margin: 8px 0 0; font-size: 14px; }
        .body { padding: 32px 40px; }
        .tracking-box { background: #eff6ff; border: 2px solid #bfdbfe; border-radius: 8px; text-align: center; padding: 20px; margin-bottom: 28px; }
        .tracking-box .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .tracking-box .code { font-size: 28px; font-weight: 700; color: #1d4ed8; letter-spacing: 3px; margin-top: 6px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-table tr td { padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .info-table tr td:first-child { color: #6b7280; width: 40%; }
        .info-table tr td:last-child { color: #111827; font-weight: 500; }
        .message-box { background: #f9fafb; border-radius: 6px; padding: 16px; font-size: 14px; color: #374151; line-height: 1.6; margin-bottom: 24px; }
        .btn { display: inline-block; background: #1d4ed8; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; }
        .footer { padding: 24px 40px; background: #f9fafb; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Murojaatingiz qabul qilindi</h1>
        <p>SIES Rektori murojaatlar tizimi</p>
    </div>

    <div class="body">
        <p style="color:#374151; font-size:15px; margin-top:0;">
            Hurmatli <strong>{{ $appeal->full_name }}</strong>,
        </p>
        <p style="color:#6b7280; font-size:14px;">
            Murojaatingiz muvaffaqiyatli qabul qilindi. Quyida tracking kodingiz va murojaat ma'lumotlari keltirilgan.
        </p>

        <div class="tracking-box">
            <div class="label">Tracking kodi</div>
            <div class="code">{{ $appeal->tracking_code }}</div>
        </div>

        <table class="info-table">
            <tr>
                <td>Sana</td>
                <td>{{ $appeal->created_at->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>{{ $appeal->phone }}</td>
            </tr>
            <tr>
                <td>Mavzu</td>
                <td>{{ $appeal->subject }}</td>
            </tr>
            <tr>
                <td>Holat</td>
                <td>Ko'rib chiqilmoqda</td>
            </tr>
        </table>

        <p style="color:#6b7280; font-size:13px; margin-bottom:8px;"><strong>Murojaat matni:</strong></p>
        <div class="message-box">{{ $appeal->body }}</div>

        <p style="text-align:center; margin-bottom:0;">
            <a href="{{ url('/tracking?code=' . $appeal->tracking_code) }}" class="btn">
                Murojaat holatini kuzatish
            </a>
        </p>
    </div>

    <div class="footer">
        Ushbu xabar avtomatik yuborilgan. Javob bermang.<br>
        &copy; {{ date('Y') }} SIES Rektori murojaatlar tizimi
    </div>
</div>
</body>
</html>
