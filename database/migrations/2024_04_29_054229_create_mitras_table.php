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
                $table->foreignId("user_id")->default(0);
                $table->string("nama_lengkap");
                $table->integer("nis");
                $table->string("no_dompet_digital");
                $table->string("image_id_card");
                $table->string("status");
                $table->integer("pengikut")->nullable();
                $table->integer("jumlah_jasa")->default(0);
                $table->integer("jumlah_product")->default(0);
                $table->float("penilaian")->default(0);;
                $table->rememberToken();
                $table->timestamps();
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
