<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Services\BulletinService;
use Illuminate\Http\Request;

class BulletinPreviewController extends Controller
{
   public function show($eleveId, $semestre, BulletinService $service)
{
    $eleve = Eleve::with('user', 'classroom')->findOrFail($eleveId);
    $bulletin = $service->calculerBulletin($eleveId, $semestre);

    return view('admin.bulletins.preview', compact('eleve', 'bulletin', 'semestre'));
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'note' => 'required|numeric|min:0|max:20',
    ]);

    $note = Note::findOrFail($id);
    $note->note = $validated['note'];
    $note->save();

    return back()->with('success', 'Note modifiée avec succès.');
}

}
