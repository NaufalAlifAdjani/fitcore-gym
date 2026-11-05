<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kredensial Akun Trainer</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F6F6F7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #16151A;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F6F6F7; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #16151A; padding: 30px 20px; border-bottom: 4px solid #ED1B45;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 1px;">
                                FITCORE <span style="color: #ED1B45;">PRO</span>
                            </h1>
                            <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">
                                Gym &amp; Performance Center
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="margin: 0 0 15px 0; color: #16151A; font-size: 20px; font-weight: 700;">
                                Halo Coach {{ $trainer->name }},
                            </h2>
                            <p style="margin: 0 0 20px 0; color: #565A66; font-size: 14px; line-height: 1.6;">
                                Selamat bergabung di tim kepelatihan <strong>FitCore Gym</strong>! Akun Personal Trainer Anda telah berhasil dibuat oleh Administrator. Anda sekarang dapat mengakses Portal Pelatih untuk mengelola jadwal sesi, melihat klien, dan mengatur ketersediaan waktu Anda.
                            </p>

                            <!-- Credentials Box -->
                            <div style="background-color: #FFF5F7; border: 1px solid #FBCFE8; border-left: 4px solid #ED1B45; border-radius: 8px; padding: 20px; margin: 25px 0;">
                                <h3 style="margin: 0 0 12px 0; color: #9E102F; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                    Informasi Akses Akun Anda
                                </h3>
                                <table role="presentation" border="0" cellpadding="4" cellspacing="0" width="100%" style="font-size: 13px; color: #374151;">
                                    <tr>
                                        <td width="35%" style="font-weight: 600; color: #565A66;">Email Login:</td>
                                        <td style="font-weight: 700; color: #16151A;">{{ $trainer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #565A66;">Kata Sandi:</td>
                                        <td style="font-family: monospace; font-size: 15px; font-weight: 700; color: #ED1B45;">{{ $plainPassword }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #565A66;">Role Akses:</td>
                                        <td style="font-weight: 600; color: #16151A;">Personal Trainer</td>
                                    </tr>
                                    @if($trainer->trainerProfile?->specialization)
                                    <tr>
                                        <td style="font-weight: 600; color: #565A66;">Spesialisasi:</td>
                                        <td style="color: #16151A;">{{ $trainer->trainerProfile->specialization }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #ED1B45; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 32px; border-radius: 9999px; box-shadow: 0 4px 10px rgba(237, 27, 69, 0.3);">
                                    Masuk ke Portal Trainer
                                </a>
                            </div>

                            <p style="margin: 20px 0 0 0; color: #6B7280; font-size: 12px; line-height: 1.5; background-color: #F9FAFB; padding: 12px; border-radius: 8px; border: 1px solid #E5E7EB;">
                                <strong>Pemberitahuan Keamanan:</strong> Demi keamanan akun Anda, harap segera mengganti kata sandi sementara ini setelah Anda berhasil login untuk pertama kalinya. Jangan bagikan informasi kredensial ini kepada pihak lain.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #F9FAFB; padding: 20px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 11px;">
                            <p style="margin: 0 0 4px 0;">&copy; {{ date('Y') }} FitCore Athletic Gym &amp; Performance Center. Seluruh hak cipta dilindungi.</p>
                            <p style="margin: 0;">Email otomatis ini dikirimkan oleh sistem administrasi FitCore Gym.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
