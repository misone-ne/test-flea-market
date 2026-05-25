<?php

namespace Tests\Feature\Item;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'name' => '家電'
        ]);

        /*
         * 実際のstorageへ保存しないためFake化
         * テスト用の仮想ストレージを利用する
         */
        Storage::fake('public');

        $image = UploadedFile::fake()->create(
            'test.jpg',
            100,
            'image/jpeg'
        );

        $response = $this->actingAs($user)
            ->post(route('item.store'), [
                'image' => $image,
                'category_ids' => [$category->id],
                'condition' => 1,
                'name' => 'テスト商品',
                'brand' => 'テストブランド',
                'description' => '商品説明テスト',
                'price' => 3000,
            ]);

        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => '商品説明テスト',
            'price' => 3000,
            'condition' => 1,
            'is_sold' => false,
        ]);

        $this->assertDatabaseHas('category_item', ['category_id' => $category->id,]);

        Storage::disk('public')->assertExists('item_images/' . $image->hashName());
    }
}
