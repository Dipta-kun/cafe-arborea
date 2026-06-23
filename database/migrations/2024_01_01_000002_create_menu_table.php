<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->string('nama_menu', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0);
            $table->string('foto')->nullable();
            $table->boolean('is_tersedia')->default(true);
            $table->integer('terjual')->default(0);
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('is_tersedia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
