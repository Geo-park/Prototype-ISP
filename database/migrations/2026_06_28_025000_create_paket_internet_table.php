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
        Schema::create('paket_internet', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga', 10, 2);
            $table->decimal('persen_pajak', 5, 2)->default(11);
            $table->integer('bandwidth_up');
            $table->integer('bandwidth_down');
            $table->enum('satuan', ['Mbps', 'Kbps'])->default('Mbps');
            $table->integer('masa_aktif')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_internet');
    }
};
