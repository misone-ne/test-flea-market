<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねアイコンを押下することによって、いいねした商品として登録することができる()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('like', [
                'item_id' => $item->id
            ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(
            1,
            $user->favoriteItems()->count()
        );
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)
            ->get(route('item.show', [
                'item_id' => $item->id
            ]));

        $response->assertStatus(200);

        $response->assertSee('ハートロゴ_ピンク.png');
    }

    public function test_再度いいねアイコンを押下することによって、いいねを解除することができる()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)
            ->post(route('like', [
                'item_id' => $item->id
            ]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
