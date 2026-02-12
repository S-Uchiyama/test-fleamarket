@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endpush

@section('title', $item->name . '｜商品詳細')

@section('content')
<div class="item">
    <div class="item__main">
        <div class="item__imageArea">
            <div class="item__imageFrame">
                @if($item->purchase)
                    <span class="item__sold">Sold</span>
                @endif
                <img src="{{ $item->img_path }}" alt="{{ $item->name }}" class="item__image">
            </div>
        </div>

        <div class="item__info">
            <h1 class="item__name">{{ $item->name }}</h1>

            @if($item->brand)
                <p class="item__brand">{{ $item->brand }}</p>
            @endif

            <p class="item__price">
                ¥{{ number_format($item->price) }} <span class="item__priceTax">（税込）</span>
            </p>

            <div class="item__statusRow">
                @auth
                    <form class="item__likeForm" method="post" action="{{ route('items.like', $item) }}">
                        @csrf
                        <button type="submit" class="item__likeBtn {{ $isLiked ? 'is-liked' : '' }}" aria-label="いいね">
                            <img src="{{ $isLiked ? asset('images/icon-like-on.png') : asset('images/icon-like-off.png') }}" 
                            alt="いいね" class="item__iconImg">
                        </button>
                        <span class="item__statusCount">{{ $item->likes->count() }}</span>
                    </form>
                @else
                    <div class="item__status">
                        <img src="{{ asset('images/icon-like-off.png') }}" alt="いいね" class="item__iconImg">
                        <span class="item__statusCount">{{ $item->likes->count() }}</span>
                    </div>
                @endauth

                <div class="item__status">
                    <img src="{{ asset('images/icon-comment.png') }}" alt="コメント" class="item__iconImg">
                    <span class="item__statusCount">{{ $item->comments->count() }}</span>
                </div>
            </div>

            <div class="item__actions">
                @if($item->purchase)
                    <span class="item__soldBtn">売り切れました</span>
                @elseif(auth()->check() && auth()->id() === $item->user_id)
                    <span class="item__soldBtn">あなたの出品商品です</span>
                @else
                    <a href="{{ route('purchase.show', $item) }}" class="item__purchaseBtn">購入手続きへ</a>
                @endif
            </div>

            <section class="item__section">
                <h2 class="item__sectionTitle">商品説明</h2>
                <p class="item__description">
                    {!! nl2br(e($item->description)) !!}
                </p>
            </section>

            <section class="item__section">
                <h2 class="item__sectionTitle">商品の情報</h2>
                <dl class="item__spec">
                    <dt>カテゴリ</dt>
                    <dd>
                        @forelse($item->categories as $category)
                            <span class="item__categoryTag">{{ $category->name }}</span>
                        @empty
                            <span>-</span>
                        @endforelse
                    </dd>
                    <dt>商品の状態</dt>
                    <dd>{{ $item->condition }}</dd>
                </dl>
            </section>

            <section class="item__section item__section--comments">
                <h2 class="item__commentTitle">
                    コメント
                    <span class="item__commentTitleCount">（{{ $item->comments->count() }}）</span>
                </h2>

                @forelse($item->comments as $comment)
                    <div class="item__comment">
                        <div class="item__commentHeader">
                            <div class="item__commentIcon">
                                @if($comment->user->profile_image)
                                    <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="" class="item__commentIconImg">
                                @endif
                            </div>
                            <div class="item__commentUser">{{ $comment->user->name }}</div>
                        </div>
                        <div class="item__commentBubble">
                            {{ $comment->body }}
                        </div>
                    </div>
                @empty
                    <p class="item__noComment">まだコメントはありません。</p>
                @endforelse
            </section>

            <section class="item__section item__section--commentForm">
                <h2 class="item__commentFormTitle">商品へのコメント</h2>

                @auth
                    <form class="item__commentForm" method="post" action="{{ route('items.comments.store', $item) }}">
                    @csrf

                    <textarea name="body" class="item__commentTextarea" rows="5" placeholder="こちらにコメントを入力してください。">{{ old('body') }}</textarea>

                    @error('body')
                        <p class="item__error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="item__commentSubmit">コメントを送信する</button>
                    </form>
                @else
                    <p class="item__loginNotice">※コメントを送信するにはログインしてください。</p>
                @endauth
            </section>
        </div>
    </div>
</div>
@endsection
