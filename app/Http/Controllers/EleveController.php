<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EleveRequest;

class EleveController extends Controller
{
    public function index()
    {
        $eleves = Eleve::with(['user', 'classroom'])->get();
        $classes = Classroom::all();
        return view('admin.eleves.index', compact('eleves', 'classes'));
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
            'classroom_id' => 'required|exists:classrooms,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $path = null;
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs', 'public');
        }

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
            'role' => \App\Enums\UserRole::ELEVE_PARENT,
            'justificatif_path' => $path,
        ]);

        Eleve::create([
            'user_id' => $user->id,
            'classroom_id' => $request->classroom_id,
            'matricule' => strtoupper(Str::random(8)),
        ]);

        return redirect()->back()->with('success', 'Élève ajouté avec succès.');
    }

  public function destroy(Eleve $eleve)
{
    try {
        $eleve->load('user');
        
        if ($eleve->user) {
            // Supprimez d'abord le justificatif s'il existe
            if ($eleve->user->justificatif_path) {
                Storage::disk('public')->delete($eleve->user->justificatif_path);
            }
            $eleve->user->delete();
        }
        
        $eleve->delete();
        
        return redirect()->back()->with('success', 'Élève supprimé avec succès.');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
}
   public function update(EleveRequest $request, Eleve $eleve)
{
    // Chargez explicitement la relation user si elle n'est pas déjà chargée
    $eleve->load('user');
    
    // Vérifiez que l'utilisateur existe
    if (!$eleve->user) {
        return redirect()->back()->with('error', 'Utilisateur associé non trouvé');
    }

    $path = $eleve->user->justificatif_path ?? null;
    
    if ($request->hasFile('justificatif')) {
        // Supprimer l'ancien justificatif s'il existe
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        $path = $request->file('justificatif')->store('justificatifs', 'public');
    }

    // Mise à jour de l'utilisateur
    $eleve->user->update([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'email' => $request->email,
        'telephone' => $request->telephone,
        'addresse' => $request->addresse,
        'date_naissane' => $request->date_naissance,
        'lieu' => $request->lieu,
        'sexe' => $request->sexe,
        'justificatif_path' => $path,
    ]);

    // Mise à jour de l'élève
    $eleve->update([
        'classroom_id' => $request->classroom_id,
    ]);

    return redirect()->back()->with('success', 'Élève mis à jour avec succès.');
}
}