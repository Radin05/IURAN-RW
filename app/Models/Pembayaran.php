<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'tgl_pembayaran', 'year', 'month'];

    protected $casts = [
        'tgl_pembayaran' => 'datetime',  // memastikan kolom tgl_pembayaran menjadi objek Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
