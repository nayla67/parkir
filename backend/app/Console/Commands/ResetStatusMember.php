<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetStatusMember extends Command
{
    protected $signature = 'member:reset-status';

    protected $description = 'Sinkronkan status member sesuai tanggal expired_at';

    public function handle()
    {
        $tanggalSekarang = now()->toDateString();


        // ==================================================
        // MEMBER YANG SUDAH LEWAT EXPIRED -> JADI 'belum'
        // ==================================================

        $jadiBelum = Member::whereDate('expired_at', '<', $tanggalSekarang)
            ->where('status', '!=', 'belum')
            ->update([
                'status' => 'belum',
            ]);


        // ==================================================
        // MEMBER YANG MASIH DALAM MASA BERLAKU -> JADI 'lunas'
        // ==================================================

        $jadiLunas = Member::whereDate('expired_at', '>=', $tanggalSekarang)
            ->where('status', '!=', 'lunas')
            ->update([
                'status' => 'lunas',
            ]);


        Log::info(
            "Sinkronisasi status member: {$jadiBelum} jadi 'belum', {$jadiLunas} jadi 'lunas'."
        );

        $this->info(
            "Berhasil sinkronisasi: {$jadiBelum} member jadi 'belum' (expired), {$jadiLunas} member jadi 'lunas' (masih berlaku)."
        );
    }
}