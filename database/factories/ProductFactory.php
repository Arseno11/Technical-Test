<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->number(10000), // Contoh: angka desimal antara 10 dan 100
            'stock' => fake()->number(100), // Contoh: angka antara 0 dan 100
            'status' => rand(0, 1),
        ];
    }
}
