<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos pendientes', Order::where('status', OrderStatus::Processing)->count())
                ->description('Procesando')
                ->descriptionIcon(Heroicon::ArrowPath)
                ->color('primary'),

            Stat::make('Pedidos enviados', Order::where('status', OrderStatus::InTransit)->count())
                ->description('En camino')
                ->descriptionIcon(Heroicon::Truck)
                ->color('warning'),

            Stat::make('Pedidos completados', Order::where('status', OrderStatus::Delivered)->count())
                ->description('Entregados')
                ->descriptionIcon(Heroicon::Cube)
                ->color('success'),

            Stat::make('Pedidos incompletos', Order::where('status', OrderStatus::NotDelivered)->count())
                ->description('No entregados')
                ->descriptionIcon(Heroicon::ArrowPath)
                ->color('danger'),
        ];
    }
}
