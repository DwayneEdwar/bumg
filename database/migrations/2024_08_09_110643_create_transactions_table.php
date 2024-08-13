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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('unit_id',15);
            $table->date('tanggal_transaksi');
            $table->string('jenis_transaksi');
            $table->integer('quantity');
            $table->string('satuan');
            $table->integer('harga_satuan');
            $table->integer('total');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
