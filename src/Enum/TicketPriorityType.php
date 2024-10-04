<?php

namespace App\Enum;

enum TicketPriorityType: string
{
    case BASSE = 'Basse';
    case MOYENNE = 'Moyenne';
    case HAUTE = 'Haute';

    public function label(): string
    {
        return match($this) {
            self::BASSE => 'Basse',
            self::MOYENNE => 'Moyenne',
            self::HAUTE => 'Haute',
        };
    }
}
