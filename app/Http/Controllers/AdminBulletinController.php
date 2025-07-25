<?php

namespace App\Http\Controllers;
use App\Models\Eleve;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Cour;
class AdminBulletinController extends Controller

{
public function index()
{
    $eleves = Eleve::with('user')->get();
    return view('admin.bulletins.index', compact('eleves'));
}



public function preview($eleveId)
{
    $semestre = 'S1'; // ou à récupérer dynamiquement
    $eleve = Eleve::with('user')->findOrFail($eleveId);

    $notes = Note::with('cour')
        ->where('eleve_id', $eleveId)
        ->where('semestre', $semestre)
        ->get();

    if ($notes->isEmpty()) {
        return view('admin.bulletins.preview', compact('eleve', 'semestre'))->with('bulletin', null);
    }

    $details = [];
    $totalPonderee = 0;
    $totalCoefficient = 0;

    foreach ($notes as $note) {
        $coefficient = $note->cour->credit ?? 1;

        $notePonderee = $note->note * $coefficient;

        $details[] = [
            'matiere' => $note->cour->libelle,
            'valeur' => $note->note,
            'coefficient' => $coefficient,
            'ponderee' => $notePonderee
        ];

        $totalPonderee += $notePonderee;
        $totalCoefficient += $coefficient;
    }

    $moyenne = $totalCoefficient > 0 ? round($totalPonderee / $totalCoefficient, 2) : 0;
    $mention = $this->getMention($moyenne);

    $bulletin = [
        'details' => $details,
        'moyenne_generale' => $moyenne,
        'mention' => $mention,
    ];

    return view('admin.bulletins.preview', compact('eleve', 'semestre', 'bulletin'));
}

}