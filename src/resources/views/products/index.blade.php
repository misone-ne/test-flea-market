@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <div class="product-list">
        <div class="product-list__tabs">
            {{-- おすすめ：recommendタブのときactive --}}
            <a href="{{ route('index', ['keyword' => $keyword]) }}" class="product-list__tab product-list__tab-recs {{ $tab === 'recommend' ? 'is-active' : '' }}">おすすめ</a>
            {{-- マイリスト：mylistタブのときactive --}}
            <a href="{{ route('index', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="product-list__tab product-list__tab-mylist {{ $tab === 'mylist' ? 'is-active' : '' }}">マイリスト</a>
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
@endsection