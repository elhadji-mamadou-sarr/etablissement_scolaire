<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Services\BulletinService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EleveParentController extends Controller
{
    public function dashboard()
    {
        return view('eleve-parent.dashboard');
    }


    public function bulletins() {
        
        $eleve = Eleve::where('user_id', Auth::id())->firstOrFail();
        
        return view('eleve-parent.bulletins.index', [
            'eleve' => $eleve,
            'semestres' => ['Semestre 1', 'Semestre 2']
        ]);
        
    }


    public function show($semestre, BulletinService $service)
    {

        $eleve = Eleve::with(['user', 'classroom'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

        $bulletin = $service->genererBulletinComplet($eleve->id, $semestre);

        if (!$bulletin) {
            return redirect()
                ->route('eleve-parent.bulletins')
                ->with('error', 'Aucune note disponible pour ce semestre');
        }
        
        $eleve = $bulletin['eleve'];
        $semestre = $bulletin['semestre'];
        $moyenne_generale = $bulletin['statistiques']['moyenne_generale'];

        return view('eleve-parent.bulletins.show', [
            'bulletin' => $bulletin,
            'eleve' =>  $eleve,
            'semestre' =>  $semestre,
            'moyenne_generale' =>  $moyenne_generale,
        ]);
    }

    public function shbow($semestre, BulletinService $bulletinService)
    {
        // Récupérer l'élève connecté
        $eleve = Eleve::with(['user', 'classroom'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Calculer le bulletin
        $bulletin = $bulletinService->genererBulletinComplet($eleve->id, $semestre);

        if (!$bulletin) {
            return back()->with('warning', 'Aucun bulletin disponible pour ce semestre');
        }

        return view('eleve-parent.bulletins.show', [
            'eleve' => $eleve,
            'bulletin' => $bulletin,
            'semestre' => $semestre
        ]);
    }


    public function downloadPdf($semestre, BulletinService $service)
    {
        $eleve = Eleve::with(['user', 'classroom'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

        $bulletin = $service->genererBulletinComplet($eleve->id, $semestre);

        if (!$bulletin) {
            return redirect()
                ->route('eleve-parent.bulletins')
                ->with('error', 'Aucune note disponible pour ce semestre');
        }

        $eleve = $bulletin['eleve'];
        
        // Génération du nom de fichier
        $fileName = 'bulletin_' . 
                   strtolower(str_replace(' ', '_', $eleve->user->nom)) . '_' .
                   strtolower(str_replace(' ', '_', $eleve->user->prenom)) . '_' .
                   strtolower(str_replace(' ', '_', $semestre)) . '_' .
                   now()->format('Y_m_d') . '.pdf';

        // Configuration du PDF
        $pdf = Pdf::loadView('pdf.bulletin', [
            'bulletin' => $bulletin,
            'eleve' => $eleve,
            'semestre' => $semestre
        ]);

        // Options du PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ]);

        // Téléchargement du PDF
        return $pdf->download($fileName);
    }
    

    // Alternative : afficher le PDF dans le navigateur
    // public function viewPdf($semestre, BulletinService $service)
    // {
    //     $eleve = Eleve::with(['user', 'classroom'])
    //             ->where('user_id', Auth::id())
    //             ->firstOrFail();

    //     $bulletin = $service->genererBulletinComplet($eleve->id, $semestre);

    //     if (!$bulletin) {
    //         return redirect()
    //             ->route('eleve-parent.bulletins')
    //             ->with('error', 'Aucune note disponible pour ce semestre');
    //     }

    //     $eleve = $bulletin['eleve'];

    //     $pdf = Pdf::loadView('eleve-parent.bulletins.pdf', [
    //         'bulletin' => $bulletin,
    //         'eleve' => $eleve,
    //         'semestre' => $semestre
    //     ]);

    //     $pdf->setPaper('A4', 'portrait');
        
    //     return $pdf->stream('bulletin.pdf');
    // }




}
