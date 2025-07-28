<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Eleve;
use App\Models\Note;
use App\Services\BulletinService;
use Illuminate\Http\Request;

class BulletinApiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'classe' => 'nullable|exists:classrooms,id',
            'semestre' => 'required|in:Semestre 1,Semestre 2'
        ]);

        $query = Eleve::with(['user', 'classroom'])
            ->when($request->classe, fn($q, $classe) => $q->where('classroom_id', $classe))
            ->orderBy('classroom_id')
            ->orderBy('user.nom');

        $eleves = $query->get();

        return response()->json([
            'eleves' => $eleves,
            'semestre' => $request->semestre
        ]);
    }

    public function show($eleveId, $semestre, BulletinService $service)
    {
        $bulletin = $service->genererBulletinComplet($eleveId, $semestre);

        if (!$bulletin) {
            return response()->json(['message' => 'Aucune note disponible pour ce semestre'], 404);
        }

        return response()->json($bulletin);
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'cour_id' => 'required|exists:cours,id',
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'required|in:devoir,composition,examen',
            'coefficient' => 'required|numeric|min:0.1|max:3',
            'semestre' => 'required|in:Semestre 1,Semestre 2',
            'date_evaluation' => 'required|date'
        ]);

        try {
            $eleve = Eleve::find($validated['eleve_id']);

            Note::create([
                ...$validated,
                'classroom_id' => $eleve->classroom_id,
                'enseignant_id' => auth()->user()->enseignant->id,
                'commentaire' => $request->commentaire
            ]);

            return response()->json(['message' => 'Note enregistrée avec succès'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }

    public function updateNote(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'sometimes|in:devoir,composition,examen'
        ]);

        $note = Note::findOrFail($id);
        $note->update($validated);

        return response()->json(['message' => 'Note mise à jour']);
    }
}
