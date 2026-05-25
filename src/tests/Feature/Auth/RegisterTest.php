<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');

        $this->assertEquals('お名前を入力してください', session('errors')->first('name'));
    }

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertEquals('メールアドレスを入力してください', session('errors')->first('email'));
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password',
            'password_confirmation'
        ]);

        $this->assertEquals('パスワードを入力してください', session('errors')->first('password'));
    }

    public function test_パスワードが7文字以下の場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors('password');

        $this->assertEquals('パスワードは8文字以上で入力してください', session('errors')->first('password'));
    }

    public function test_パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password999',
        ]);

        $response->assertSessionHasErrors('password_confirmation');

        $this->assertEquals('パスワードと一致しません', session('errors')->first('password_confirmation'));
    }

    /**
     * 要件シート「全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される」
     * 
     * メール認証機能追加に伴い、会員情報の登録後はメール認証誘導画面へ遷移する仕様に変更しています。
     * test名を仕様変更後の挙動に合わせて変更しました。 
     */
    public function test_全ての項目が入力されている場合、会員情報が登録され、メール認証画面に遷移される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('verification.notice.custom'));
    }
}
