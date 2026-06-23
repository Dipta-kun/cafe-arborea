<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'nama_menu_snapshot',
        'harga_snapshot',
        'jumlah',
        'subtotal',
        'catatan_item',
    ];

    protected $casts = [
        'harga_snapshot' => 'decimal:2',
        'subtotal'       => 'decimal:2',
        'jumlah'         => 'integer',
    ];

    protected $appends = ['subtotal_format'];

    // Accessors
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Relations
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
