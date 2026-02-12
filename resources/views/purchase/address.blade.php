@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase-address.css') }}">
@endpush

@section('title', '住所の変更')

@section('content')
    <div class="address">
        <h1 class="address__title">住所の変更</h1>

        <form class="address__form" action="{{ route('purchase.address.update', $item) }}" method="post">
        @csrf

        <div class="address__field">
            <label class="address__label" for="postcode">郵便番号</label>
            <input class="address__input" id="postcode" type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}"
            placeholder="例）123-4567">
            @error('postcode')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address__field">
            <label class="address__label" for="address">住所</label>
            <input class="address__input" id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address__field">
            <label class="address__label" for="building">建物名</label>
            <input class="address__input" id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
            @error('building')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <button class="address__submit" type="submit">更新する</button>
        </form>
    </div>
@endsection
