<p align="center">
  <h1 align="center">💻 Sistem Informasi Order Management System (OMS) & Perakitan PC</h1>
  <p align="center">
    <strong>Studi Kasus: PT Aneka Teknologi Solusindo (Diablotech)</strong><br>
    Tugas Akhir / Skripsi — Program Studi Sistem Informasi, Universitas Komputer Indonesia (UNIKOM)
  </p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/Google_Gemini-AI_Powered-8E75B2?style=for-the-badge&logo=googlegemini&logoColor=white" alt="Gemini AI">
</p>

---

## 📖 Tentang Proyek

Aplikasi **Order Management System (OMS)** berbasis web ini dirancang khusus untuk mendigitalisasi dan mengotomatisasi proses bisnis penjualan ritel komponen komputer, perakitan PC (*Custom Build*), hingga manajemen purnajual (*Quality Control* dan Garansi) pada **PT Aneka Teknologi Solusindo**.

Sistem ini dilengkapi dengan algoritma validasi kompatibilitas perangkat keras (*Hardware Clash Detection*) untuk memastikan seluruh komponen PC yang dipilih pelanggan 100% kompatibel dari segi soket, dimensi fisik (*Form Factor*), hingga kebutuhan daya listrik sebelum pesanan dapat diproses.

---

## ✨ Fitur Utama

### 🛒 1. Smart E-Katalog & Perakitan PC (Customer)
* **Auto-Match Daily Build:** Algoritma rekomendasi rakitan PC harian otomatis yang dijamin kompatibel (mencocokkan *Motherboard*, *Processor*, *RAM*, *VGA*, *Cooler*, *Storage*, *Casing*, dan kalkulasi daya *PSU*).
* **Hardware Clash Validation:** Validasi keranjang belanja secara *real-time* yang mengunci *checkout* dan memberikan peringatan spesifik jika ditemukan ketidakcocokan komponen (misal: ukuran radiator *Cooler* atau panjang *VGA* melebihi kapasitas *Casing*).
* **AI Chatbot Assistant:** Konsultasi interaktif terintegrasi dengan AI (Google Gemini) untuk membantu pelanggan meracik spesifikasi PC sesuai anggaran dan kebutuhan.
* **Manajemen PO & Pembayaran:** Mendukung alur transaksi B2B/B2C mulai dari persetujuan/revisi *Purchase Order* (PO), cetak surat penawaran (*Offering Letter*) & *Invoice*, hingga unggah bukti pembayaran (DP maupun Pelunasan).

### 🛡️ 2. Manajemen Operasional & Transaksi (Admin)
* **Dashboard Analitik:** Ringkasan metrik penjualan, status pesanan aktif, dan ketersediaan stok barang.
* **Master Data & Bundling:** Pengelolaan data inventaris komponen (lengkap dengan atribut teknis seperti *socket*, *tipe memory*, *form factor*, dan *power watt*) serta fitur paket rakitan (*Bundle*).
* **Manajemen Pesanan Terpadu:** Verifikasi pembayaran, penentuan ongkos kirim, penugasan teknisi perakit (*Assign Teknisi*), hingga pelacakan resi pengiriman.
* **Laporan & Audit:** Pencetakan laporan penjualan periodik (PDF) dan pemantauan riwayat aktivitas pengguna (*Activity Logs*) yang dapat diekspor ke PDF.

### 🔧 3. Quality Control (QC) & Klaim Garansi/Retur (Teknisi & Admin)
* **Digitalisasi Dokumen QC:** Teknisi dapat mengunggah dokumentasi pengujian fisik (foto tampak depan, belakang, kondisi menyala, dan catatan teknis) untuk setiap unit PC yang dirakit.
* **Cetak Sertifikat Garansi & QC Otomatis:** Pembuatan dokumen garansi dan hasil lulus uji QC berformat PDF secara otomatis yang dapat diunduh langsung oleh pelanggan.
* **Manajemen Retur (RMA):** Alur pengajuan retur barang oleh pelanggan, penginputan resi retur, hingga validasi hasil pengecekan fisik oleh teknisi.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel Framework (v12.x), PHP 8.x
* **Frontend:** Blade Templating Engine, Tailwind CSS, Alpine.js / JavaScript, FontAwesome
* **Asset Bundler:** Vite
* **Database:** MySQL / MariaDB
* **Integrasi Eksternal:** Google Gemini API (AI Chatbot), DOMPDF / Laravel PDF (Cetak Invoice, Offering, Laporan, & Dokumen QC)
* **Deployment Environment:** Docker, Nginx, aaPanel

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal (*local environment*):

### Prasyarat
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL

### Langkah Instalasi

1. **Clone repositori ini:**
   ```bash
   git clone [https://github.com/kevinfernaldyyy/anekatech-skripsi-unikom-SI.git](https://github.com/kevinfernaldyyy/anekatech-skripsi-unikom-SI.git)
   cd anekatech-skripsi-unikom-SI
   ```

2. **Instal dependensi PHP dan JavaScript:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi file Environment (`.env`):**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database di dalam file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Opsional) Tambahkan API Key Gemini Anda jika ingin menguji fitur AI Chatbot:*
   ```env
   GEMINI_API_KEY=your_gemini_api_key_here
   ```

4. **Generate Application Key & Storage Link:**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

5. **Jalankan Migrasi Database:**
   ```bash
   php artisan migrate --seed
   ```
   *(Catatan: Jika menggunakan file dump SQL, silakan impor file `.sql` langsung melalui phpMyAdmin atau aplikasi database client Anda).*

6. **Jalankan Server Aplikasi:**
   Buka dua terminal terpisah untuk menjalankan *server* Laravel dan *compiler* Vite:
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```
   Akses aplikasi melalui browser di `http://localhost:8000`.

---

## 👥 Pembagian Hak Akses (Role)

Sistem ini menggunakan autentikasi *Multi-Role* dengan pembagian akses sebagai berikut:
1. **Admin (`/admin/*`):** Mengelola seluruh data master, memvalidasi pembayaran, menugaskan teknisi, memproses retur, dan mencetak laporan eksekutif.
2. **Teknisi (`/teknisi/*`):** Menerima tugas perakitan/instalasi, melakukan inspeksi komponen, mengisi formulir *Quality Control* (QC), dan memvalidasi barang retur.
3. **Customer (`/customer/*`):** Menjelajahi katalog, merakit PC dengan sistem validasi otomatis, melakukan pemesanan, mengunggah bukti bayar, mengunduh dokumen garansi/QC, dan mengajukan retur.

---

## 👨‍💻 Informasi Pengembang

Disusun dan dikembangkan sebagai syarat kelulusan Sarjana Komputer (S.Kom):

* **Nama:** Kevin Fernaldy
* **Program Studi:** Sistem Informasi
* **Fakultas:** Teknik dan Ilmu Komputer
* **Institusi:** Universitas Komputer Indonesia (UNIKOM) — Bandung, Indonesia

---
<p align="center">
  <i>Hak Cipta © 2026 Kevin Fernaldy. Seluruh hak cipta dilindungi undang-undang.</i>
</p>
