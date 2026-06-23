<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menu')->onDelete('cascade');
            $table->string('nama_menu_snapshot', 150); // Snapshot saat pesan
            $table->decimal('harga_snapshot', 10, 2);  // Snapshot harga saat pesan
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan_item')->nullable();
            $table->timestamps();

            $table->index('pesanan_id');
            $table->index('menu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
