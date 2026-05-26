<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'livello',
        'nome',
        'cognome',
        'email',
        'username',
        'password',
        'data_nascita',
        'organizzazione',
        'telefono',
    ];

    public function eventiOrganizzati()
    {
        return $this->hasMany(Event::class, 'organizzatore_id');
    }

    public function acquisti()
    {
        return $this->hasMany(Purchase::class, 'cliente_id');
    }

    public function partecipazioni()
    {
        return $this->belongsToMany(Event::class, 'participations', 'cliente_id', 'evento_id')->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
