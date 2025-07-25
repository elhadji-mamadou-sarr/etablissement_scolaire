<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Cour;
use App\Models\Classroom;
use App\Models\Enseignant;

class NoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enseignant = $user?->enseignant;

        if (!$enseignant) {
            return back()->withErrors(['error' => 'Aucun enseignant associé à ce compte.']);
        }

        // Récupération des cours et classes liés à cet enseignant
        $cours = $enseignant->cours;
        $classrooms = $enseignant->classrooms;

        // Tous les élèves (tu peux filtrer si nécessaire par classe)
        $eleves = Eleve::with('user', 'classroom')->get();

        // Toutes les notes saisies par cet enseignant
        $notes = $enseignant->notes()->with(['eleve.user', 'cour', 'classroom'])->get();

        return view('enseignant.notes.index', compact('cours', 'classrooms', 'eleves', 'notes'));
    }

public function store(Request $request)
{
    $request->validate([
        'eleve_id' => 'required|exists:eleves,id',
        'cour_id' => 'required|exists:cours,id',
        'semestre' => 'required|string',
        'note' => 'required|numeric|min:0|max:20',
    ]);

    $user = auth()->user();

    // Vérifie si l'utilisateur a bien un enseignant lié
    $enseignant = $user->enseignant ?? Enseignant::where('user_id', $user->id)->first();

    if (!$enseignant || !$enseignant->id) {
        return back()->with('error', 'Aucun enseignant lié à ce compte.');
    }

    // Récupère la classe de l’élève
    $eleve = Eleve::findOrFail($request->eleve_id);

    Note::create([
        'eleve_id'      => $eleve->id,
        'cour_id'       => $request->cour_id,
        'classroom_id'  => $eleve->classroom_id,
        'enseignant_id' => $enseignant->id,
        'note'          => $request->note,
        'semestre'      => $request->semestre,
    ]);

    return back()->with('success', 'Note enregistrée avec succès.');
}


public function update(Request $request, Note $note)
{
    $request->validate([
        'eleve_id' => 'required|exists:eleves,id',
        'cour_id' => 'required|exists:cours,id',
        'semestre' => 'required|string',
        'note' => 'required|numeric',
    ]);

    $eleve = Eleve::findOrFail($request->eleve_id);
    $classroom_id = $eleve->classroom_id;

    $note->update([
        'eleve_id' => $request->eleve_id,
        'cour_id' => $request->cour_id,
        'classroom_id' => $classroom_id,
        'semestre' => $request->semestre,
        'note' => $request->note,
    ]);

    return back()->with('success', 'Note modifiée avec succès.');
}
}