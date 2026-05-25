@extends('layouts.app')

@section('title', '住所変更')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <h2 class="l-form__title">住所の変更</h2>

        <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post" class="l-form__form">
            @csrf
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
            </div>

            <div class="l-form__actions">
                <button type="submit" class="l-form__btn">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection 