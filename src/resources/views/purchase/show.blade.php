@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('title', '商品購入')

@section('content')
<div class="purchase">
    <div class="purchase__main">
        <div class="purchase__left">
            <div class="purchase__itemRow">
                <div class="purchase__thumb">
                    <img src="{{ $item->img_path }}" alt="{{ $item->name }}" class="purchase__thumbImg">
                </div>
                <div class="purchase__itemInfo">
                    <h1 class="purchase__itemName">{{ $item->name }}</h1>
                    <p class="purchase__itemPrice">¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            <hr class="purchase__divider">

            @php
                $hasAddress = !empty($user->postcode) && !empty($user->address);
            @endphp
            <form id="purchase-form-dummy" class="purchase__form" method="post" action="{{ route('purchase.store', $item) }}">
                @csrf

                <input type="hidden" name="postcode" value="{{ $user->postcode }}">
                <input type="hidden" name="address" value="{{ $user->address }}">
                <input type="hidden" name="building" value="{{ $user->building }}">
                <input type="hidden" name="shipping_address" value="profile">

                <section class="purchase__section">
                    <h2 class="purchase__sectionTitle">支払い方法</h2>
                    <div class="purchase__field">
                        <select name="payment_method" class="purchase__select" id="payment-method-select">
                            <option value="">選択してください</option>
                            <option value="convenience_store"
                                {{ old('payment_method') === 'convenience_store' ? 'selected' : '' }}>
                                コンビニ払い
                            </option>
                            <option value="card"
                                {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                カード払い
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="purchase__error">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <hr class="purchase__divider">

                <section class="purchase__section purchase__section--address">
                    <div class="purchase__sectionHeader">
                        <h2 class="purchase__sectionTitle">配送先</h2>
                        <a href="{{ route('purchase.address.edit', $item) }}" class="purchase__changeLink">変更する</a>
                    </div>

                    <div class="purchase__addressBody">
                        <p class="purchase__addressLine">〒 {{ $user->postcode ?? '未登録' }}</p>
                        <p class="purchase__addressLine">{{ $user->address ?? '住所が未登録です' }}</p>
                        @if(!empty($user->building))
                            <p class="purchase__addressLine">{{ $user->building }}</p>
                        @endif
                    </div>
                    @unless($hasAddress)
                        <p class="purchase__error">住所が未登録のため購入できません。住所を登録してください。</p>
                    @endunless
                    @error('postcode')
                        <p class="purchase__error">{{ $message }}</p>
                    @enderror
                    @error('address')
                        <p class="purchase__error">{{ $message }}</p>
                    @enderror
                </section>

                <hr class="purchase__divider purchase__divider--bottom">

                <div class="purchase__buttonRow purchase__buttonRow--sp">
                    <button type="submit" class="purchase__submit" {{ $hasAddress ? '' : 'disabled' }} aria-disabled="{{ $hasAddress ? 'false' : 'true' }}">
                        購入する
                    </button>
                </div>
            </form>
        </div>

        <aside class="purchase__summary">
            <div class="purchase__summaryRow purchase__summaryRow--top">
                <div class="purchase__summaryLabel">商品代金</div>
                <div class="purchase__summaryValue">¥ {{ number_format($item->price) }}</div>
            </div>
            <div class="purchase__summaryRow">
                <div class="purchase__summaryLabel">支払い方法</div>
                <div class="purchase__summaryValue" id="payment-method-summary">
                    @php
                        $method = old('payment_method');
                    @endphp
                    @if ($method === 'convenience_store')
                        コンビニ払い
                    @elseif ($method === 'card')
                        カード払い
                    @else
                        選択してください
                    @endif
                </div>
            </div>

            <div class="purchase__buttonRow purchase__buttonRow--pc">
                <button type="submit" form="purchase-form-dummy" class="purchase__submit" {{ $hasAddress ? '' : 'disabled' }} aria-disabled="{{ $hasAddress ? 'false' : 'true' }}">
                    購入する
                </button>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('payment-method-select');
        const summary = document.getElementById('payment-method-summary');

        if (!select || !summary) return;

        const labels = {
            convenience_store: 'コンビニ払い',
            card: 'カード払い',
        };

        const update = () => {
            summary.textContent = labels[select.value] || '選択してください';
        };

        update();
        select.addEventListener('change', update);
    });
</script>
@endpush
