<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）()
    {
        $user = User::factory()->create([
            'name' => 'COACHTECH',
            'profile_image' => 'test-profile.jpg',
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1',
        ]);

        $response = $this->actingAs($user)
            ->get(route('mypage.edit'));

        $response->assertStatus(200);

        $response->assertSee('COACHTECH');

        $response->assertSee('123-4567');

        $response->assertSee('東京都テスト区1-1');

        $response->assertSee('test-profile.jpg');
    }
}
