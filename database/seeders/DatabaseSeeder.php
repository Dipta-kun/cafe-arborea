<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'mayang@gmail.com'],
            [
                'name'     => 'Mayang',
                'email'    => 'mayang@gmail.com',
                'password' => Hash::make('123456789'),
            ]
        );

        $this->call([
            KategoriSeeder::class,
            MenuSeeder::class,
            MejaSeeder::class,
        ]);
    }
}
