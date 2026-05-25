@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <h2 class="l-form__title">プロフィール設定</h2>

        <form action="{{ route('mypage.update') }}" method="post" enctype="multipart/form-data" class="l-form__form">
        @csrf
            
            <div class="l-form__image-section">
                <div class="l-form__image-preview">
                    @if($user->profile_image)
                    <img src="{{ Storage::url($user->profile_image) }}" alt="プロフィール画像" class="l-form__image">
                    @else
                    <div class="l-form__image-default"></div>
                    @endif
                </div>
                <label class="l-form__image-button">
                    画像を選択する
                    <input type="file" name="profile_image" class="l-form__image-input">
                </label>
                @error('profile_image')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="name" class="l-form__label">ユーザー名</label>
                <input type="text" name="name" id="name" class="l-form__input" value="{{ old('name', $user->name) }}">
                @error('name')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="post_code" class="l-form__label">郵便番号</label>
                <input type="text" name="post_code" id="post_code" value="{{ old('post_code', $user->post_code) }}" class="l-form__input">
                @error('post_code')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="address" class="l-form__label">住所</label>
                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="l-form__input">
                @error('address')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="building" class="l-form__label">建物名</label>
                <input type="text" name="building" id="building" value="{{ old('building', $user->building) }}" class="l-form__input">
                @error('building')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__actions">
                <button type="submit" class="l-form__btn">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection
