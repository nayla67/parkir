<?php

namespace App\Http\Controllers;

use App\Models\TiketParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TiketController extends Controller
{
    // =========================
    // Menampilkan semua tiket
    // =========================
    public function index()
    {
        $tiket = TiketParkir::latest()->get();

        return response()->json($tiket);
    }

    // =========================
    // Membuat tiket / QR baru
    // =========================
    public function create(Request $request)
    {
        // Transaksi + row lock (lockForUpdate) mencegah race condition:
        // dua request paralel tidak akan membaca nomor terakhir yang sama.
        return DB::transaction(function () {
            // 1. Ambil tiket terakhir berdasarkan ID menurun (BUKAN created_at).
            //    ID auto-increment selalu naik, jadi "terakhir" tidak ambigu
            //    walau beberapa tiket dibuat di detik yang sama.
            $last = TiketParkir::orderByDesc('id')
                ->lockForUpdate()
                ->first();

            // 2. Ekstrak angka dari kode terakhir lalu +1.
            //    Contoh: "A04" -> (int) 4 -> 5 -> "A05".
            $lastNumber = $last
                ? (int) preg_replace('/\D+/', '', $last->kode_tiket)
                : 0;
            $nomor = $lastNumber + 1;

            // 3. Guard anti-kolisi: lewati nomor yang ternyata sudah terpakai
            //    di tabel (data lama / sisa rekaman), sehingga kode selalu
            //    increment dan tidak pernah duplikat.
            $kode = '';
            do {
                $kode = 'A' . str_pad($nomor, 2, '0', STR_PAD_LEFT);
                $dipakai = TiketParkir::where('kode_tiket', $kode)->exists();
                if ($dipakai) {
                    $nomor++;
                }
            } while ($dipakai);

            // Buat QR unik
            do {
                $qrCode = Str::random(60);
            } while (
                TiketParkir::where('qr_code', $qrCode)->exists()
            );

            // Simpan tiket
            $tiket = TiketParkir::create([
                "kode_tiket" => $kode,
                "qr_code" => $qrCode,
                "jenis" => "non_member",
                "status" => "masuk",
                "waktu_masuk" => now(),
                "waktu_keluar" => null,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Tiket berhasil dibuat",
                "data" => $tiket,
            ], 201);
        });
    }

    // =========================
    // Scan QR untuk kendaraan masuk
    // =========================
    public function masuk(Request $request)
    {
        // Validasi QR
        $request->validate([
            "qr_code" => "required|string",
        ]);

        // Cari QR di database
        $tiket = TiketParkir::where(
            "qr_code",
            $request->qr_code
        )->first();

        // QR tidak ditemukan
        if (!$tiket) {
            return response()->json([
                "success" => false,
                "message" => "QR tidak terdaftar",
            ], 404);
        }

        // Kendaraan masih berada di dalam parkiran
        if ($tiket->status === "masuk") {
            return response()->json([
                "success" => false,
                "message" => "Kendaraan dengan QR ini sudah masuk",
                "data" => $tiket,
            ], 400);
        }

        // Kalau status sebelumnya keluar,
        // kendaraan masuk kembali
        $tiket->update([
            "status" => "masuk",
            "waktu_masuk" => now(),
            "waktu_keluar" => null,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Kendaraan berhasil masuk",
            "data" => $tiket,
        ], 200);
    }

    // =========================
    // Preview cek tiket sebelum keluar (READ-ONLY)
    // Tidak mengubah status / waktu_keluar di database.
    // =========================
    public function cek($qrCode)
    {
        $tiket = TiketParkir::where("qr_code", $qrCode)
            ->where("status", "masuk")
            ->first();

        if (!$tiket) {
            return response()->json([
                "success" => false,
                "message" => "Tiket tidak ditemukan atau kendaraan sudah keluar.",
            ], 404);
        }

        $waktuMasuk = Carbon::parse($tiket->waktu_masuk);
        $durasiMenit = max(0, $waktuMasuk->diffInMinutes(Carbon::now()));
        $jam = max(1, ceil($durasiMenit / 60));

        $tarifPerJam = 3000;
        $totalBayar = $jam * $tarifPerJam;

        return response()->json([
            "success" => true,
            "data" => [
                "kode_tiket" => $tiket->kode_tiket,
                "jenis" => $tiket->jenis,
                "member" => $tiket->jenis === "member",
                "status" => $tiket->status,
                "waktu_masuk" => $tiket->waktu_masuk,
                "durasi_menit" => $durasiMenit,
                "jam" => $jam,
                "tarif_per_jam" => $tarifPerJam,
                "total_bayar" => $totalBayar,
            ],
        ], 200);
    }
}
