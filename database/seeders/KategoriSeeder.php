<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Minuman',     'icon' => 'bi-cup-straw',  'urutan' => 1, 'deskripsi' => 'Pilihan kopi, non-kopi, dan minuman segar'],
            ['nama_kategori' => 'Teman Ngopi', 'icon' => 'bi-cookie',     'urutan' => 2, 'deskripsi' => 'Camilan, roti, dan hidangan pendamping'],
            ['nama_kategori' => 'Makanan',     'icon' => 'bi-egg-fried',  'urutan' => 3, 'deskripsi' => 'Makanan utama dan hidangan berat'],
        ];

        foreach ($kategori as $item) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
