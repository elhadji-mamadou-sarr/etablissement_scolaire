<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourResquest;
use App\Models\Cour;
use Illuminate\Http\Request;


class CourController extends Controller
{

    public function index()
    {
        return Cour::with('classrooms')->get();
    }


    public function store(CourResquest $request)
    {
        return Cour::create($request->all());
    }

    public function show(Cour $cour)
    {
        return $cour;
    }


    public function update(CourResquest $request, Cour $cour)
    {
        $cour->update($request->all());
        return $cour;
    }
    

    public function destroy(Cour $cour)
    {
        $cour->delete();
        return response()->json(['message' => 'Supprimé avec succès']);
    }

    
}
