@extends('layouts.app')

@section('title', '商品出品')

@section('content')
<div class="l-form">
    <div class="l-form__container">
        <h2 class="l-form__title">商品の出品</h2>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="l-form__form">
            @csrf

            {{-- 商品画像 --}}
            <div class="l-form__group">
                <label class="l-form__label">商品画像</label>
                <div class="l-form__image-upload">
                    <label class="l-form__image-btn">
                        画像を選択する
                        <input type="file" name="image" class="l-form__image-input">
                    </label>
                </div>
                @error('image')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="l-form__sub-title">商品の詳細</h3>

            {{-- カテゴリー --}}
            <div class="l-form__group">
                <label class="l-form__label">カテゴリー</label>
                <div class="l-form__category-list">
                    @foreach($categories as $category)
                    <label class="l-form__category-label">
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="item-sell__category-input" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                        <span class="item-sell__category-text">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_ids')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品の状態 --}}
            <div class="l-form__group">
                <label for="condition" class="l-form__label">商品の状態</label>
                <div class="l-form__select-wrapper">
                    <select name="condition" id="condition" class="l-form__input">
                        <option value="" disabled hidden {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                        @foreach(\App\Models\Item::CONDITION_LABELS as $key => $value)
                        <option value="{{ $key }}" {{ old('condition') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @error('condition')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="l-form__sub-title">商品名と説明</h3>

            <div class="l-form__group">
                <label for="name" class="l-form__label">商品名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="l-form__input">
                @error('name')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="brand" class="l-form__label">ブランド名</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="l-form__input">
            </div>

            <div class="l-form__group">
                <label for="description" class="l-form__label">商品の説明</label>
                <textarea name="description" id="description" class="l-form__input l-form__textarea">{{ old('description') }}</textarea>
                @error('description')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="l-form__group">
                <label for="price" class="l-form__label">販売価格</label>
                <div class="l-form__price-input">
                    <span class="l-form__price-symbol">¥</span>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" class="l-form__input">
                </div>
                @error('price')
                <p class="l-form__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="l-form__btn">出品する</button>
        </form>
    </div>
</div>
@endsection
