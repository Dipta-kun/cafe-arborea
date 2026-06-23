<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'kategori_id',
        'nama_menu',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'is_tersedia',
        'terjual',
    ];

    protected $casts = [
        'harga'       => 'decimal:2',
        'stok'        => 'integer',
        'terjual'     => 'integer',
        'is_tersedia' => 'boolean',
    ];

    protected $appends = ['foto_url', 'harga_format'];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_menu);
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty('nama_menu')) {
                $model->slug = Str::slug($model->nama_menu);
            }
        });
    }

    // Accessors
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/menu-placeholder.jpg');
    }

    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format((float) ($this->harga ?? 0), 0, ',', '.');
    }

    // Relations
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'menu_id');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('is_tersedia', true)->where('stok', '>', 0);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }
}
