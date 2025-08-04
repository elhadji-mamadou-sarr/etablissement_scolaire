<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Classroom;
use App\Models\Cour;
use App\Models\Note;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques simples
        $eleves = Eleve::count();
        $enseignants = Enseignant::count();
        $classes = Classroom::count();
        $cours = Cour::count();

        // 📈 Courbe : Nombre de bulletins (élèves notés) par mois
        $bulletinsParMois = Note::selectRaw('EXTRACT(MONTH FROM created_at) as mois, COUNT(DISTINCT eleve_id) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        // 🧠 Camembert : Répartition des élèves par classe (avec requête compatible avec ta structure)
        $repartitionClasses = Classroom::select('classrooms.libelle')
            ->selectSub(function ($query) {
                $query->from('eleves')
                    ->selectRaw('count(*)')
                    ->whereColumn('eleves.classroom_id', 'classrooms.id');
            }, 'eleves_count')
            ->get()
            ->pluck('eleves_count', 'libelle');

        // 📊 Histogramme : Moyenne des notes par matière
        $notesParCours = Note::join('cours', 'notes.cour_id', '=', 'cours.id')
            ->select('cours.libelle', DB::raw('AVG(notes.note) as moyenne'))
            ->groupBy('cours.libelle')
            ->pluck('moyenne', 'libelle');

        return view('admin.dashboard', compact(
            'eleves',
            'enseignants',
            'classes',
            'cours',
            'bulletinsParMois',
            'repartitionClasses',
            'notesParCours'
        ));
    }
}
