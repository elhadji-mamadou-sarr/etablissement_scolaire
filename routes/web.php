<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourController;
use App\Http\Controllers\EleveParentController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EleveController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Enseignant\NoteController;
use App\Http\Controllers\BulletinPreviewController;
use App\Http\Controllers\AdminBulletinController;
use App\Http\Controllers\BulletinController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    return match($user->role->value) {
        'administrateur' => redirect()->route('admin.dashboard'),
        'enseignant' => redirect()->route('enseignant.dashboard'),
        'eleve_parent' => redirect()->route('eleve-parent.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
});

// Routes Administrateur
Route::middleware(['auth', 'role:administrateur'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('cours', CourController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('users', UserController::class);
    Route::resource('eleves', EleveController::class);
    Route::resource('enseignants', EnseignantController::class);
    Route::get('bulletins', [AdminBulletinController::class, 'index'])->name('bulletins.index');
    Route::get('/bulletins/{eleve}/{semestre}', [BulletinPreviewController::class, 'show'])
        ->name('bulletins.preview');

    Route::get('/bulletins/download/{eleve}/{semestre}', [BulletinPreviewController::class, 'downloadPdf'])
        ->name('bulletins.download');



});



// Routes Enseignant
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::get('/dashboard', [EnseignantController::class, 'dashboard'])->name('dashboard');
    
   Route::resource('notes', NoteController::class);
});

// Routes Élève/Parent
Route::middleware(['auth', 'role:eleve_parent'])->prefix('eleve-parent')->name('eleve-parent.')->group(function () {
    Route::get('/dashboard', [EleveParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/bulletins', [EleveParentController::class, 'bulletins']) ->name('bulletins');
    Route::get('/bulletins/{semestre}', [EleveParentController::class, 'show']) ->name('show');

    Route::get('/bulletins/download/{semestre}', [EleveParentController::class, 'downloadPdf']) ->name('bulletins.download');
});






require __DIR__.'/auth.php';
