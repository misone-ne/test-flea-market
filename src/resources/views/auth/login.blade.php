@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <h2 class="l-form__title">ログイン</h2>

        <form action="{{ route('login') }}" method="post" class="l-form__form" novalidate>
        @csrf

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

            <div class="l-form__actions">
                <button type="submit" class="l-form__btn">ログインする</button>
                <a href="{{ route('register') }}" class="l-form__link">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection
