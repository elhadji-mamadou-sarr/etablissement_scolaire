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

});

// Routes Enseignant
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::get('/dashboard', [EnseignantController::class, 'dashboard'])->name('dashboard');
   
});

// Routes Élève/Parent
Route::middleware(['auth', 'role:eleve_parent'])->prefix('eleve-parent')->name('eleve-parent.')->group(function () {
    Route::get('/dashboard', [EleveParentController::class, 'dashboard'])->name('dashboard');
});



require __DIR__.'/auth.php';
