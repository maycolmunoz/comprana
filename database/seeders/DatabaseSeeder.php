<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(SectionSeeder::class);

        $sectionIds = Section::pluck('id')->all();

        Product::factory(40)
            ->make()
            ->each(function ($product) use ($sectionIds) {
                $product->section_id = $sectionIds[array_rand($sectionIds)];
                $product->save();
            });
    }
}
