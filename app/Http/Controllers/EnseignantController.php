<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Cour;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;
use App\Http\Requests\EnseignantRequest;
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

    public function dashboard()
    {
        return view('enseignant.dashboard');
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


}
