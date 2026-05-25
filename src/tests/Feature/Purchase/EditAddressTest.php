<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->create([
            'post_code' => null,
            'address' => null,
            'building' => null,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('address.update', [
                'item_id' => $item->id
            ]), [
                'post_code' => '123-4567',
                'address' => '東京都テスト区1-1',
                'building' => 'テストマンション101'
            ]);

        $response = $this->actingAs($user)
            ->get(route('purchase.show', [
                'item_id' => $item->id
            ]));

        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都テスト区1-1');
        $response->assertSee('テストマンション101');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create([
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1',
            'building' => 'テストマンション101',
        ]);

        $item = Item::factory()->create();

        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'payment_method' => 'カード支払い',
            'post_code' => $user->post_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1',
            'building' => 'テストマンション101',
        ]);
    }
}
