@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endpush

@section('title', '商品一覧')

@section('after_header')
    <div class="tabs">
        <div class="container">
            <div class="tabs__inner">
                <a class="tab {{ request('tab') !== 'mylist' ? 'tab--active' : '' }}" 
                   href="{{ route('items.index', ['keyword' => request('keyword')]) }}">おすすめ</a>
                <a class="tab {{ request('tab') === 'mylist' ? 'tab--active' : '' }}" 
                   href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}">マイリスト</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if($items->isEmpty())
        @if(request('tab') === 'mylist' && !auth()->check())
        @else
            <p>商品がありません。</p>
        @endif
    @else
    <div class="grid">
        @foreach($items as $item)
            <a class="card" href="{{ route('items.show', $item) }}">
                <div class="card__thumb">
                    @if($item->purchase)
                        <span class="card__sold">Sold</span>
                    @endif
                    <img class="card__img" src="{{ $item->img_path }}" alt="{{ $item->name }}">
                </div>
                <div class="card__meta">
                    <div class="card__name">{{ $item->name }}</div>
                </div>
            </a>
        @endforeach
    </div>
    @endif
@endsection
