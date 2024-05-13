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
        Schema::create('jasas', function (Blueprint $table) {
            $table->id("jasaId");
            $table->string('name_jasa');
            $table->text('desc_jasa');
            $table->decimal('price_jasa', 10, 2);
            $table->float('rating_jasa');
            $table->foreignId('mitraId');
            $table->string('image_jasa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasas');
    }
};
