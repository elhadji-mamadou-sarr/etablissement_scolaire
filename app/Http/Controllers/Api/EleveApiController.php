<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EleveApiController extends Controller
{
    public function index()
    {
        return Eleve::with(['user', 'classroom'])->get();
    }

    public function show(Eleve $eleve)
    {
        return $eleve->load(['user', 'classroom']);
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
            'classroom_id' => 'required|exists:classrooms,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $path = null;
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs', 'public');
        }

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'addresse' => $validated['addresse'],
            'date_naissance' => $validated['date_naissance'],
            'lieu' => $validated['lieu'],
            'sexe' => $validated['sexe'],
            'password' => bcrypt('0000'),
            'role' => \App\Enums\UserRole::ELEVE_PARENT,
            'justificatif_path' => $path,
        ]);

        $eleve = Eleve::create([
            'user_id' => $user->id,
            'classroom_id' => $validated['classroom_id'],
            'matricule' => strtoupper(Str::random(8)),
        ]);

        return response()->json($eleve->load(['user', 'classroom']), 201);
    }

    public function update(Request $request, Eleve $eleve)
    {
        $eleve->load('user');

        if (!$eleve->user) {
            return response()->json(['message' => 'Utilisateur associé non trouvé.'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($eleve->user->id),
            ],
            'telephone' => 'required',
            'addresse' => 'required',
            'date_naissance' => 'required|date',
            'lieu' => 'required',
            'sexe' => ['required', Rule::in(['M', 'F'])],
            'classroom_id' => 'required|exists:classrooms,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $path = $eleve->user->justificatif_path;

        if ($request->hasFile('justificatif')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('justificatif')->store('justificatifs', 'public');
        }

        $eleve->user->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'addresse' => $validated['addresse'],
            'date_naissance' => $validated['date_naissance'],
            'lieu' => $validated['lieu'],
            'sexe' => $validated['sexe'],
            'justificatif_path' => $path,
        ]);

        $eleve->update([
            'classroom_id' => $validated['classroom_id'],
        ]);

        return response()->json($eleve->load(['user', 'classroom']));
    }

    public function destroy(Eleve $eleve)
    {
        $eleve->load('user');

        if ($eleve->user) {
            if ($eleve->user->justificatif_path) {
                Storage::disk('public')->delete($eleve->user->justificatif_path);
            }
            $eleve->user->delete();
        }

        $eleve->delete();

        return response()->json(['message' => 'Élève supprimé avec succès.']);
    }
}
