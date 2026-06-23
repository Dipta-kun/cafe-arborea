<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('metode_pembayaran', 20)->default('kasir'); // qris, kasir
            $table->string('status_pembayaran', 20)->default('belum_bayar'); // belum_bayar, lunas
            
            $table->index('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropIndex(['status_pembayaran']);
            $table->dropColumn(['metode_pembayaran', 'status_pembayaran']);
        });
    }
};
