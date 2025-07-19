<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMINISTRATEUR = 'administrateur';
    case ENSEIGNANT = 'enseignant';
    case ELEVE_PARENT = 'eleve_parent';

    public function label(): string
    {
        return match($this) {
            self::ADMINISTRATEUR => 'Administrateur',
            self::ENSEIGNANT => 'Enseignant',
            self::ELEVE_PARENT => 'Élève/Parent',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::ADMINISTRATEUR => [
                'manage_users',
                'manage_classes',
                'manage_subjects',
                'manage_grades',
                'view_all_reports'
            ],
            self::ENSEIGNANT => [
                'manage_own_grades',
                'view_own_classes',
                'view_own_subjects'
            ],
            self::ELEVE_PARENT => [
                'view_own_grades',
                'view_own_reports'
            ],
        };
    }
}