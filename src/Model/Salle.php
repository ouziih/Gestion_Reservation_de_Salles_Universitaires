<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $table = 'salles';

    protected $fillable = [
        'nom',
        'batiment',
        'capacite',
        'type',
        'active',
    ];

    protected $casts = [
        'capacite' => 'integer',
        'active' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}