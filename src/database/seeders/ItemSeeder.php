<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザーがいなければ、テストユーザーを一人作成してそのIDを使う
        $user = User::first() ?? User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $items = [
            ['name' => '腕時計', 'price' => 15000, 'brand' => 'Rolax', 'description' => 'スタイリッシュなデザインのメンズ腕時計', 'image_path' => 'item_images/Armani+Mens+Clock.jpg', 'condition' => 1, 'category_id' => 1],
            ['name' => 'HDD', 'price' => 5000, 'brand' => '西芝', 'description' => '高速で信頼性の高いハードディスク', 'image_path' => 'item_images/HDD+Hard+Disk.jpg', 'condition' => 2, 'category_id' => 2],
            ['name' => '玉ねぎ3束', 'price' => 300, 'brand' => 'なし', 'description' => '新鮮な玉ねぎ3束のセット', 'image_path' => 'item_images/iLoveIMG+d.jpg', 'condition' => 3, 'category_id' => 10],
            ['name' => '革靴', 'price' => 4000, 'brand' => null, 'description' => 'クラシックなデザインの革靴', 'image_path' => 'item_images/Leather+Shoes+Product+Photo.jpg', 'condition' => 4, 'category_id' => 1],
            ['name' => 'ノートPC', 'price' => 45000, 'brand' => null, 'description' => '高性能なノートパソコン', 'image_path' => 'item_images/Living+Room+Laptop.jpg', 'condition' => 1, 'category_id' => 2],
            ['name' => 'マイク', 'price' => 8000, 'brand' => 'なし  ', 'description' => '高音質のレコーディング用マイク', 'image_path' => 'item_images/Music+Mic+4632231.jpg', 'condition' => 2, 'category_id' => 2],
            ['name' => 'ショルダーバッグ', 'price' => 3500, 'brand' => null, 'description' => 'おしゃれなショルダーバッグ', 'image_path' => 'item_images/Purse+fashion+pocket.jpg', 'condition' => 3, 'category_id' => 1],
            ['name' => 'タンブラー', 'price' => 500, 'brand' => 'なし', 'description' => '使いやすいタンブラー', 'image_path' => 'item_images/Tumbler+souvenir.jpg', 'condition' => 4, 'category_id' => 10],
            ['name' => 'コーヒーミル', 'price' => 4000, 'brand' => 'Starbacks', 'description' => '手動のコーヒーミル', 'image_path' => 'item_images/Waitress+with+Coffee+Grinder.jpg', 'condition' => 1, 'category_id' => 10],
            ['name' => 'メイクセット', 'price' => 2500, 'brand' => null, 'description' => '便利なメイクアップセット', 'image_path' => 'item_images/外出メイクアップセット.jpg', 'condition' => 2, 'category_id' => 6],
        ];

        foreach ($items as $itemData) {
            $categoryId = $itemData['category_id'];
            unset($itemData['category_id']);

            $item = Item::create(array_merge($itemData, [
                'user_id' => $user->id,
            ]));

            $item->categories()->attach($categoryId);
        }
    }
}
