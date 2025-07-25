<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function cours()
{
    return $this->belongsToMany(Cour::class, 'enseignant_cour_classroom')
        ->withPivot('classroom_id')
        ->withTimestamps();
}

public function classrooms()
{
    return $this->belongsToMany(Classroom::class);
}

public function coursClassrooms()
{
    return \DB::table('enseignant_cour_classroom')
        ->join('cours', 'enseignant_cour_classroom.cour_id', '=', 'cours.id')
        ->join('classrooms', 'enseignant_cour_classroom.classroom_id', '=', 'classrooms.id')
        ->where('enseignant_cour_classroom.enseignant_id', $this->id)
        ->select('cours.libelle as cours', 'classrooms.libelle as classe')
        ->get();
}

public function notes() {
    return $this->hasMany(Note::class);
}



}

