<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_ots', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_code'); // Nomor kupon / tiket
            $table->string('payment_method'); // cash, qris, transfer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_ots');
    }
};