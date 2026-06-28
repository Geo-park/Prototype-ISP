<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odc_id')->constrained('odc')->onDelete('cascade');
            $table->string('nama');
            $table->string('kode')->unique();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('kapasitas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odp');
    }
};
