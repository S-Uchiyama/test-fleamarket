<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Flea App')</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @stack('styles')

</head>
<body>

@php
    $showFullHeader =
        auth()->check()
        || request()->routeIs('items.index', 'items.show');
@endphp

<header class="header">
    <div class="container">
        <div class="header__inner">
            <a class="header__logo" href="{{ route('items.index') }}">
                <img src="{{ asset('images/header-logo.png') }}" alt="CT COACHTECH" class="header__logoImage">
            </a>

            @if($showFullHeader || auth()->check())
                <div class="header__search">
                    <form class="header__searchForm" method="get" action="{{ route('items.index') }}">
                        <input
                            class="header__searchInput"
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="なにをお探しですか？"
                            aria-label="商品名で検索"
                        >
                        @if(request('tab') === 'mylist')
                            <input type="hidden" name="tab" value="mylist">
                        @endif
                    </form>
                </div>

                <nav class="header__nav">
                    @auth
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="header__logoutBtn" type="submit">ログアウト</button>
                        </form>
                        <a class="header__navLink" href="{{ url('/mypage') }}">マイページ</a>
                        <a class="header__sellBtn" href="{{ url('/sell') }}">出品</a>
                    @else
                        <a class="header__navLink" href="{{ route('login') }}">ログイン</a>
                        <a class="header__navLink" href="{{ url('/login') }}">マイページ</a>
                        <a class="header__sellBtn" href="{{ url('/login') }}">出品</a>
                    @endauth
                </nav>
            @endif
        </div>
    </div>
</header>

@yield('after_header')

<main class="main">
    <div class="container">
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
