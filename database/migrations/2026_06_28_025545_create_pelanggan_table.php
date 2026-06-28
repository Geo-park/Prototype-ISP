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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('no_pelanggan')->unique();
            $table->string('nama');
            $table->text('alamat');
            $table->string('no_wa');
            $table->foreignId('paket_id')->constrained('paket_internet')->onDelete('restrict');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('pppoe_username')->unique();
            $table->string('pppoe_password');
            $table->date('tgl_aktivasi');
            $table->date('tgl_jatuh_tempo');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
