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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->text('deskrpsi');
            $table->integer('harga');
            $table->float('rating_barang') ;
            $table->foreignId('category_id');
            $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->string('image_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
