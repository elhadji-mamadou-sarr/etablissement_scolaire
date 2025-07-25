<?php

namespace App\Services;

use App\Models\Note;

class BulletinService
{
    public function calculerBulletin($eleveId, $semestre)
    {
        $notes = Note::with('cour')
            ->where('eleve_id', $eleveId)
            ->where('semestre', $semestre)
            ->get();

        if ($notes->isEmpty()) {
            return null;
        }

        $totalPondere = 0;
        $totalCoef = 0;
        $details = [];

        foreach ($notes as $note) {
            $valeur = $note->note;
            $coef = $note->cour->credit ?? 1;
            $ponderee = $valeur * $coef;

            $details[] = [
                'matiere' => $note->cour->libelle,
                'note' => $valeur,
                'coefficient' => $coef,
                'ponderee' => round($ponderee, 2),
                'note_id' => $note->id,
            ];

            $totalPondere += $ponderee;
            $totalCoef += $coef;
        }

        $moyenne = $totalCoef > 0 ? $totalPondere / $totalCoef : 0;

        $mention = match (true) {
            $moyenne >= 16 => 'Très Bien',
            $moyenne >= 14 => 'Bien',
            $moyenne >= 12 => 'Assez Bien',
            $moyenne >= 10 => 'Passable',
            default => 'Insuffisant',
        };

        return [
            'details' => $details,
            'moyenne_generale' => round($moyenne, 2),
            'mention' => $mention,
        ];
    }
}
