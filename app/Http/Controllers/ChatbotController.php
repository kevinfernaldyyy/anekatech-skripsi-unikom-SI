<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Barang; // Sesuaikan dengan nama model barang kamu
use Exception; // Tambahkan ini di atas

class ChatbotController extends Controller
{
    public function askGemini(Request $request)
    {
        try {
            $userMessage = $request->input('pesan');

            // 1. Ambil data barang yang stoknya ada sebagai "Contekan" AI
            $barangTersedia = Barang::where('stok', '>', 0)
            ->get(['nama_barang', 'kategori', 'harga_jual']);
            $dataPengetahuan = json_encode($barangTersedia);

            // 2. Buat System Prompt
            $prompt = "Kamu adalah Asisten AI dari PT Aneka Teknologi Solusindo (DiabloTech). 
            Tugasmu membantu kustomer merakit PC atau memberikan rekomendasi berdasarkan budget.
            Gunakan data harga dan komponen real-time berikut ini untuk menjawab: " . $dataPengetahuan . "
            Jika budget tidak cukup, beritahu alasannya dengan sopan. Jawablah dengan format HTML yang rapi (gunakan <b>, <ul>, <li>).
            ATURAN PENTING: Jika kustomer menanyakan kontak, ingin konsultasi dengan manusia/teknisi, atau meminta nomor WhatsApp (WA), kamu WAJIB memberikan jawaban dengan mengarahkan mereka ke nomor WhatsApp ini: 6281234567890. Berikan juga link format HTML seperti ini: <a href='https://wa.me/6281234567890' target='_blank' class='text-blue-600 underline'>Hubungi Admin via WA</a>.
            Pertanyaan Kustomer: " . $userMessage;

            // 3. Tembak API Google (Pakai withoutVerifying() untuk Bypass SSL di Lokal)
            $apiKey = env('GEMINI_API_KEY');
            
            // Cek apakah API Key terbaca
            if(empty($apiKey)) {
                return response()->json(['reply' => 'Sistem Error: API Key tidak terbaca dari file .env!']);
            }

            $response = Http::withoutVerifying()->withHeaders([ // <-- withoutVerifying() adalah kunci untuk lokal!
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            // 4. Cek hasil balasan
            if ($response->successful()) {
                $botReply = $response->json('candidates.0.content.parts.0.text');
                return response()->json(['reply' => $botReply]);
            } else {
                // Kalau Google nolak, tampilkan alasan aslinya
                return response()->json(['reply' => 'Google API Error: ' . $response->body()]);
            }

        } catch (Exception $e) {
            // Kalau Laravel/Server yang error, tampilkan error aslinya
            return response()->json(['reply' => 'Laravel Error: ' . $e->getMessage()]);
        }
    }
}