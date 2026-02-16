<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case Processing = 'procesando';
    case InTransit = 'en_camino';
    case Delivered = 'entregado';
    case NotDelivered = 'no_entregado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Processing => 'Procesando',
            self::InTransit => 'En Camino',
            self::Delivered => 'Entregado',
            self::NotDelivered => 'No Entregado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Processing => 'blue',
            self::InTransit => 'yellow',
            self::Delivered => 'green',
            self::NotDelivered => 'gray',
        };
    }
}
