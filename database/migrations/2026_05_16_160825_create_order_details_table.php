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
    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        // Menghubungkan detail ini ke ID Transaksi di tabel orders
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        $table->string('product_name'); // Menyimpan nama menu yang dibeli
        $table->integer('price');        // Menyimpan harga satuan saat dibeli
        $table->integer('qty');          // Menyimpan jumlah item yang dibeli
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
