<?php
// Script untuk regenerate semua QR code dari PNG ke SVG
// Jalankan dari root project: php regenerate_qr.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Meja;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$mejas = Meja::all();
$count = 0;

foreach ($mejas as $meja) {
    $url  = url('/menu/' . $meja->nomor_meja);
    $path = 'qrcodes/meja_' . $meja->id . '.svg';
    
    try {
        $qrCode = QrCode::format('svg')->size(300)->margin(2)->generate($url);
        Storage::disk('public')->put($path, $qrCode);
        $meja->update(['qr_code' => $path]);
        echo "✓ QR Code diperbarui: Meja {$meja->nomor_meja}\n";
        $count++;
    } catch (Exception $e) {
        echo "✗ Gagal untuk Meja {$meja->nomor_meja}: " . $e->getMessage() . "\n";
    }
}

echo "\nSelesai! {$count} QR Code berhasil dibuat/diperbarui.\n";
