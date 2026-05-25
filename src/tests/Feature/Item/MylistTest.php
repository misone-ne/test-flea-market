<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create();
        $otherItem = Item::factory()->create();

        $user->favoriteItems()->attach($likedItem->id);

        $response = $this->actingAs($user)
            ->get(route('index', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertSeeText($likedItem->name);
        $response->assertDontSeeText($otherItem->name);
    }

    public function test_購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)
            ->get(route('index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        $items = Item::factory()
            ->count(3)
            ->sequence(
                ['name' => '未認証商品AAA'],
                ['name' => '未認証商品BBB'],
                ['name' => '未認証商品CCC'],
            )
            ->create();

        $response = $this->get(route('index', ['tab' => 'mylist']));

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertDontSee($item->name);
        }
    }
}
