<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isEnseignant()) {
            return response()->json([
                'message' => 'Accès réservé aux enseignants'
            ], 403);
        }
    
        $enseignant = $user->enseignant;
    
        // Récupérer les données avec les mêmes filtres
        $notes = $enseignant->notes()
                         ->with(['eleve.user', 'cour', 'classroom'])
                         ->when(request('classe'), function($q, $classe) {
                             return $q->where('classroom_id', $classe);
                         })
                         ->when(request('cours'), function($q, $cours) {
                             return $q->where('cour_id', $cours);
                         })
                         ->when(request('semestre'), function($q, $semestre) {
                             return $q->where('semestre', $semestre);
                         })
                         ->latest()
                         ->get();
    
       
        // Récupérer les options de filtre
        $filters = [
            'classes' => $enseignant->classrooms->map(function($classroom) {
                return ['id' => $classroom->id, 'libelle' => $classroom->libelle];
            }),
            'cours' => $enseignant->cours->map(function($cour) {
                return ['id' => $cour->id, 'libelle' => $cour->libelle];
            }),
            'semestres' => ['Semestre 1', 'Semestre 2'] // Vous pouvez adapter selon votre système
        ];
    
            
        return response()->json([
            'notes' => $notes,
            'cours' => $enseignant->cours,
            'classes' => $enseignant->classrooms
        ]);
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'cour_id' => 'required|exists:cours,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'note' => 'required|numeric|min:0|max:20',
            'semestre' => 'required|string',
            'type_note' => 'required|string',
            'coefficient' => 'required|numeric|min:0',
            'date_evaluation' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $validated['enseignant_id'] = auth()->user()->id;

        return Note::create($validated);
    }


    public function findCoursByEnseignant(Enseignant $enseignant){

        return response()->json([
            'cours' => $enseignant->cours
        ]);
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
