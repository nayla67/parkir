<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'kode',
        'qr_code',
        'nama',
        'perusahaan',
        'harga',
        'status',
        'expired_at',
    ];

    protected $casts = [
    'expired_at' => 'date',
];
}