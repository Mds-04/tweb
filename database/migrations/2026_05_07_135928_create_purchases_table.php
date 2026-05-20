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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('events')->onDelete('cascade');
            $table->string('codice_ordine')->unique();
            $table->integer('num_biglietti');
            $table->string('metodo_pagamento');
            $table->decimal('totale', 10, 2);
            $table->decimal('prezzo_unitario', 8, 2);
            $table->boolean('sconto_applicato')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
