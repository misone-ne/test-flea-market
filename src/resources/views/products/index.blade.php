@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <div class="product-list">
        <div class="product-list__tabs">
            {{-- おすすめ：タブパラメータが空のときactive --}}
            <a href="/" class="product-list__tab product-list__tab-recs {{ !request()->query('tab') ? 'is-active' : '' }}">おすすめ</a>
            {{-- マイリスト：タブパラメータがmylistのときactive --}}
            <a href="/?tab=mylist" class="product-list__tab product-list__tab-mylist {{ request()->query('tab') === 'mylist' ? 'is-active' : '' }}">マイリスト</a>
        </div>

        {{-- 商品一覧 --}}
        <div class="produt-list__grid">
            @forelse ($items as $item)
            <div class="product-card">
                <a href="{{ route('item.show' , ['item_id' => $item->id]) }}" class="product-card__link">
                    <div class="product-card__image-wrapper">
                        <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="product-card__image">
                        @if ($item->is_sold)
                            <span class="product-card__sold">Sold</span>
                        @endif
                    </div>
                    <p class="product-card__name">{{ $item->name }}</p>
                </a>
            </div>
            @empty
                <p class="product-list__none">表示する商品がありません。</p>
            @endforelse
        </div>
@endsection