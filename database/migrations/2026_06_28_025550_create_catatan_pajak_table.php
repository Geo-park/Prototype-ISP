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
        Schema::create('catatan_pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->onDelete('restrict');
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('restrict');
            $table->string('no_faktur')->unique();
            $table->date('tgl_faktur');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('persen_pajak', 5, 2);
            $table->decimal('nominal_pajak', 10, 2);
            $table->decimal('total', 10, 2);
            $table->boolean('dikirim_mekari')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pajak');
    }
};
