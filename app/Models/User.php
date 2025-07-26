<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'addresse',
        'date_naissane',
        'lieu',
        'sexe',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    // Méthodes pour vérifier les rôles
    public function isAdministrateur(): bool
    {
        return $this->role === UserRole::ADMINISTRATEUR;
    }

    public function isEnseignant(): bool
    {
        return $this->role === UserRole::ENSEIGNANT;
    }

    public function isEleveParent(): bool
    {
        return $this->role === UserRole::ELEVE_PARENT;
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->role->permissions());
    }

    public function getRoleLabel(): string
    {
        return $this->role->label();
    }

    public function eleve()
    {
        return $this->hasOne(Eleve::class);
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    
}
