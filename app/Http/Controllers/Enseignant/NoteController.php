<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Cour;
use App\Models\Classroom;
use App\Models\Enseignant;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isEnseignant()) {
            abort(403, 'Accès réservé aux enseignants');
        }

        $enseignant = $user->enseignant;

        // Récupérer les classes de l'enseignant
        $classroomIds = $enseignant->classrooms()->pluck('classrooms.id');
        /// Debug: Vérifiez les IDs des classes associées
        // $classroomIds = $enseignant->classrooms()->pluck('id');
        // dd([
        //     'enseignant_id' => $enseignant->id,
        //     'classrooms' => $classroomIds,
        //     'pivot_data' => DB::table('classroom_enseignant')->where('enseignant_id', $enseignant->id)->get()
        // ]);
        // Récupérer les élèves de ces classes
        $classroomIds = $enseignant->cours()->pluck('classroom_id');
        $eleves = Eleve::whereIn('classroom_id', $classroomIds)
             ->with(['user', 'classroom'])
             ->get();

        // Récupérer les cours que l'enseignant donne
        $cours = $enseignant->cours;
        $classes = $enseignant->classrooms;

        // Récupérer les notes avec filtres
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
                         ->paginate(20);
  
        return view('enseignant.notes.index', compact('notes', 'cours', 'classes', 'eleves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'cour_id' => 'required|exists:cours,id',
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'required|in:devoir,composition,examen',
            'coefficient' => 'required|numeric|min:0.1|max:3',
            'semestre' => 'required|in:Semestre 1,Semestre 2',
            'date_evaluation' => 'required|date|before_or_equal:today',
            'commentaire' => 'nullable|string|max:255'
        ]);
        
        try {
            $eleve = Eleve::findOrFail($validated['eleve_id']);
            $enseignant = auth()->user()->enseignant;


            // Vérifier que l'enseignant peut noter ce cours dans cette classe
            $canTeach = $enseignant->cours()
                                 ->where('cours.id', $validated['cour_id'])
                                 ->wherePivot('classroom_id', $eleve->classroom_id)
                                 ->exists();

            // if (!$canTeach) {
            //     throw new \Exception("Vous n'êtes pas autorisé à noter ce cours pour cette classe");
            // }

            $note = Note::create([
                ...$validated,
                'classroom_id' => $eleve->classroom_id,
                'enseignant_id' => $enseignant->id
            ]);
            return back()->with('success', 'Note enregistrée avec succès');
            
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Note $note)
    {
        // $this->authorize('update', $note);

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'coefficient' => 'required|numeric|min:0.1|max:3',
            'commentaire' => 'nullable|string|max:255'
        ]);

        try {
            $note->update($validated);
            return back()->with('success', 'Note mise à jour avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour');
        }
    }

    public function destroy(Note $note)
    {
        // $this->authorize('delete', $note);
        
        try {
            $note->delete();
            return back()->with('success', 'Note supprimée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
}

// class NoteController extends Controller
// {
//     public function __construct()
//     {
//         // $this->middleware('can:manage-notes');
//     }

//     public function index()
//     {
//         $user = auth()->user();
        
//         if ($user->isAdministrateur()) {
//             $notes = Note::with(['eleve.user', 'cour', 'classroom', 'enseignant.user'])
//                        ->latest()
//                        ->paginate(20);
            
//             return view('admin.notes.index', compact('notes'));
//         }

//         if ($user->isEnseignant() && $enseignant = $user->enseignant) {

//             $notes = $enseignant->notes()
//                               ->with(['eleve.user', 'cour', 'classroom'])
//                               ->latest()
//                               ->paginate(20);
            
//             $cours = $enseignant->cours;
//             $classes = $enseignant->classrooms;

//             $eleves = Eleve::whereIn('classroom_id', $classes->pluck('id'))
//                            ->with('user')
//                            ->get();
                           
            
//             $classroomIds = $enseignant->classrooms()->pluck('classrooms.id');
           
//             // Si l'enseignant n'a aucune classe attribuée
//             // if ($classroomIds->isEmpty()) {
//             //     return back()->with('warning', 'Vous n\'êtes affecté à aucune classe');
//             // }
//             // Récupérer les élèves de ces classes
//             $eleves = Eleve::with('user')
//                     ->whereIn('classroom_id', $classroomIds)
//                     ->get();
          
//             return view('enseignant.notes.index', compact('notes', 'cours', 'classes', 'eleves'));
//         }

//         abort(403, 'Accès non autorisé');
//     }


//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'eleve_id' => 'required|exists:eleves,id',
//             'cour_id' => 'required|exists:cours,id',
//             'note' => 'required|numeric|min:0|max:20',
//             'type_note' => 'required|in:devoir,composition,examen',
//             'coefficient' => 'required|numeric|min:0.1|max:3',
//             'semestre' => 'required|in:Semestre 1,Semestre 2',
//             'date_evaluation' => 'required|date|before_or_equal:today',
//             'commentaire' => 'nullable|string|max:255'
//         ]);

//         try {
//             $eleve = Eleve::findOrFail($validated['eleve_id']);
//             $enseignant = auth()->user()->enseignant;

//             if (!$enseignant) {
//                 throw new \Exception("Aucun enseignant associé à ce compte");
//             }

//             // Vérifie que l'enseignant peut noter ce cours
//             $canTeach = $enseignant->cours()
//                                  ->where('cours.id', $validated['cour_id'])
//                                  ->wherePivot('classroom_id', $eleve->classroom_id)
//                                  ->exists();

//             if (!$canTeach) {
//                 throw new \Exception("Vous n'êtes pas autorisé à noter ce cours pour cette classe");
//             }

//             Note::create([
//                 ...$validated,
//                 'classroom_id' => $eleve->classroom_id,
//                 'enseignant_id' => $enseignant->id
//             ]);

//             return back()->with('success', 'Note enregistrée avec succès');

//         } catch (\Exception $e) {
//             return back()->with('error', $e->getMessage());
//         }
//     }

//     public function update(Request $request, Note $note)
//     {
//         // $this->authorize('update', $note);

//         $validated = $request->validate([
//             'note' => 'required|numeric|min:0|max:20',
//             'coefficient' => 'required|numeric|min:0.1|max:3',
//             'commentaire' => 'nullable|string|max:255'
//         ]);

//         try {
//             $note->update($validated);
//             return back()->with('success', 'Note mise à jour avec succès');
//         } catch (\Exception $e) {
//             return back()->with('error', 'Erreur lors de la mise à jour');
//         }
//     }

//     public function destroy(Note $note)
//     {
//         // $this->authorize('delete', $note);
        
//         try {
//             $note->delete();
//             return back()->with('success', 'Note supprimée avec succès');
//         } catch (\Exception $e) {
//             return back()->with('error', 'Erreur lors de la suppression');
//         }
//     }
// }