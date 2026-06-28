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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoice')->onDelete('restrict');
            $table->timestamp('tgl_bayar')->nullable();
            $table->enum('metode', ['QRIS', 'virtual_account', 'transfer'])->nullable();
            $table->decimal('jumlah', 10, 2)->nullable();
            $table->string('referensi')->nullable();
            $table->enum('status', ['success', 'pending', 'failed', 'expired'])->default('pending');
            $table->json('callback_raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
