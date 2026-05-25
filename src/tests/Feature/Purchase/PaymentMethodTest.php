<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_小計画面で変更が反映される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post(
                route('purchase.preview', [
                    'item_id' => $item->id
                ]),
                [
                    'payment_method' => 'カード支払い',
                ]
            );

        $response->assertRedirect(
            route('purchase.show', [
                'item_id' => $item->id,
                'payment_method' => 'カード支払い',
            ])
        );

        $response = $this->actingAs($user)
            ->get(
                route('purchase.show', [
                    'item_id' => $item->id,
                    'payment_method' => 'カード支払い',
                ])
            );

        $response->assertStatus(200);

        $response->assertSee('カード支払い');
    }
}
