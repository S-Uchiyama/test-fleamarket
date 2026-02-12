@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('title', 'マイページ')

@section('content')
<div class="mypage">

    <div class="mypage__top">
        <div class="mypage__user">
            <div class="mypage__avatar">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="mypage__avatarImg">
                @else
                    <div class="mypage__avatarDefault"></div>
                @endif
            </div>
            <div class="mypage__name">{{ $user->name }}</div>
        </div>

        <a href="{{ route('mypage.profile.edit') }}" class="mypage__editBtn">
            プロフィールを編集
        </a>
    </div>

    <div class="mypageTabs">
        <div class="mypageTabs__inner">
            <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypageTabs__tab {{ $tab === 'sell' ? 'is-active' : '' }}">出品した商品</a>

            <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypageTabs__tab {{ $tab === 'buy' ? 'is-active' : '' }}">購入した商品</a>
        </div>
    </div>

    <div class="mypageGrid">
        @forelse($items as $item)
            <a href="{{ route('items.show', $item) }}" class="mypageCard">
                <div class="mypageCard__thumb">
                    @if($item->purchase)
                        <span class="card__sold">Sold</span>
                    @endif
                    <img src="{{ $item->img_path }}" alt="{{ $item->name }}" class="mypageCard__img">
                </div>
                <div class="mypageCard__name">
                    {{ $item->name }}
                </div>
            </a>
        @empty
            <p>商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
