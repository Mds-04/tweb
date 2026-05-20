<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'cliente_id',
        'evento_id',
        'codice_ordine',
        'num_biglietti',
        'totale',
        'prezzo_unitario',
        'metodo_pagamento',
        'sconto_applicato',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function evento()
    {
        return $this->belongsTo(Event::class, 'evento_id');
    }
}
