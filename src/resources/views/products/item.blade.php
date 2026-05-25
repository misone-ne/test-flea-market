@extends('layouts.app')

@section('title', '商品詳細 - ' . $item->name)

@section('content')
<div class="product-detail">
    <div class="product-detail__container">
        
        <div class="product-detail__image-wrapper">
            @if($item->is_sold)
            <div class="product-detail__sold-label">
                <span>Sold</span>
            </div>
            @endif
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="product-detail__image">
        </div>

        <div class="product-detail__content">
            <div class="product-detail__header">
                <h2 class="product-detail__name">{{ $item->name }}</h2>
                <p class="product-detail__brand">{{ $item->brand }}</p>
                <p class="product-detail__price"><span class="product-detail__currency">￥</span>{{ number_format($item->price) }}<span class="product-detail__tax">（税込）</span></p>
                
                {{-- いいね・コメントアイコン --}}
                <div class="product-detail__stats">
                    <div class="product-detail__stats-item">
                        @auth
                        <form action="{{ route('like', ['item_id' => $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="product-detail__icon-btn">
                                @if($isLiked)
                                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="いいね済み" class="product-detail__icon">
                                @else
                                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="product-detail__icon">
                                @endif
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="product-detail__icon"></a>
                        @endauth
                        <span class="product-detail__count">{{ $item->favorite_items_count }}</span>
                    </div>
                    <div class="product-detail__stats-item">
                        <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント" class="product-detail__icon">
                        <span class="product-detail__count">{{ $item->comments_count }}</span>
                    </div>
                </div>
            </div>

            @if($item->is_sold)
            <button class="product-detail__sold-btn" disabled>売り切れました</button>
            @else
            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="product-detail__buy-btn">購入手続きへ</a>
            @endif

            <div class="product-detail__section">
                <h3 class="product-detail__section-title">商品説明</h3>
                <p class="product-detail__description">{{ $item->description }}</p>
            </div>

            <div class="product-detail__section">
                <h3 class="product-detail__section-title">商品の情報</h3>
                <div class="product-detail__info-row">
                    <span class="product-detail__info-label">カテゴリー</span>
                    <div class="product-detail__tags">
                        @foreach($item->categories as $category)
                        <span class="product-detail__tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="product-detail__info-row">
                    <span class="product-detail__info-label">商品の状態</span>
                    <span class="product-detail__info-value">{{ $item->condition_text }}</span>
                </div>
            </div>

            {{-- コメント --}}
            <div class="product-detail__comment-section">
                <h3 class="product-detail__section-title product-detail__comment-title">コメント({{ $item->comments_count }})</h3>
    
                <div class="product-detail__comment-list">
                    @foreach($item->comments as $comment)
                    <div class="product-detail__comment-item">
                        <div class="product-detail__comment-user">

                            <div class="product-detail__user-icon">
                                @if($comment->user->profile_image)
                                <img src="{{ Storage::url($comment->user->profile_image) }}" alt="プロフィール画像" class="product-detail__user-icon-img">
                                @endif
                            </div>

                            <span class="product-detail__user-name">{{ $comment->user->name }}</span>
                        </div>
                        <div class="product-detail__comment-body">
                            {{ $comment->comment }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <form action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="POST" class="product-detail__comment-form">
                    @csrf
                    <p class="product-detail__comment-label">商品へのコメント</p>
                    <textarea name="comment" class="product-detail__comment-textarea">{{ old('comment') }}</textarea>
                        @error('comment')
                        <p class="product-detail__error">{{ $message }}</p>
                        @enderror
                    <button type="submit" class="product-detail__comment-btn">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
