<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('index'));

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品は「Sold」と表示される()
    {
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get(route('index'));

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品者専用テスト商品ABC',
        ]);

        $otherItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品XYZ',
        ]);

        $response = $this->actingAs($user)
            ->get(route('index'));

        $response->assertStatus(200);

        $response->assertDontSeeText($myItem->name);
        $response->assertSeeText($otherItem->name);
    }
}
