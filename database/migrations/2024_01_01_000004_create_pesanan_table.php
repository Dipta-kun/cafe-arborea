<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan', 20)->unique();
            $table->foreignId('meja_id')->constrained('meja')->onDelete('cascade');
            $table->string('nama_pelanggan', 100);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->integer('total_item')->default(0);
            $table->enum('status', [
                'menunggu',
                'diproses',
                'siap_disajikan',
                'selesai',
                'dibatalkan'
            ])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamp('waktu_pesan')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('meja_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
