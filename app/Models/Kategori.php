<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'icon',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_kategori);
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty('nama_kategori')) {
                $model->slug = Str::slug($model->nama_kategori);
            }
        });
    }

    // Relations
    public function menu()
    {
        return $this->hasMany(Menu::class, 'kategori_id');
    }

    public function menuTersedia()
    {
        return $this->hasMany(Menu::class, 'kategori_id')->where('is_tersedia', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
