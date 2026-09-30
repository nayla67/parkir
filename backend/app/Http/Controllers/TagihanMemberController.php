<?php

namespace App\Http\Controllers;

use App\Models\TagihanMember;
use Illuminate\Http\Request;

class TagihanMemberController extends Controller
{
    // Menampilkan semua tagihan
    public function index()
    {
        $tagihan = TagihanMember::with('member')
            ->latest('tanggal_tagihan')
            ->get();

        return response()->json($tagihan);
    }

    // Menampilkan satu tagihan
    public function show(TagihanMember $tagihanMember)
    {
        $tagihanMember->load('member');

        return response()->json($tagihanMember);
    }

    // Membayar tagihan
    public function bayar(TagihanMember $tagihanMember)
    {
        // Kalau sudah lunas, tidak bisa dibayar lagi
        if ($tagihanMember->status === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan ini sudah lunas.',
            ], 400);
        }

        // Ubah tagihan menjadi lunas
        $tagihanMember->update([
            'status' => 'lunas',
            'tanggal_bayar' => now()->toDateString(),
        ]);

        // Ubah status member menjadi lunas
        $tagihanMember->member->update([
            'status' => 'lunas',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil.',
            'data' => $tagihanMember->load('member'),
        ]);
    }
}