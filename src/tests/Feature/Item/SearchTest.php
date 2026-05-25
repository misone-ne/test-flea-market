<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_「商品名」で部分一致検索ができる()
    {
        $targetItem = Item::factory()->create([
            'name' => 'テスト商品A',
        ]);

        $otherItem = Item::factory()->create([
            'name' => '全然違う商品',
        ]);

        $response = $this->get(route('index', [
            'keyword' => 'テスト',
        ]));

        $response->assertStatus(200);

        $response->assertSee($targetItem->name);
        $response->assertDontSee($otherItem->name);
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'テスト商品B',
        ]);

        $user->favoriteItems()->attach($likedItem->id);

        $response = $this->actingAs($user)
            ->get(route('index', [
                'tab' => 'mylist',
                'keyword' => 'テスト',
            ]));

        $response->assertStatus(200);

        $response->assertSee($likedItem->name);

        $response->assertSee('name="keyword"', false);
        $response->assertSee('value="テスト"', false);
    }
}
