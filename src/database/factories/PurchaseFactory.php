<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'buyer_id' => User::factory(),
            'payment_method' => 'カード支払い',
            'post_code' => fake()->postcode(),
            'address' => fake()->address(),
            'building' => fake()->optional()->secondaryAddress(),
        ];
    }
}
