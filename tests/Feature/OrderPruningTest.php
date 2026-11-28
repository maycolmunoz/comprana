<?php

use App\Models\Order;

it('prunes only unpaid orders older than a month', function () {
    $this->travelTo('2026-06-15 12:00:00');

    $prunable = Order::factory()->create([
        'payment_id' => null,
        'created_at' => '2026-04-01 00:00:00',
    ]);
    $paid = Order::factory()->create([
        'payment_id' => 123,
        'created_at' => '2026-04-01 00:00:00',
    ]);
    $recent = Order::factory()->create([
        'payment_id' => null,
        'created_at' => '2026-06-01 00:00:00',
    ]);

    $this->artisan('model:prune', ['--model' => [Order::class]])->assertSuccessful();

    $this->assertModelMissing($prunable);
    $this->assertModelExists($paid);
    $this->assertModelExists($recent);
});
