<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun MyFutsal Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #DC2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
            border-top: none;
        }

        .password-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            border-radius: 5px;
        }

        .password {
            font-family: monospace;
            font-size: 24px;
            font-weight: bold;
            color: #DC2626;
            letter-spacing: 2px;
        }

        .cta-button {
            display: inline-block;
            background-color: #DC2626;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di MyFutsal</h1>
        </div>

        <div class="content">
            <h2>Halo, {{ $user->full_name }}!</h2>

            <p>Terima kasih telah mendaftar di MyFutsal. Akun Anda telah berhasil dibuat.</p>

            <p>Berikut adalah informasi akun Anda:</p>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Nama:</strong> {{ $user->full_name }}</li>
                <li><strong>No. Telepon:</strong> {{ $user->phone_number }}</li>
            </ul>

            <p>Kata sandi sementara Anda adalah:</p>

            <div class="password-box">
                <span class="password">{{ $password }}</span>
            </div>

            <p><strong>PENTING:</strong> Demi keamanan akun Anda, kami sangat menyarankan agar Anda segera mengubah kata
                sandi ini setelah berhasil login.</p>

            <p>Untuk mengubah kata sandi Anda, silakan kunjungi halaman profil di akun MyFutsal Anda setelah login.</p>

            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="cta-button">Login Sekarang</a>
            </p>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim dukungan kami.
            </p>

            <p>Terima kasih,<br>Tim MyFutsal</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} MyFutsal. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
