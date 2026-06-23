<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_meja', 10)->unique();
            $table->string('nama_meja', 50)->nullable();
            $table->integer('kapasitas')->default(4);
            $table->enum('status', ['tersedia', 'terisi', 'tidak_aktif'])->default('tersedia');
            $table->string('qr_code')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
