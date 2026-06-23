<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $minuman = Kategori::where('nama_kategori', 'Minuman')->first();
        $temanNgopi = Kategori::where('nama_kategori', 'Teman Ngopi')->first();
        $makanan = Kategori::where('nama_kategori', 'Makanan')->first();

        $menus = [
            // Minuman
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Air Mineral', 'harga' => 8000, 'stok' => 100, 'deskripsi' => 'Air mineral kemasan botol dingin dan segar'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Americano', 'harga' => 16000, 'stok' => 100, 'deskripsi' => 'Double shot espresso dengan air panas/dingin'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Bandrek', 'harga' => 12000, 'stok' => 100, 'deskripsi' => 'Minuman tradisional jahe hangat berempah'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Caffe latte', 'harga' => 23000, 'stok' => 100, 'deskripsi' => 'Espresso dengan steamed milk lembut dan creamy'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Cappuccino', 'harga' => 23000, 'stok' => 100, 'deskripsi' => 'Kombinasi seimbang espresso, susu hangat, dan foam tebal'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Coffe Jelly', 'harga' => 22000, 'stok' => 100, 'deskripsi' => 'Kopi dingin segar dengan tambahan topping jelly kopi kenyal'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Es Choco Selasi', 'harga' => 22000, 'stok' => 100, 'deskripsi' => 'Minuman cokelat segar ditambah biji selasih berkhasiat'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Es Teh Manis Gentong', 'harga' => 13000, 'stok' => 100, 'deskripsi' => 'Es teh manis khas gentong yang segar dan legit'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Hot/Icce Chocolate', 'harga' => 22000, 'stok' => 100, 'deskripsi' => 'Minuman cokelat premium disajikan hangat atau dingin'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Jeruk Peras/Jus Jeruk', 'harga' => 22000, 'stok' => 100, 'deskripsi' => 'Perasan jeruk segar murni kaya vitamin C'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Jus Semangka', 'harga' => 20000, 'stok' => 100, 'deskripsi' => 'Jus buah semangka segar pelindung dahaga'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Jus Alphukat', 'harga' => 22000, 'stok' => 100, 'deskripsi' => 'Jus buah alpukat kental dengan kental manis cokelat'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Jus Strawberry', 'harga' => 20000, 'stok' => 100, 'deskripsi' => 'Jus strawberry asam manis menyegarkan'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Kopi Sanger', 'harga' => 21000, 'stok' => 100, 'deskripsi' => 'Kopi hitam disaring tradisional dicampur susu kental manis'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Kopi Susu Arborea', 'harga' => 25000, 'stok' => 100, 'deskripsi' => 'Kopi susu gula aren khas racikan Café Arborea Jatijajar'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Kopi Susu Pandan', 'harga' => 23000, 'stok' => 100, 'deskripsi' => 'Es kopi susu beraroma wangi pandan manis alami'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Lemon Tea', 'harga' => 16000, 'stok' => 100, 'deskripsi' => 'Es teh dengan irisan lemon segar yang asam manis'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Lychee Tea', 'harga' => 18000, 'stok' => 100, 'deskripsi' => 'Es teh rasa leci dengan buah leci asli di dalamnya'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Matcha Series', 'harga' => 24000, 'stok' => 100, 'deskripsi' => 'Minuman matcha Jepang berkualitas tinggi dengan susu'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Mochaccino', 'harga' => 24000, 'stok' => 100, 'deskripsi' => 'Paduan seimbang espresso, susu, dan sirup cokelat lezat'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Regal Latte', 'harga' => 23000, 'stok' => 100, 'deskripsi' => 'Susu segar creamy dengan remahan biskuit Regal yang lezat'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Spanish Latte', 'harga' => 25000, 'stok' => 100, 'deskripsi' => 'Kopi susu ala Spanyol dengan tambahan susu kental manis yang gurih'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Teh Tawar Gentong', 'harga' => 8000, 'stok' => 100, 'deskripsi' => 'Teh tawar khas gentong disajikan hangat atau dingin'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Thai Tea', 'harga' => 20000, 'stok' => 100, 'deskripsi' => 'Minuman teh khas Thailand yang manis dan creamy'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Wedang Jahe', 'harga' => 12000, 'stok' => 100, 'deskripsi' => 'Minuman jahe hangat penambah imun dan stamina'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Wedang Uwuh', 'harga' => 12000, 'stok' => 100, 'deskripsi' => 'Minuman herbal tradisional Yogyakarta berwana merah alami'],
            ['kategori_id' => $minuman?->id, 'nama_menu' => 'Yakult Lychee', 'harga' => 23000, 'stok' => 100, 'deskripsi' => 'Minuman probiotik Yakult berpadu rasa leci manis segar'],

            // Teman Ngopi
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Cireng', 'harga' => 15000, 'stok' => 50, 'deskripsi' => 'Cireng crispy hangat disajikan lengkap dengan bumbu rujak pedas'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Donut', 'harga' => 16000, 'stok' => 50, 'deskripsi' => 'Donat empuk dengan taburan gula halus atau cokelat meses'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'French Friesh', 'harga' => 22000, 'stok' => 50, 'deskripsi' => 'Kentang goreng renyah disajikan dengan saus sambal & mayones'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Hihang Hoheng', 'harga' => 18000, 'stok' => 50, 'deskripsi' => 'Pisang goreng renyah khas Arborea Jatijajar'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Hinhong Hongheng', 'harga' => 18000, 'stok' => 50, 'deskripsi' => 'Singkong goreng mekar empuk bertabur keju parut gurih'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Mie / Kwitiau Goreng', 'harga' => 25000, 'stok' => 50, 'deskripsi' => 'Mie atau kwetiau goreng bumbu khas dengan telur dan sayuran'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Nasi Goreng', 'harga' => 25000, 'stok' => 50, 'deskripsi' => 'Nasi goreng bumbu lezat dengan telur mata sapi dan acar segar'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Pisang Coklat Lumer', 'harga' => 16000, 'stok' => 50, 'deskripsi' => 'Lumpia pisang coklat goreng garing dengan isi coklat meleleh'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Roti Maryam Coklat', 'harga' => 16000, 'stok' => 50, 'deskripsi' => 'Roti maryam hangat berlapis coklat manis nikmat'],
            ['kategori_id' => $temanNgopi?->id, 'nama_menu' => 'Roti Maryam Original', 'harga' => 15000, 'stok' => 50, 'deskripsi' => 'Roti maryam original dengan susu kental manis dan mentega'],

            // Makanan
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ayam Chili Oil', 'harga' => 21000, 'stok' => 30, 'deskripsi' => 'Ayam goreng empuk berlumur bumbu pedas gurih khas chili oil'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ayam Geprek', 'harga' => 21000, 'stok' => 30, 'deskripsi' => 'Ayam goreng krispi digeprek dengan sambal bawang ekstra pedas'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ayam Jeletot', 'harga' => 40000, 'stok' => 30, 'deskripsi' => 'Tumis ayam berbumbu rica-rica super pedas bikin nagih'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ayam Serundeng (Porsi Besar)', 'harga' => 40000, 'stok' => 30, 'deskripsi' => 'Satu porsi ayam serundeng kelapa gurih (cukup untuk 2-3 orang)'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ayam Serundeng (Porsi Sedang)', 'harga' => 23000, 'stok' => 30, 'deskripsi' => 'Ayam goreng serundeng porsi standar lengkap dengan kelapa parut gurih'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Cumi Cabai Hijau', 'harga' => 40000, 'stok' => 25, 'deskripsi' => 'Cumi asin dimasak pedas segar dengan irisan cabai hijau besar'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Iga Bakar', 'harga' => 45000, 'stok' => 20, 'deskripsi' => 'Iga sapi empuk dibakar dengan olesan saus madu kecap gurih'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Ikan Pecak', 'harga' => 40000, 'stok' => 20, 'deskripsi' => 'Ikan goreng disiram bumbu kuah pecak khas Betawi yang segar pedas'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Jukut Goreng', 'harga' => 7000, 'stok' => 50, 'deskripsi' => 'Selada air/jukut goreng renyah bumbu gurih khas Sunda'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Nasi Putih', 'harga' => 6000, 'stok' => 150, 'deskripsi' => 'Satu porsi nasi putih pulen hangat'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Oseng Mercon', 'harga' => 40000, 'stok' => 25, 'deskripsi' => 'Oseng kikil/daging sapi super pedas meledak di mulut'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Paru Balado (Porsi Besar)', 'harga' => 40000, 'stok' => 30, 'deskripsi' => 'Paru sapi goreng garing berbumbu balado cabai merah melimpah (porsi besar)'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Paru Balado (Porsi Sedang)', 'harga' => 23000, 'stok' => 30, 'deskripsi' => 'Paru goreng balado porsi standar pas untuk satu orang'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Sambal', 'harga' => 8000, 'stok' => 100, 'deskripsi' => 'Tambahan sambal korek/sambal bawang ekstra pedas'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Tahu/Tempe Goreng', 'harga' => 4000, 'stok' => 100, 'deskripsi' => 'Porsi pelengkap tahu dan tempe goreng gurih hangat'],
            ['kategori_id' => $makanan?->id, 'nama_menu' => 'Telur Gimbal', 'harga' => 8000, 'stok' => 100, 'deskripsi' => 'Telur dadar goreng crispy berbentuk gimbal renyah gurih'],
        ];

        foreach ($menus as $menu) {
            if ($menu['kategori_id']) {
                Menu::updateOrCreate(
                    ['nama_menu' => $menu['nama_menu']],
                    array_merge($menu, ['is_tersedia' => true])
                );
            }
        }
    }
}
