<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourResquest;
use App\Models\Cour;
use Illuminate\Http\Request;

class CourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cours = Cour::with('classrooms')->get();

        return view('cours.index', ['cours' => $cours]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourResquest $request)
    {
        Cour::create($request->validated());
        return redirect()->back()->with('success', 'Cours ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourResquest $request, Cour $cour)
    {
        $cour->update($request->validated());
        return redirect()->back()->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cour $cour)
    {
        $cour->delete();
        return redirect()->back()->with('success', 'Cours supprimé avec succès.');
    }
}
