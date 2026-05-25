@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <h2 class="l-form__title">会員登録</h2>

        <form action="{{ route('register') }}" method="post" class="l-form__form" novalidate>
        @csrf

            <div class="l-form__group">
                <label for="name" class="l-form__label">ユーザー名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="l-form__input">
                @error('name')
                    <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="email" class="l-form__label">メールアドレス</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="l-form__input">
                @error('email')
                    <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="password" class="l-form__label">パスワード</label>
                <input type="password" name="password" id="password" class="l-form__input">
                @error('password')
                    <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="password_confirmation" class="l-form__label">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="l-form__input">
                @error('password_confirmation')
                    <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__actions">
                <button type="submit" class="l-form__btn">登録する</button>
                <a href="{{ route('login') }}" class="l-form__link">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection