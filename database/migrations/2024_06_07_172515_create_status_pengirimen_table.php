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
        Schema::create('status_pengirimen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status');
            $table->foreignId('user_id');
            $table->foreignId('mitra_id');
            $table->foreignId('barang_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pengirimen');
    }
};
