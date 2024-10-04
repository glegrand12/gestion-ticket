<?php

namespace App\Enum;

enum TicketStatusType: string
{
    case OUVERT = 'Ouvert';
    case EN_COURS = 'En cours';
    case RESOLU = 'Résolu';
    case FERME = 'Fermé';

    public function label(): string
    {
        return match($this) {
            self::OUVERT => 'Ouvert',
            self::EN_COURS => 'En cours',
            self::RESOLU => 'Résolu',
            self::FERME => 'Fermé',
        };
    }
}
