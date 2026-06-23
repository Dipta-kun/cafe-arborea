<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $fillable = [
        'nomor_meja',
        'nama_meja',
        'kapasitas',
        'status',
        'qr_code',
        'keterangan',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    // Relations
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'meja_id');
    }

    public function pesananAktif()
    {
        return $this->hasMany(Pesanan::class, 'meja_id')
            ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Accessors
    public function getQrCodeUrlAttribute()
    {
        return url('/menu/' . $this->nomor_meja);
    }
}
