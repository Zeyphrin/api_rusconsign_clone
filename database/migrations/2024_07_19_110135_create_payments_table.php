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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('user_id');
            $table->string('checkout_link');
            $table->string('external_id');
            $table->string('no_transaction');
            $table->integer('quantity');
            $table->string('invoice_url');
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreign('barang_id')->references('id')->on('barangs');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
