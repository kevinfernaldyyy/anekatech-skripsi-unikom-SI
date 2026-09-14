<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Styles */
        body { 
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif; 
            background-color: #f1f5f9; 
            margin: 0; 
            padding: 0; 
        }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f1f5f9; padding-bottom: 50px; }
        .main { 
            background-color: #ffffff; 
            margin: 30px auto; 
            width: 100%; 
            max-width: 600px; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(30, 41, 59, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        /* Header & Brand */
        .header { 
            background-color: #ffffff; 
            padding: 35px 30px 20px 30px; 
            text-align: center; 
            border-bottom: 1px solid #f1f5f9;
        }
        .logo-img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Content Area */
        .content { padding: 40px; color: #334155; line-height: 1.7; }
        .badge-task {
            display: inline-block;
            background-color: #fff7ed;
            color: #c2410c;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #ffedd5;
            margin-bottom: 15px;
        }
        h2 { color: #0f172a; margin-top: 0; font-size: 22px; font-weight: 800; }
        
        /* Task Info Box */
        .task-box { 
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 25px; 
            margin: 25px 0;
        }
        .task-row { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .label { color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; width: 120px; flex-shrink: 0; }
        .value { color: #1e293b; font-size: 14px; font-weight: 700; }

        /* CTA Button */
        .btn-container { text-align: center; margin: 30px 0; }
        .btn { 
            background-color: #1e40af; 
            color: #ffffff !important; 
            padding: 16px 35px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 14px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .footer { 
            padding: 25px; 
            text-align: center; 
            font-size: 11px; 
            color: #94a3b8; 
            background-color: #f8fafc;
        }
        .highlight { color: #1e40af; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <img src="{{ url('img/logo.jpeg') }}" alt="DIABLOTECH" class="logo-img">
                <div style="margin-top: 10px; color: #94a3b8; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">
                    Internal Operations System
                </div>
            </div>

            <div class="content">
                <div class="badge-task">New Build Assignment</div>
                <h2>Halo, {{ $teknisi->name }} 🛠️</h2>
                <p>Sistem telah menugaskan Anda untuk bertanggung jawab pada proyek perakitan unit <span class="highlight">Custom Build</span> terbaru. Mohon periksa detail penugasan berikut:</p>
                
                <div class="task-box">
                    <div class="task-row">
                        <span class="label">No. Pesanan</span>
                        <span class="value">#{{ $transaksi->no_transaksi }}</span>
                    </div>
                    <div class="task-row">
                        <span class="label">Nama Client</span>
                        <span class="value">{{ $transaksi->nama_customer }}</span>
                    </div>
                    <div class="task-row" style="margin-bottom: 0;">
                        <span class="label">Prioritas</span>
                        <span class="value" style="color: #ef4444;">Standar / High (Urgent)</span>
                    </div>
                </div>

                <p>Segera lakukan verifikasi ketersediaan komponen di gudang dan mulai proses perakitan sesuai dengan standar operasional <strong>Quality Control (QC)</strong> DiabloTech.</p>

                <div class="btn-container">
                    <a href="{{ url('/admin/orders/' . $transaksi->id) }}" class="btn">Buka Lembar Kerja QC</a>
                </div>

                <p style="font-size: 13px; color: #64748b; font-style: italic; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                    Catatan: Harap lampirkan foto hasil stress-test suhu komponen ke dalam sistem setelah selesai.
                </p>

                <div style="margin-top: 30px;">
                    <p style="margin-bottom: 0; font-size: 13px;">Diterbitkan otomatis oleh,</p>
                    <p style="margin-top: 5px; font-weight: bold; color: #1e293b;">DiabloTech Admin Center</p>
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} <strong>PT Aneka Teknologi Solusi Indo</strong>. All rights reserved.<br>
                Gedung Workshop Lt. 2, Bandung.
            </div>
        </div>
    </div>
</body>
</html>