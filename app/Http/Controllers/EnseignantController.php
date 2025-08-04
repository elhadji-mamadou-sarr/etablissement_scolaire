<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Cour;
use App\Models\Enseignant;
use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\EnseignantRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with('user')->get();
        $classrooms = Classroom::all();
        $cours = Cour::all();

        return view('admin.enseignants.index', compact('enseignants', 'classrooms', 'cours'));
    }


   public function dashboard(){

    $enseignant = Enseignant::where('user_id', auth()->id())->first();

    $cours = \DB::table('enseignant_cour_classroom')
        ->join('cours', 'enseignant_cour_classroom.cour_id', '=', 'cours.id')
        ->where('enseignant_id', $enseignant->id)
        ->select('cours.libelle')
        ->distinct()
        ->pluck('libelle');

    $classrooms = \DB::table('enseignant_cour_classroom')
        ->join('classrooms', 'enseignant_cour_classroom.classroom_id', '=', 'classrooms.id')
        ->where('enseignant_id', $enseignant->id)
        ->select('classrooms.libelle')
        ->distinct()
        ->pluck('libelle');

    return view('enseignant.dashboard', compact('cours', 'classrooms'));
}
    

    public function store(EnseignantRequest $request)
    {
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'addresse' => $request->addresse,
            'date_naissane' => $request->date_naissance,
            'lieu' => $request->lieu,
            'sexe' => $request->sexe,
            'password' => Hash::make("0000"),
            'role' => UserRole::ENSEIGNANT,
        ]);

        $enseignant = Enseignant::create([
            'user_id' => $user->id,
        ]);

        foreach ($request->cours as $cour_id) {
            foreach ($request->classrooms as $classroom_id) {
                \DB::table('enseignant_cour_classroom')->insert([
                    'enseignant_id' => $enseignant->id,
                    'cour_id' => $cour_id,
                    'classroom_id' => $classroom_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Enseignant ajouté avec succès.');
    }
    

    public function update(EnseignantRequest $request, Enseignant $enseignant ) {

        $user = $enseignant->user;
    
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'addresse' => $request->addresse,
            'date_naissane' => $request->date_naissance,
            'lieu' => $request->lieu,
            'sexe' => $request->sexe,
        ]);
    
        // Supprimer les anciennes relations
        \DB::table('enseignant_cour_classroom')
            ->where('enseignant_id', $enseignant->id)
            ->delete();

        foreach ($request->cours as $cour_id) {
            foreach ($request->classrooms as $classroom_id) {
                \DB::table('enseignant_cour_classroom')->insert([
                    'enseignant_id' => $enseignant->id,
                    'cour_id' => $cour_id,
                    'classroom_id' => $classroom_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Enseignant modifié avec succès.');
    }


    public function destroy(Enseignant $enseignant)
    {
        $enseignant->cours()->detach();
        $enseignant->classrooms()->detach();
        $enseignant->user->delete();
        $enseignant->delete();

        return redirect()->back()->with('success', 'Enseignant supprimé avec succès.');
    }
   
    

    public function mesCours()
    {
        $user = Auth::user();

        // Vérifie que c'est bien un enseignant
        if (!$user->enseignant) {
            abort(403, "Vous n'êtes pas un enseignant.");
        }

        $enseignant = $user->enseignant;

        // Récupère les cours avec les classes
        $coursClassrooms = $enseignant->coursClassrooms(); // méthode déjà définie dans le modèle

        return view('enseignant.cours.index', [
            'classes' => $coursClassrooms
        ]);
    }


}
