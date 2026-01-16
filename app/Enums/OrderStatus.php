<?php

namespace App\Enums;

enum OrderStatus: string
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
}
