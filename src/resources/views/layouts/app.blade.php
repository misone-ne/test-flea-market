@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ - @yield('title')</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    @vite(['resources/scss/app.scss'])
</head>

<body class="flea-market">
    <div class="app">
        <header class="header">
            <h1 class="header__heading">
                <a href="{{ route('index') }}" class="header__logo-link">
                    <img src="{{ asset('images/COACHTECHヘッダーロゴ.png')}}" alt="coachtechフリマ" class="header__logo-img">
                </a>
            </h1>

            {{-- ロゴ以外（認証関連ページ以外で表示） --}}
            @if (!request()->routeIs('login', 'register', 'verification.notice.custom'))

            <div class="header__inner">
                {{-- 検索窓 --}}
                <form action="{{ route('index') }}" method="GET" class="header__search-form">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" class="header__search-input">
                </form>

                {{-- ナビ --}}
                <nav class="header__nav">
                    <ul class="header__nav-list">
                        @auth
                        <li class="header__nav-item">
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="header__nav-link">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="header__nav-item">
                            <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
                        </li>
                        @endauth

                        <li class="header__nav-item">
                            <a href="{{ route('mypage')}}" class="header__nav-link">マイページ</a>
                        </li>
                        <li class="header__nav-item header__nav-item--sell">
                            <a href="{{ route('item.sell') }}" class="header__nav-sell">出品</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </header>

        <main class="main">
            @yield('content')
        </main>
    </div>
</body>
</html>