<?php

namespace Tests\Feature\Item;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('comment.store', [
                'item_id' => $item->id
            ]), [
                'comment' => 'テストコメント'
            ]);

        $response->assertRedirect(
            route('item.show', [
                'item_id' => $item->id
            ])
        );

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $this->assertEquals(1, Comment::count());
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post(
            route('comment.store', [
                'item_id' => $item->id
            ]),
            [
                'comment' => 'テストコメント'
            ]
        );

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('item.show', [
                'item_id' => $item->id
            ]))
            ->post(route('comment.store', [
                'item_id' => $item->id
            ]), [
                'comment' => ''
            ]);

        $response->assertSessionHasErrors(['comment']);

        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $comment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->from(route('item.show', [
                'item_id' => $item->id
            ]))
            ->post(route('comment.store', [
                'item_id' => $item->id
            ]), [
                'comment' => $comment
            ]);

        $response->assertSessionHasErrors(['comment']);

        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);
    }
}
