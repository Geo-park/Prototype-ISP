<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pop_olt', function (Blueprint $table) {
            $table->string('daerah')->nullable()->after('kapasitas');
        });

        Schema::table('odc', function (Blueprint $table) {
            $table->string('daerah')->nullable()->after('kapasitas');
        });

        Schema::table('odp', function (Blueprint $table) {
            $table->string('daerah')->nullable()->after('kapasitas');
        });
    }

    public function down(): void
    {
        Schema::table('pop_olt', function (Blueprint $table) {
            $table->dropColumn('daerah');
        });
        Schema::table('odc', function (Blueprint $table) {
            $table->dropColumn('daerah');
        });
        Schema::table('odp', function (Blueprint $table) {
            $table->dropColumn('daerah');
        });
    }
};
