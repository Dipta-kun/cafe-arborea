<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        $mejas = [];
        for ($i = 1; $i <= 15; $i++) {
            $mejas[] = [
                'nomor_meja' => str_pad($i, 2, '0', STR_PAD_LEFT),
                'nama_meja'  => 'Meja ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'kapasitas'  => ($i <= 10) ? 4 : 6,
                'status'     => 'tersedia',
                'keterangan' => ($i <= 10) ? 'Area indoor' : 'Area outdoor',
            ];
        }

        foreach ($mejas as $meja) {
            Meja::updateOrCreate(
                ['nomor_meja' => $meja['nomor_meja']],
                $meja
            );
        }
    }
}
