<?php

namespace Tests\Feature\Purchase;

use Mockery;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_「購入する」ボタンを押下すると購入が完了する()
    {
        $buyer = User::factory()->create();

        $item = Item::factory()->create(['is_sold' => false,]);

        /*
         * Stripe処理は要件対象外のため、
         * 購入データ作成と商品状態変更のみ確認する
         */

        $response = $this->actingAs($buyer)
            ->post(route('purchase.store', [
                'item_id' => $item->id
            ]), [
                'payment_method' => 'カード支払い',
                'post_code' => '123-4567',
                'address' => '東京都テスト区',
                'building' => 'テストマンション'
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
        ]);
    }

    public function test_購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
        ]);

        $response = $this->actingAs($buyer)
            ->get(route('index'));

        $response->assertSee('Sold');
    }

    public function test_「プロフィール購入した商品一覧」に追加されている()
    {
        $buyer = User::factory()->create();

        $item = Item::factory()->create();

        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
        ]);

        $response = $this->actingAs($buyer)
            ->get(route('mypage', [
                'page' => 'buy'
            ]));

        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
}
