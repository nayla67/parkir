<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\TagihanMember;
use Illuminate\Console\Command;
use Carbon\Carbon;

class BuatTagihanMember extends Command
{
    protected $signature = 'member:buat-tagihan';

    protected $description = 'Membuat tagihan bulanan untuk semua member';

    public function handle()
    {
        $tanggalTagihan = Carbon::now()->startOfMonth();

        $members = Member::all();

        foreach ($members as $member) {
            TagihanMember::firstOrCreate(
                [
                    'member_id' => $member->id,
                    'tanggal_tagihan' => $tanggalTagihan->toDateString(),
                ],
                [
                    'jumlah' => $member->harga,
                    'status' => 'belum',
                    'tanggal_bayar' => null,
                ]
            );

            // Setiap ada tagihan baru, member menjadi belum lunas
            $member->update([
                'status' => 'belum',
            ]);
        }

        $this->info('Tagihan member berhasil dibuat.');

        return Command::SUCCESS;
    }
}