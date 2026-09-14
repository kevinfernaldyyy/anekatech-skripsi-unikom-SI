<!DOCTYPE html>
<html>
<head>
    <title>Informasi Akun Staf - DiabloTech</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            background-color: #f4f7fa; 
            margin: 0; 
            padding: 0; 
            -webkit-font-smoothing: antialiased;
        }
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            background-color: #ffffff; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            border: 1px solid #eaeaea; 
        }
        .header { 
            background-color: #0f172a; 
            padding: 35px 30px; 
            text-align: center; 
            border-bottom: 3px solid #2563eb; 
        }
        .header h1 { 
            color: #ffffff; 
            margin: 0; 
            font-size: 24px; 
            letter-spacing: 2px; 
            text-transform: uppercase; 
        }
        .header h1 span { 
            color: #3b82f6; 
        }
        .content { 
            padding: 40px; 
            color: #475569; 
            line-height: 1.8; 
            font-size: 15px; 
        }
        .content h2 { 
            color: #0f172a; 
            margin-top: 0; 
            font-size: 20px; 
            font-weight: 600; 
        }
        .info-box { 
            background-color: #f8fafc; 
            border: 1px solid #e2e8f0; 
            border-left: 4px solid #2563eb; 
            padding: 20px; 
            border-radius: 4px; 
            margin: 30px 0; 
        }
        .info-box strong { 
            color: #0f172a; 
        }
        .warning-box {
            color: #b91c1c;
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 20px;
            line-height: 1.6;
        }
        .btn { 
            display: inline-block; 
            background-color: #2563eb; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 12px 30px; 
            border-radius: 4px; 
            font-weight: 600; 
            margin-top: 25px; 
            font-size: 14px; 
            text-align: center;
        }
        .footer { 
            background-color: #f8fafc; 
            padding: 25px 40px; 
            text-align: center; 
            color: #94a3b8; 
            font-size: 12px; 
            border-top: 1px solid #e2e8f0; 
            line-height: 1.6; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DIABLO<span>TECH</span></h1>
        </div>
        <div class="content">
            <h2>Yth. {{ $user->name }},</h2>
            <p>Kami menginformasikan bahwa akun Anda sebagai <strong>{{ strtoupper($user->role) }}</strong> di sistem manajemen internal DiabloTech (PT Aneka Teknologi Solusindo) telah berhasil didaftarkan oleh Administrator.</p>
            
            <div class="info-box">
                <strong>Kredensial Akses Anda:</strong><br><br>
                Alamat Email : {{ $user->email }}<br>
                Kata Sandi   : <strong>{{ $passwordAsli }}</strong>
            </div>

            <div class="warning-box">
                <strong>PENTING:</strong> Demi keamanan data perusahaan, kami sangat menyarankan Anda untuk segera mengubah kata sandi standar di atas setelah Anda berhasil masuk (<em>login</em>) ke dalam sistem untuk pertama kalinya.
            </div>

            <a href="{{ url('/login') }}" class="btn">Masuk ke Sistem</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PT Aneka Teknologi Solusindo. Seluruh hak cipta dilindungi.<br>
            Jl. Teknologi No. 123, Kota Bandung
        </div>
    </div>
</body>
</html>