<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * 会員登録画面表示
     *
     * Fortifyの標準ルートではなく
     * FormRequestによるバリデーション設計を採用している為、
     * 明示的にViewを返す構成としている
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * 会員登録処理
     *
     * - FortifyのCreatesNewUsersを利用してユーザー新規作成
     * - 登録直後にログイン状態へ切替、メール認証画面へ遷移
     */
    public function register(RegisterRequest $request, CreatesNewUsers $userCreator)
    {
        $user = $userCreator->create($request->validated());

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect()->route('verification.notice.custom');
    }

    // ログイン画面表示
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     *
     * - 認証成功時、セッション再生成（セッション固定攻撃対策）
     * - メール未認証の場合はメール認証誘導画面へ
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice.custom');
            }

            return redirect()->route('index');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->onlyInput('email');
    }

    /**
     * ログアウト処理
     *
     * - セッション無効化（なりすまし防止）
     * - CSRFトークン再生成（セキュリティ維持）
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
