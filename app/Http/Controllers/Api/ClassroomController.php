<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
     
    public function index()
    {
        return Classroom::with('cours')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'string|max:555',
        ]);

        return Classroom::create($request->all());
    }


    public function show(Classroom $classeroom)
    {
        return $classeroom;
    }


    public function update(Request $request, Classroom $classeroom)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'string|max:555',
        ]);

        $classeroom->update($request->all());
        return $classeroom;
    }


    public function attachCour(Request $request, Classroom $classroom)
    {
        $request->validate([
            'cour_id' => 'required|exists:cours,id',
        ]);

        if (!$classroom->cours()->where('cours.id', $request->cour_id)->exists()) {
            $classroom->cours()->attach($request->cour_id);
            return response()->json(['message' => 'Cours attaché avec succès.']);
        } else {
            return response()->json(['message' => 'Ce cours est déjà attaché.'], 409);
        }
        
    }


    public function detachCour(Request $request, Classroom $classroom)
    {
        $request->validate([
            'cour_id' => 'required|exists:cours,id',
        ]);

        $classroom->cours()->detach($request->cour_id);

        return response()->json(['message' => 'Cours détaché avec succès.']);
    }


    public function listCours(Classroom $classroom)
    {
        return $classroom->cours;
    }


    public function destroy(Classroom $classeroom)
    {
        $classeroom->delete();
        return response()->json(['message' => 'Supprimé avec succès']);
    }
    
}
