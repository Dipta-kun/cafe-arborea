<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan',
        'meja_id',
        'nama_pelanggan',
        'total_harga',
        'total_item',
        'status',
        'catatan',
        'waktu_pesan',
        'waktu_selesai',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    protected $casts = [
        'total_harga'   => 'decimal:2',
        'total_item'    => 'integer',
        'waktu_pesan'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    protected $appends = [
        'total_harga_format', 
        'status_label', 
        'status_color',
        'payment_status_label',
        'payment_status_color',
        'payment_method_label'
    ];

    // Status constants
    const STATUS_MENUNGGU      = 'menunggu';
    const STATUS_DIPROSES      = 'diproses';
    const STATUS_SIAP          = 'siap_disajikan';
    const STATUS_SELESAI       = 'selesai';
    const STATUS_DIBATALKAN    = 'dibatalkan';

    // Accessors
    public function getTotalHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'menunggu'      => 'Menunggu',
            'diproses'      => 'Diproses',
            'siap_disajikan'=> 'Siap Disajikan',
            'selesai'       => 'Selesai',
            'dibatalkan'    => 'Dibatalkan',
            default         => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'menunggu'      => 'warning',
            'diproses'      => 'info',
            'siap_disajikan'=> 'primary',
            'selesai'       => 'success',
            'dibatalkan'    => 'danger',
            default         => 'secondary',
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->status_pembayaran) {
            'belum_bayar' => 'Belum Bayar',
            'lunas'       => 'Lunas',
            default       => ucfirst($this->status_pembayaran),
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->status_pembayaran) {
            'belum_bayar' => 'danger',
            'lunas'       => 'success',
            default       => 'secondary',
        };
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->metode_pembayaran) {
            'qris'  => 'QRIS (Bayar Instan)',
            'kasir' => 'Bayar di Kasir',
            default => ucfirst($this->metode_pembayaran),
        };
    }

    // Relations
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    // Scopes
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Generate kode pesanan unik
    public static function generateKode()
    {
        $prefix = 'ARB';
        $date   = date('ymd');
        $last   = self::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($last, 3, '0', STR_PAD_LEFT);
    }
}
