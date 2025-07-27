<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\Note;

class BulletinService
{

    public function genererBulletinComplet($eleveId, $semestre)
    {
        $eleve = Eleve::with(['user', 'classroom'])->findOrFail($eleveId);
        
        $notes = Note::where('eleve_id', $eleveId)
            ->where('semestre', $semestre)
            ->with(['cour', 'enseignant.user'])
            ->get()
            ->groupBy('cour_id');

        if ($notes->isEmpty()) {
            return null;
        }

        $resultat = [
            'eleve' => $eleve,
            'semestre' => $semestre,
            'date_edition' => now()->format('d/m/Y'),
            'matieres' => [],
            'statistiques' => []
        ];

        $totalPondere = 0;
        $totalCredits = 0;

        foreach ($notes as $courId => $notesMatiere) {
            $cour = $notesMatiere->first()->cour;
            $moyenneMatiere = $this->calculerMoyenneMatiere($notesMatiere);
            $ponderee = $moyenneMatiere * $cour->credit;

            $resultat['matieres'][] = [
                'matiere' => $cour->libelle,
                'credit' => $cour->credit,
                'notes' => $notesMatiere->map(fn($n) => [
                    'type' => ucfirst($n->type_note),
                    'note' => $n->note,
                    'coefficient' => $n->coefficient,
                    'date' => $n->date_evaluation->format('d/m'),
                    'enseignant' => $n->enseignant->user->nom
                ]),
                'moyenne' => round($moyenneMatiere, 2),
                'ponderation' => round($ponderee, 2)
            ];

            $totalPondere += $ponderee;
            $totalCredits += $cour->credit;
        }

        $moyenneGenerale = $totalCredits > 0 ? $totalPondere / $totalCredits : 0;

        $resultat['statistiques'] = [
            'moyenne_generale' => round($moyenneGenerale, 2),
            'mention' => $this->determinerMention($moyenneGenerale),
            'classement' => $this->calculerClassement($eleve->classroom_id, $semestre, $moyenneGenerale),
            'total_credits' => $totalCredits
        ];

        return $resultat;
    }


    private function calculerMoyenneMatiere($notes)
    {
        $total = 0;
        $totalCoeff = 0;

        foreach ($notes as $note) {
            $total += $note->note * $note->coefficient;
            $totalCoeff += $note->coefficient;
        }

        return $totalCoeff > 0 ? $total / $totalCoeff : 0;
    }
    

    private function determinerMention(float $moyenne): string
    {
       
        $mention = match (true) {
            $moyenne >= 16 => 'Très Bien',
            $moyenne >= 14 => 'Bien',
            $moyenne >= 12 => 'Assez Bien',
            $moyenne >= 10 => 'Passable',
            default => 'Insuffisant',
        };

        return $mention;
    }


    private function calculerClassement($classroomId, $semestre, $moyenne): ?int
    {
        // Implémentation complexe avec requête SQL
        // Retourne la position dans le classement
        return 1;
    }




    public function genererPdfBulletin(array $bulletinData)
    {
        $pdf = PDF::loadView('pdf.bulletin', $bulletinData);
        return $pdf->download('bulletin-'.$bulletinData['eleve']->user->nom.'-'.$bulletinData['semestre'].'.pdf');
    }




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
