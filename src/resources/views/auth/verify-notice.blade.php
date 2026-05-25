@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <div class="l-form__message">
            <p class="l-form__text">登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p class="l-form__text">メール認証を完了してください。</p>
        </div>

        {{-- 
            開発環境ではMailHogを使用してメール認証を行う。
            「認証はこちら」ボタンからMailHogへ遷移し、
            メール内の認証リンクをクリックして認証を完了する。
        --}}
        <div class="l-form__actions">
            <a href="http://localhost:8025" target="_blank" class="l-form__button-mail">認証はこちらから</a>
        </div>

        <div class="l-form__actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="l-form__link">認証メールを再送する</button>
            </form>
        </div>
    </div>
</div>
@endsection
