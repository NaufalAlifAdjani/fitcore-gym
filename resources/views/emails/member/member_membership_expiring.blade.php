<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Masa Aktif Membership</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #F4F4F5;
            color: #16151A;
            margin: 0;
            padding: 32px 16px;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #E4E4E7;
        }
        .header {
            background-color: #BA0030;
            padding: 32px 24px;
            text-align: center;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .header p {
            margin: 8px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 32px 24px;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 16px;
            color: #3F3F46;
        }
        .info-card {
            background-color: #FAFAFA;
            border-left: 4px solid #BA0030;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            color: #71717A;
            font-weight: 500;
        }
        .info-value {
            color: #16151A;
            font-weight: 700;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0 16px;
        }
        .btn-renew {
            display: inline-block;
            background-color: #BA0030;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.02em;
        }
        .footer {
            background-color: #FAFAFA;
            padding: 20px 24px;
            text-align: center;
            border-top: 1px solid #E4E4E7;
            font-size: 12px;
            color: #A1A1AA;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FitCore Gym</h1>
            <p>Pengingat Masa Aktif Paket Membership</p>
        </div>

        <div class="content">
            <p>Halo, <strong>{{ $member?->name ?? 'Member FitCore' }}</strong>!</p>
            <p>
                Kami ingin menginformasikan bahwa paket keanggotaan FitCore Gym Anda akan segera berakhir dalam <strong>3 hari</strong>.
            </p>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Paket Membership:</span>
                    <span class="info-value">{{ $package?->name ?? 'Membership Gym' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Berakhir:</span>
                    <span class="info-value">{{ optional($membership->end_date)->translatedFormat('d F Y') ?? $membership->end_date }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Saat Ini:</span>
                    <span class="info-value" style="color: #059669; text-transform: uppercase;">{{ $membership->status }}</span>
                </div>
            </div>

            <p>
                Agar sesi latihan rutin dan progres kebugaran Anda tidak terputus, segera lakukan perpanjangan paket keanggotaan sebelum tanggal kedaluwarsa.
            </p>

            <div class="button-wrapper">
                <a href="{{ route('member.membership.packages') }}" class="btn-renew">Perpanjang Membership Sekarang</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} FitCore Gym. Semua Hak Dilindungi.<br>
            Email ini dikirim secara otomatis oleh sistem FitCore Gym.
        </div>
    </div>
</body>
</html>
