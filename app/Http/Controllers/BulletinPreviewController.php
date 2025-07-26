<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Eleve;
use App\Models\Note;
use App\Services\BulletinService;
use Illuminate\Http\Request;


class BulletinPreviewController extends Controller
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
        $classes = Classroom::all();

        return view('admin.bulletins.index', [
            'eleves' => $eleves,
            'classes' => $classes,
            'selectedClasse' => $request->classe,
            'semestre' => $request->semestre ?? 'Semestre 1'
        ]);
    }

    

    public function show($eleveId, $semestre, BulletinService $service)
    {

        $bulletin = $service->genererBulletinComplet($eleveId, $semestre);
        $eleve = $bulletin['eleve'];
        $semestre = $bulletin['semestre'];
        $moyenne_generale = $bulletin['statistiques']['moyenne_generale'];
        if (!$bulletin) {
            return redirect()
                ->route('admin.bulletins.index')
                ->with('error', 'Aucune note disponible pour ce semestre');
        }
        // dd($bulletin);
        return view('admin.bulletins.show', [
            'bulletin' => $bulletin,
            'eleve' =>  $eleve,
            'semestre' =>  $semestre,
            'moyenne_generale' =>  $moyenne_generale,
        ]);
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

            return back()->with('success', 'Note enregistrée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'sometimes|in:Contrôle,Devoir,Examen'
        ]);

        $note = Note::findOrFail($id);
        $note->update($validated);

        return back()->with('success', 'Note mise à jour');
    }
}