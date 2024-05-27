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
            $table->id('jasaId'); // Primary key with a custom name
            $table->string('name_jasa'); // Consistent naming with controller
            $table->text('desc_jasa'); // Consistent naming with controller
            $table->decimal('price_jasa', 10, 2); // Consistent naming with controller
            $table->float('rating_jasa'); // Consistent naming with controller
            $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade'); // Ensure consistency and add cascade delete
            $table->string('image_jasa'); // Consistent naming with controller
            $table->timestamps(); // Add created_at and updated_at timestamps
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
