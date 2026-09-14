<!DOCTYPE html>
<html>
<head>
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
        .btn { 
            display: inline-block; 
            background-color: #2563eb; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 12px 30px; 
            border-radius: 4px; 
            font-weight: 600; 
            margin-top: 15px; 
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
            <h2>Selamat bergabung, {{ $user->name }}.</h2>
            <p>Kami mengucapkan terima kasih atas kepercayaan Anda untuk mendaftar di sistem DiabloTech. Kami informasikan bahwa akun Anda telah berhasil dibuat dan siap untuk digunakan.</p>
            
            <p>Melalui platform kami, Anda dapat mengakses katalog komponen perangkat keras terkini, mengajukan pesanan perakitan kustom, serta memantau status perakitan secara waktu nyata (<em>real-time</em>) melalui Dasbor Pelanggan.</p>

            <div class="info-box">
                <strong>Informasi Akun Anda:</strong><br><br>
                Alamat Email : {{ $user->email }}<br>
                Status Akun  : Aktif
            </div>

            <p>Untuk memulai pengalaman berbelanja dan perakitan Anda, silakan akses portal kami melalui tautan di bawah ini.</p>
            
            <a href="{{ url('/') }}" class="btn">Kunjungi Portal DiabloTech</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PT Aneka Teknologi Solusindo. Seluruh hak cipta dilindungi.<br>
            Jl. Teknologi No. 99, Kota Bandung
        </div>
    </div>
</body>
</html>