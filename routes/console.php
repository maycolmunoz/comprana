<?php

use App\Models\Order;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune', ['--model' => [Order::class]])->monthly();
Schedule::command('auth:clear-resets')->daily();
Schedule::command('sanctum:prune-expired', ['--hours' => 24])->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
