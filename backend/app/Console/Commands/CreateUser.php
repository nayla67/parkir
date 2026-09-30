<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * Nama & signature command.
     * Contoh pemakaian:
     *   php artisan user:create
     */
    protected $signature = 'user:create';

    /**
     * Deskripsi command (muncul di php artisan list).
     */
    protected $description = 'Membuat akun user baru (admin/petugas)';

    public function handle()
    {
        $this->info('=== Buat Akun Baru ===');

        $name = $this->ask('Nama lengkap');

        $email = $this->ask('Email');

        $password = $this->secret('Password (input tersembunyi)');

        $role = $this->choice(
            'Pilih role',
            ['admin', 'petugas'],
            1 // default index (petugas)
        );

        // Validasi input
        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ],
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            $this->error('Gagal membuat user:');
            foreach ($validator->errors()->all() as $err) {
                $this->line('- ' . $err);
            }
            return Command::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $this->newLine();
        $this->info('✅ User berhasil dibuat!');
        $this->table(
            ['ID', 'Nama', 'Email', 'Role'],
            [[$user->id, $user->name, $user->email, $user->role]]
        );

        return Command::SUCCESS;
    }
}