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
            $table->enum('metode_pembayaran', ['qris', 'kasir'])->default('kasir')->after('total_item');
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas'])->default('belum_bayar')->after('metode_pembayaran');
            
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
