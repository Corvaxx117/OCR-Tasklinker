<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Statut standardisé des tâches (board Kanban 3 colonnes).
 */
enum TaskStatus: string
{
    case TODO = 'TODO';
    case DOING = 'DOING';
    case DONE = 'DONE';

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'To Do',
            self::DOING => 'Doing',
            self::DONE => 'Done',
        };
    }

    /** Retourne les statuts dans l'ordre d'affichage souhaité. */
    public static function ordered(): array
    {
        return [self::TODO, self::DOING, self::DONE];
    }
}
