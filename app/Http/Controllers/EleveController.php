<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
        $eleve->user->delete();
        $eleve->delete();
        return redirect()->back()->with('success', 'Élève supprimé avec succès.');
    }
}
