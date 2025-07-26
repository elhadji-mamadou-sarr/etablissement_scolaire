<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = ['user_id', 'classroom_id', 'matricule'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public function moyenneParMatiere($courId)
    {
        return $this->notes()
            ->where('cour_id', $courId)
            ->avg('note');
    }


}
