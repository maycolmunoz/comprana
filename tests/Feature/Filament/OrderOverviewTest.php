<?php

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Widgets\OrderOverview;
use App\Models\Order;
use Livewire\Livewire;

it('counts orders using the enum values', function () {
    Order::factory()->create(['status' => OrderStatus::Processing]);

    Livewire::test(OrderOverview::class)
        ->assertSeeHtmlInOrder(['Pedidos pendientes', '1'])
        ->assertSeeHtmlInOrder(['Pedidos enviados', '0']);
});
