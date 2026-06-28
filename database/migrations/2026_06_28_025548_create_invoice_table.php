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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('restrict');
            $table->foreignId('paket_id')->constrained('paket_internet')->onDelete('restrict');
            $table->string('periode');
            $table->date('tgl_invoice');
            $table->date('tgl_jatuh_tempo');
            $table->string('nama_paket');
            $table->string('bandwidth');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('persen_pajak', 5, 2);
            $table->decimal('nominal_pajak', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->string('duitku_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
