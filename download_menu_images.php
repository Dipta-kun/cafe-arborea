<?php

/**
 * Download Menu Images from Unsplash (source.unsplash.com)
 * Run: php download_menu_images.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

// ─── Mapping menu → query Unsplash ───────────────────────────────────────────
$menuImages = [
    // ── MINUMAN ──────────────────────────────────────────────────────────────
    'Air Mineral'           => 'mineral water bottle cold',
    'Americano'             => 'americano coffee black espresso',
    'Bandrek'               => 'ginger drink traditional indonesian warm spice',
    'Caffe latte'           => 'cafe latte coffee milk art',
    'Cappuccino'            => 'cappuccino coffee foam art',
    'Coffe Jelly'           => 'coffee jelly drink glass',
    'Es Choco Selasi'       => 'chocolate milk drink iced basil seed',
    'Es Teh Manis Gentong'  => 'iced sweet tea glass',
    'Hot/Icce Chocolate'    => 'hot chocolate drink mug cocoa',
    'Jeruk Peras/Jus Jeruk' => 'fresh orange juice glass',
    'Jus Semangka'          => 'watermelon juice fresh red glass',
    'Jus Alphukat'          => 'avocado juice smoothie green creamy',
    'Jus Strawberry'        => 'strawberry juice smoothie pink fresh',
    'Kopi Sanger'           => 'drip coffee milk condensed sweet',
    'Kopi Susu Arborea'     => 'iced coffee milk palm sugar indonesian',
    'Kopi Susu Pandan'      => 'pandan coffee milk green iced',
    'Lemon Tea'             => 'lemon iced tea fresh glass',
    'Lychee Tea'            => 'lychee tea iced fruit glass',
    'Matcha Series'         => 'matcha latte green tea japanese',
    'Mochaccino'            => 'mocha coffee chocolate milk drink',
    'Regal Latte'           => 'biscuit cookie milk latte drink',
    'Spanish Latte'         => 'spanish latte iced coffee condensed milk',
    'Teh Tawar Gentong'     => 'plain tea traditional indonesian glass',
    'Thai Tea'              => 'thai iced tea orange creamy glass',
    'Wedang Jahe'           => 'ginger tea warm traditional drink',
    'Wedang Uwuh'           => 'herbal spice drink red traditional java',
    'Yakult Lychee'         => 'yakult probiotic lychee drink glass',

    // ── TEMAN NGOPI ──────────────────────────────────────────────────────────
    'Cireng'                => 'cireng fried cassava snack indonesian',
    'Donut'                 => 'donut glazed sugar bakery',
    'French Friesh'         => 'french fries crispy golden plate',
    'Hihang Hoheng'         => 'banana fritter goreng fried sweet',
    'Hinhong Hongheng'      => 'fried cassava singkong cheese topping',
    'Mie / Kwitiau Goreng'  => 'fried noodles kwetiau stir fry egg',
    'Nasi Goreng'           => 'nasi goreng fried rice indonesian egg',
    'Pisang Coklat Lumer'   => 'banana chocolate spring roll fried dessert',
    'Roti Maryam Coklat'    => 'roti maryam paratha chocolate sweet',
    'Roti Maryam Original'  => 'roti maryam paratha butter condensed milk',

    // ── MAKANAN ──────────────────────────────────────────────────────────────
    'Ayam Chili Oil'                => 'fried chicken chili oil spicy crispy',
    'Ayam Geprek'                   => 'ayam geprek smashed fried chicken sambal',
    'Ayam Jeletot'                  => 'spicy stir fry chicken rica rica',
    'Ayam Serundeng (Porsi Besar)'  => 'serundeng fried chicken coconut spice large',
    'Ayam Serundeng (Porsi Sedang)' => 'fried chicken coconut serundeng portion',
    'Cumi Cabai Hijau'              => 'squid squid green chili stir fry seafood',
    'Iga Bakar'                     => 'grilled beef ribs barbeque plate',
    'Ikan Pecak'                    => 'fried fish pecak sambal betawi',
    'Jukut Goreng'                  => 'fried vegetables water spinach crispy sundanese',
    'Nasi Putih'                    => 'white rice steamed plain bowl',
    'Oseng Mercon'                  => 'oseng mercon spicy stir fry beef tendon',
    'Paru Balado (Porsi Besar)'     => 'paru balado beef lung spicy red large',
    'Paru Balado (Porsi Sedang)'    => 'paru balado beef lung fried spicy',
    'Sambal'                        => 'sambal chili sauce indonesian spicy',
    'Tahu/Tempe Goreng'             => 'tofu tempe fried golden crispy',
    'Telur Gimbal'                  => 'fried egg omelette crispy indonesian',
];

// ─── Ensure storage directory exists ─────────────────────────────────────────
$storageDir = __DIR__ . '/storage/app/public/menu';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
    echo "✅ Created directory: storage/app/public/menu\n";
}

// ─── Download helper ──────────────────────────────────────────────────────────
function downloadImage(string $query, string $savePath): bool
{
    $width  = 600;
    $height = 600;

    // Use Unsplash source (no API key needed)
    $encoded = urlencode($query);
    $url     = "https://source.unsplash.com/{$width}x{$height}/?{$encoded}";

    $context = stream_context_create([
        'http' => [
            'timeout'          => 20,
            'follow_location'  => 1,
            'max_redirects'    => 5,
            'user_agent'       => 'Mozilla/5.0 (compatible; PSI-MenuDownloader/1.0)',
        ],
        'ssl' => [
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ],
    ]);

    $data = @file_get_contents($url, false, $context);

    if ($data === false || strlen($data) < 5000) {
        // Fallback: use picsum.photos with deterministic seed from query
        $seed    = abs(crc32($query)) % 1000;
        $fallback = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
        $data    = @file_get_contents($fallback, false, $context);
    }

    if ($data === false || strlen($data) < 1000) {
        return false;
    }

    return file_put_contents($savePath, $data) !== false;
}

// ─── Main loop ────────────────────────────────────────────────────────────────
$allMenus = Menu::all();
$total    = count($allMenus);
$success  = 0;
$skipped  = 0;
$failed   = 0;

echo "\n🌿 Café Arborea – Menu Image Downloader\n";
echo str_repeat('─', 55) . "\n";
echo "Total menu: {$total} item\n\n";

foreach ($allMenus as $menu) {
    $name    = $menu->nama_menu;
    $query   = $menuImages[$name] ?? null;

    // If no query found, build one from the name itself
    if (!$query) {
        $query = strtolower($name) . ' food drink indonesian cafe';
    }

    // Sanitize filename
    $slug     = preg_replace('/[^a-z0-9]+/', '_', strtolower($name));
    $slug     = trim($slug, '_');
    $filename = "menu/{$slug}.jpg";
    $fullPath = $storageDir . "/{$slug}.jpg";

    // Skip if already downloaded
    if (file_exists($fullPath) && filesize($fullPath) > 5000) {
        echo "  ⏭️  SKIP  – {$name} (sudah ada)\n";
        $skipped++;

        // Still update DB if foto column is empty
        if (empty($menu->foto)) {
            $menu->update(['foto' => $filename]);
        }
        continue;
    }

    echo "  ⬇️  Downloading – {$name} … ";
    $ok = downloadImage($query, $fullPath);

    if ($ok) {
        $menu->update(['foto' => $filename]);
        echo "✅\n";
        $success++;
    } else {
        echo "❌ GAGAL\n";
        $failed++;
    }

    // Small delay to avoid rate limiting
    usleep(400000); // 0.4 seconds
}

echo "\n" . str_repeat('─', 55) . "\n";
echo "✅ Berhasil : {$success}\n";
echo "⏭️  Dilewati : {$skipped}\n";
echo "❌ Gagal    : {$failed}\n";
echo "\nSelesai! Jalankan: php artisan storage:link (jika belum)\n\n";
