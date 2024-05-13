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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->string("nama_lengkap");
            $table->integer("nis");
            $table->string("no_dompet_digital");
            $table->string("image_id_card");
            $table->string("status");
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('mitraId')->nullable()->after('penilaian');

            $table->foreign('mitraId')
                ->references('id')
                ->on('mitras')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['mitraId']);

            $table->dropColumn('mitraId');
        });
    }
};
