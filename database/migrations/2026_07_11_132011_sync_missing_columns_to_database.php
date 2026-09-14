<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==========================================
        // 1. SINKRONISASI TABEL PENGAJUAN RETUR
        // ==========================================
        
        // Ganti nama tabel jika di migration lama namanya 'pengajuan_returs' atau 'pengajuan_retur'
        if (Schema::hasTable('pengajuan_returs')) {
            Schema::rename('pengajuan_returs', 'pengajuan_return');
        } elseif (Schema::hasTable('pengajuan_retur')) {
            Schema::rename('pengajuan_retur', 'pengajuan_return');
        }

        Schema::table('pengajuan_return', function (Blueprint $table) {
            // Tambah kolom yang hilang
            $table->string('foto_packing_balik')->nullable()->after('foto_resi_balik');
            $table->string('nama_bank')->nullable()->after('foto_packing_balik');
            $table->string('no_rekening')->nullable()->after('nama_bank');
            $table->string('bukti_refund')->nullable()->after('no_rekening');
            $table->string('hasil_cek_teknisi')->nullable()->after('bukti_refund');
        });

        // Modifikasi Enum Status (Pakai raw SQL agar aman dan tidak bentrok)
        DB::statement("ALTER TABLE pengajuan_return MODIFY COLUMN status ENUM('menunggu_verifikasi', 'disetujui', 'ditolak', 'barang_dikirim', 'selesai', 'sedang_dicek_teknisi', 'selesai_refund', 'klaim_ditolak') NOT NULL DEFAULT 'menunggu_verifikasi'");


        // ==========================================
        // 2. SINKRONISASI TABEL TRANSAKSI
        // ==========================================
        
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambah kolom status_pesanan & status_order jika belum ada
            if (!Schema::hasColumn('transaksi', 'status_pesanan')) {
                $table->enum('status_pesanan', ['menunggu_proses', 'sedang_dirakit', 'menunggu_po', 'siap_diambil', 'sedang_dikirim', 'selesai', 'batal', 'belum_bayar', 'sedang_disiapkan'])->default('menunggu_proses')->after('bukti_pelunasan');
            }
            if (!Schema::hasColumn('transaksi', 'status_order')) {
                $table->string('status_order', 50)->default('process')->after('status_pesanan');
            }
        });

        // Update Enum metode_pengiriman & status_pembayaran
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN metode_pengiriman ENUM('pickup', 'delivery', 'courier', 'technician') NOT NULL DEFAULT 'pickup'");
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status_pembayaran ENUM('lunas', 'dp', 'belum_bayar', 'batal') NOT NULL DEFAULT 'lunas'");


        // ==========================================
        // 3. SINKRONISASI TABEL TRANSAKSI DETAILS
        // ==========================================
        
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Tambah kolom nama_barang_custom
            if (!Schema::hasColumn('transaksi_details', 'nama_barang_custom')) {
                $table->string('nama_barang_custom')->nullable()->after('barang_id');
            }
        });

        // Ubah barang_id agar boleh kosong (NULL)
        DB::statement("ALTER TABLE transaksi_details MODIFY COLUMN barang_id BIGINT UNSIGNED NULL");


        // ==========================================
        // 4. SINKRONISASI TABEL PENGIRIMAN TEKNISI
        // ==========================================
        
        // Ganti nama tabel jika di migration lama namanya 'pengiriman_teknisis'
        if (Schema::hasTable('pengiriman_teknisis')) {
            Schema::rename('pengiriman_teknisis', 'pengiriman_teknisi');
        }

        Schema::table('pengiriman_teknisi', function (Blueprint $table) {
            // Tambah kolom dokumen
            if (!Schema::hasColumn('pengiriman_teknisi', 'dokumen')) {
                $table->string('dokumen')->nullable()->after('foto_bukti_instalasi');
            }

            // Hapus kolom yang tidak jadi dipakai
            if (Schema::hasColumn('pengiriman_teknisi', 'foto_kartu_garansi')) {
                $table->dropColumn('foto_kartu_garansi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};