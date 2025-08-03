<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Cour;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with('user')->get();
        $classrooms = Classroom::all();
        $cours = Cour::all();

        return view('admin.enseignants.index', compact('enseignants', 'classrooms', 'cours'));
    }

    public function dashboard()
    {
        return view('enseignant.dashboard');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required',
            'addresse' => 'required',
            'date_naissance' => 'required|date',
            'lieu' => 'required',
            'sexe' => ['required', Rule::in(['M', 'F'])],
            'cours' => 'required|array',
            'classrooms' => 'required|array'
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'addresse' => $request->addresse,
            'date_naissane' => $request->date_naissance,
            'lieu' => $request->lieu,
            'sexe' => $request->sexe,
            'password' => bcrypt('0000'),
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
        'coursClassrooms' => $coursClassrooms
    ]);
}


}
