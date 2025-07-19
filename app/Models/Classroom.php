<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'libelle',
        'description',
    ];

    // Une classe a plusieurs cours
    public function cours()
    {
        return $this->belongsToMany(Cour::class);
    }
 

}
