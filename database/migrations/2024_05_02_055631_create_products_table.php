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
        Schema::create('products', function (Blueprint $table) {
            $table->id('productId'); // Primary key with a custom name
            $table->string('name_product'); // Consistent naming with controller
            $table->text('desc_product'); // Consistent naming with controller
            $table->integer('price_product'); // Consistent naming with controller
            $table->float('rating_product'); // Consistent naming with controller
            $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade'); // Ensure consistency and add cascade delete
            $table->string('image'); // Consistent naming with controller
            $table->timestamps(); // Add created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
