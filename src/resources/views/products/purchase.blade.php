@extends('layouts.app')

@section('title', '商品購入')

@section('content')
<div class="purchase">

    {{-- 支払い方法更新専用フォーム --}}
    <form id="preview-form" action="{{ route('purchase.preview', ['item_id' => $item->id]) }}" method="post">
        @csrf
        <input type="hidden" name="payment_method" id="preview-payment-method">
    </form>

    {{-- 購入処理フォーム --}}
    <form id="purchase-form" action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="post" class="purchase__container" novalidate>
        @csrf

        {{-- 画面左側 --}}
        <div class="purchase__main">
            {{-- 商品情報 --}}
            <div class="purchase__item">
                <div class="purchase__item--image-wrapper">
                    @if($item->is_sold)
                    <div class="purchase__item--sold-label">
                    <span>Sold</span>
                    </div>
                    @endif
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="purchase__img">
                </div>
                <div class="purchase__item-detail">
                    <h2 class="purchase__item-name">{{ $item->name }}</h2>
                    <p class="purchase__item-price"><span class="purchase__item-currency">￥</span>{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="purchase__section">
                <h3 class="purchase__section-title">支払い方法</h3>
                <div class="purchase__select-wrapper">
                    <select name="payment_method" class="purchase__select" onchange="updatePayment(this.value)">
                        <option value="" disabled {{ ($payment_method ?? null) ? '' : 'selected' }}>選択してください</option>
                        <option value="コンビニ支払い" {{ ($payment_method ?? null) === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                        <option value="カード支払い" {{ ($payment_method ?? null) === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                </div>
                @error('payment_method')
                <p class="purchase__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 配送先 --}}
            <div class="purchase__section">
                <div class="purchase__section-header">
                    <h3 class="purchase__section-title">配送先</h3>
                    <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="purchase__link">変更する</a>
                </div>
                <div class="purchase__address">
                    <p class="purchase__address-postcode">〒 {{ $user->post_code}}</p>
                    <p class="purchase__address-text">{{ $user->address }} {{ $user->building }}</p>
                </div>
                @error('post_code')
                <p class="purchase__error">{{ $message }}</p>
                @enderror
                @error('address')
                <p class="purchase__error">{{ $message }}</p>
                @enderror
            </div>
            {{-- 支払い方法送信用 --}}
            <input type="hidden" name="payment_method" id="purchase-payment-method" value="{{ $payment_method ?? '' }}">
            {{-- 配送先情報送信用 --}}
            <input type="hidden" name="post_code" value="{{ $user->post_code }}">
            <input type="hidden" name="address" value="{{ $user->address }}">
            <input type="hidden" name="building" value="{{ $user->building }}">
        </div>

        {{-- 画面右側：内容確認 --}}
        <aside class="purchase__sidebar">
            <div class="purchase__summary-card">
                <table class="purchase__summary">
                    <tr class="purchase__row">
                        <th class="purchase__label">商品代金</th>
                        <td class="purchase__value"><span class="purchase__value-currency">￥</span>{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="purchase__row">
                        <th class="purchase__label">支払い方法</th>
                        <td class="purchase__value">{{ $payment_method ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            @if(session('error'))
            <p class="purchase__error">{{ session('error') }}</p>
            @endif
            @if($item->is_sold)
            <button type="button" class="purchase__sold-btn" disabled>売り切れました</button>
            @else
            <button type="submit" form="purchase-form" class="purchase__submit-btn">購入する</button>
            @endif
        </aside>
    </form>
</div>
<script>
function updatePayment(value) {
    document.getElementById('preview-payment-method').value = value;
    document.getElementById('purchase-payment-method').value = value;

    document.getElementById('preview-form').submit();
}
</script>
@endsection