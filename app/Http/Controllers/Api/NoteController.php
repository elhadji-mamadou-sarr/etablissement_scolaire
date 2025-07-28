<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return Note::with(['eleve', 'cour', 'classroom', 'enseignant'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'cour_id' => 'required|exists:cours,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'note' => 'required|numeric|min:0|max:20',
            'semestre' => 'required|string',
            'type_note' => 'required|string',
            'coefficient' => 'required|numeric|min:0',
            'date_evaluation' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        return Note::create($validated);
    }

    public function show(Note $note)
    {
        return $note->load(['eleve', 'cour', 'classroom', 'enseignant']);
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'note' => 'sometimes|numeric|min:0|max:20',
            'semestre' => 'sometimes|string',
            'type_note' => 'sometimes|string',
            'coefficient' => 'sometimes|numeric|min:0',
            'date_evaluation' => 'sometimes|date',
            'commentaire' => 'nullable|string',
        ]);

        $note->update($validated);

        return $note;
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->noContent();
    }
}
