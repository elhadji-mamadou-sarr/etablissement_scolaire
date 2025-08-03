<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class EnseignantApiController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with(['user', 'classrooms', 'cours'])->get();
        return response()->json($enseignants);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'addresse' => $validated['addresse'],
            'date_naissane' => $validated['date_naissance'],
            'lieu' => $validated['lieu'],
            'sexe' => $validated['sexe'],
            'password' => Hash::make("0000"),
            'role' => UserRole::ENSEIGNANT,
        ]);

        $enseignant = Enseignant::create([
            'user_id' => $user->id,
        ]);

        foreach ($validated['cours'] as $cour_id) {
            foreach ($validated['classrooms'] as $classroom_id) {
                DB::table('enseignant_cour_classroom')->insert([
                    'enseignant_id' => $enseignant->id,
                    'cour_id' => $cour_id,
                    'classroom_id' => $classroom_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json($enseignant->load(['user', 'classrooms', 'cours']), 201);
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->cours()->detach();
        $enseignant->classrooms()->detach();
        $enseignant->user->delete();
        $enseignant->delete();

        return response()->json(['message' => 'Enseignant supprimé avec succès']);
    }

    
}
