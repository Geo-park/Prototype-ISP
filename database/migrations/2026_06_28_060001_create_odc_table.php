<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pop_olt_id')->constrained('pop_olt')->onDelete('cascade');
            $table->string('nama');
            $table->string('kode')->unique();
            $table->enum('level', ['L1', 'L2'])->default('L1');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('kapasitas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odc');
    }
};
