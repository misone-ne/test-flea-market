<?php

namespace Tests\Feature\Profile;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）()
    {
        $user = User::factory()->create([
            'name' => 'COACHRECH',
            'profile_image' => 'test-profile.jpg',
        ]);

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);

        $buyItem = Item::factory()->create([
            'name' => '購入商品',
        ]);

        Purchase::factory()->create([
            'item_id' => $buyItem->id,
            'buyer_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('mypage'));

        $response->assertStatus(200);

        $response->assertSee('COACHTECH');

        $response->assertSee('/storage/test-profile.jpg');

        $response->assertSee('出品商品');

        $response = $this->actingAs($user)
            ->get(route('mypage', [
                'page' => 'buy'
            ]));

        $response->assertSee('購入商品');
    }
}
