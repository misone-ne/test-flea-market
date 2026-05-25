<?php

namespace Tests\Feature\Item;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）()
    {
        $commentUser = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => '商品説明テスト',
            'condition' => 1,
        ]);

        $categories = Category::factory()->count(2)->create();

        $item->categories()->attach($categories->pluck('id'));

        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee(number_format($item->price));
        $response->assertSee('商品説明テスト');

        $response->assertSee('良好');

        $response->assertSee('テストコメント');
        $response->assertSee($commentUser->name);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        $item = Item::factory()->create();

        $categories = Category::factory()->count(3)->create();

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
