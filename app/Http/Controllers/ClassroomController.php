<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Cour;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with('cours')->get();
        $cours = Cour::all(); // pour le select
        return view('classes.index', compact('classrooms', 'cours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required',
            'description' => 'required',
            'cours' => 'nullable|array'
        ]);

        $classroom = Classroom::create($validated);
        $classroom->cours()->sync($request->input('cours', []));

        return back()->with('success', 'Classe ajoutée avec succès.');
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'libelle' => 'required',
            'description' => 'required',
            'cours' => 'nullable|array'
        ]);

        $classroom->update($validated);
        $classroom->cours()->sync($request->input('cours', []));

        return back()->with('success', 'Classe modifiée avec succès.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return back()->with('success', 'Classe supprimée.');
    }
    
}
