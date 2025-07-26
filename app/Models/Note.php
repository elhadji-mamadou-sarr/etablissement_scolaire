<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Note extends Model
{
    
    protected $fillable = [
        'eleve_id', 'cour_id', 'classroom_id', 'enseignant_id',
        'note', 'semestre', 'type_note', 'coefficient', 'date_evaluation', 'commentaire'
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'note' => 'decimal:2',
        'coefficient' => 'float'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            // Validation de la note
            if ($note->note < 0 || $note->note > 20) {
                throw new \Exception("La note doit être entre 0 et 20");
            }

            // Vérification que l'enseignant peut noter ce cours
            $canTeach = \DB::table('enseignant_cour_classroom')
                ->where('enseignant_id', $note->enseignant_id)
                ->where('cour_id', $note->cour_id)
                ->where('classroom_id', $note->classroom_id)
                ->exists();

            if (!$canTeach) {
                throw new \Exception("Cet enseignant n'est pas autorisé à noter ce cours dans cette classe");
            }
        });
    }

    // Relations
    public function eleve() {
        return $this->belongsTo(Eleve::class);
    }

    public function cour() {
        return $this->belongsTo(Cour::class)->withDefault([
            'libelle' => 'Matière supprimée',
            'credit' => 1
        ]);
    }

    public function enseignant() {
        return $this->belongsTo(Enseignant::class)->withDefault([
            'user_id' => null
        ]);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

}