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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizzatore_id')->constrained('users')->onDelete('cascade');
            $table->string('titolo');
            $table->text('descrizione');
            $table->text('programma');
            $table->date('data');
            $table->time('orario');
            $table->string('citta');
            $table->string('luogo');
            $table->text('come_raggiungere')->nullable();
            $table->string('immagine')->nullable();
            $table->string('categoria');
            $table->decimal('prezzo', 8, 2);
            $table->integer('biglietti_totali');
            $table->integer('biglietti_disponibili');
            $table->integer('sconto_giorni')->nullable();
            $table->decimal('sconto_percentuale', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
