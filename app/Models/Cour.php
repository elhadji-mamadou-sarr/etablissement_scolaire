<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'libelle',
        'credit',
        'volume',
        'semestre',
    ];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_cour_classroom')
            ->withPivot('classroom_id')
            ->withTimestamps();
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }


}
