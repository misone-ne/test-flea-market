<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    // メール認証誘導画面
    public function notice()
    {
        return view('auth.verify-notice');
    }

    // メール認証完了
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('mypage.edit');
    }

    // 認証メール再送
    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back();
    }
}
