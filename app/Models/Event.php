<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizzatore_id',
        'titolo',
        'descrizione',
        'programma',
        'data',
        'orario',
        'citta',
        'luogo',
        'come_raggiungere',
        'immagine',
        'categoria',
        'prezzo',
        'biglietti_totali',
        'biglietti_disponibili',
        'sconto_giorni',
        'sconto_percentuale',
    ];

    public function organizzatore()
    {
        return $this->belongsTo(User::class, 'organizzatore_id');
    }

    public function acquisti()
    {
        return $this->hasMany(Purchase::class, 'evento_id');
    }

    public function partecipanti()
    {
        return $this->belongsToMany(User::class, 'participations', 'evento_id', 'cliente_id')->withTimestamps();
    }
}
