<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'salle_id' => 'integer',
        'date_debut' => 'immutable_datetime',
        'date_fin' => 'immutable_datetime',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}