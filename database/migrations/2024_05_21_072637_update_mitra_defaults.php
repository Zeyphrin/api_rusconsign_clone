<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('mitras')
            ->whereNull('jumlah_product')
            ->update(['jumlah_product' => 0]);

        DB::table('mitras')
            ->whereNull('jumlah_jasa')
            ->update(['jumlah_jasa' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
