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
        Schema::create('cod', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasis')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('status_pembayaran');
            $table->string('grand_total');
            $table->string('user_status_pembayaran')->default('pending');
            $table->string('mitra_status_pembayaran')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cod');
    }
};
