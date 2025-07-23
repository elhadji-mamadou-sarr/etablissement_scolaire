<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'eleve_id',
        'cour_id',
        'classroom_id',
        'enseignant_id', // ➕ Ajoute cette ligne
        'note',          // Remplace "valeur" par "note"
        'semestre',
    ];

    public function eleve() {
        return $this->belongsTo(Eleve::class);
    }

    public function cour() {
        return $this->belongsTo(Cour::class);
    }

    public function enseignant() {
        return $this->belongsTo(Enseignant::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
