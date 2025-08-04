<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BulletinApiController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CourController;
use App\Http\Controllers\Api\EleveApiController;
use App\Http\Controllers\Api\EnseignantApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\NoteController;


Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = $request->user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
});


// Route de logout protégée
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Déconnecté avec succès']);
});

// Toutes les routes protégées par Sanctum ici
Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('classes', ClassroomController::class);
    Route::get('classes/{classroom}/cours', [ClassroomController::class, 'listCours']);
    Route::post('classes/{classroom}/cours/attach', [ClassroomController::class, 'attachCour']);
    Route::post('classes/{classroom}/cours/detach', [ClassroomController::class, 'detachCour']);
    
    
    Route::apiResource('users', UserApiController::class);
    Route::apiResource('cours', CourController::class);
    Route::apiResource('eleves', EleveApiController::class);
    
    Route::apiResource('enseignants', EnseignantApiController::class);
    Route::apiResource('notes', NoteController::class);
    Route::get('notes/enseignant/{enseignant}', [NoteController::class, 'findCoursByEnseignant']);

    Route::get('bulletins', [BulletinApiController::class, 'index']);
    Route::get('bulletins/{eleve}/{semestre}', [BulletinApiController::class, 'show']);
    Route::post('bulletins/note', [BulletinApiController::class, 'storeNote']);
    Route::put('bulletins/note/{id}', [BulletinApiController::class, 'updateNote']);
    
});
