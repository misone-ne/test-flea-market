@extends('layouts.app')

@section('title', 'プロフィール')

@section('content')
<div class="mypage product-list">

    <div class="mypage__user">
        <div class="mypage__user-info">
            <div class="mypage__image-wrapper">
                @if($user->profile_image)
                <img src="{{ Storage::url($user->profile_image) }}" alt="プロフィール画像" class="mypage__user-img">
                @else
                <div class="c-user-profile__img-default"></div>
                @endif
            </div>
            <h2 class="mypage__name">{{ $user->name }}</h2>
            <a href="{{ route('mypage.edit') }}" class="mypage__edit-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="product-list">
        <div class="product-list__tabs">
            {{-- タブ切替：出品した商品 --}}
            <a href="{{ route('mypage', ['page' => 'sell']) }}" class="product-list__tab {{ $page === 'sell' ? 'is-active' : '' }}">出品した商品</a>
            {{-- タブ切替：購入した商品 --}}
            <a href="{{ route('mypage', ['page' => 'buy']) }}" class="product-list__tab {{ $page === 'buy' ? 'is-active' : '' }}">購入した商品</a>
        </div>

        <div class="product-list__grid">
            @forelse ($items as $item)
            <div class="product-card">
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="product-card__link">
                    <div class="product-card__image-wrapper">
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="product-card__image">
                            @if ($item->is_sold)
                            <span class="product-card__sold-label">Sold</span>
                            @endif
                    </div>
                    <p class="product-card__name">{{ $item->name }}</p>
                </a>
            </div>
            @empty
            <div class="product-list__none">
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
