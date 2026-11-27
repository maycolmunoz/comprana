<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasLabel
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
            self::Processing => 'info',
            self::InTransit => 'warning',
            self::Delivered => 'success',
            self::NotDelivered => 'danger',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::Processing => 'badge-info bg-info/10 text-info border-info/20',
            self::InTransit => 'badge-warning bg-warning/10 text-warning border-warning/20',
            self::Delivered => 'badge-success bg-success/10 text-success border-success/20',
            self::NotDelivered => 'badge-error bg-error/10 text-error border-error/20',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Processing => 'o-arrow-path',
            self::InTransit => 'o-truck',
            self::Delivered => 'o-check-circle',
            self::NotDelivered => 'o-x-circle',
        };
    }
}
