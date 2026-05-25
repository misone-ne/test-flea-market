<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'brand' => fake()->company(),
            'price' => fake()->numberBetween(1000, 50000),
            'description' => fake()->sentence(),
            'image_path' => 'item_images/test.jpg',
            'condition' => fake()->numberBetween(1, 4),
            'is_sold' => false,
        ];
    }
}
