@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endpush

@section('title', '商品の出品')

@section('content')
<div class="sell">
    <h1 class="sell__title">商品の出品</h1>

    <form class="sell__form" method="post" action="{{ route('items.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <section class="sellSection sellSection--image">
            <h2 class="sellSection__label">商品画像</h2>

            <label class="sellUpload">
                <input type="file" name="image" class="sellUpload__input" accept=".jpeg,.png,image/*">

                <div class="sellUpload__frame">
                    <span class="sellUpload__button">画像を選択する</span>
                    <img id="imagePreview" class="sellUpload__preview" src="" alt="" style="display:none;">
                </div>
            </label>

            @error('image')
                <p class="sell__error">{{ $message }}</p>
            @enderror
        </section>

        <section class="sellSection">
            <h2 class="sellSection__title">商品の詳細</h2>

            <div class="sellField">
                <p class="sellField__label">カテゴリー</p>
                <div class="sellCategories">
                    @foreach($categories as $category)
                        <label class="sellCategory">
                            <input
                                type="checkbox"
                                name="category_ids[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                            >
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_ids')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sellField">
                <p class="sellField__label">商品の状態</p>
                <select name="condition" class="sellSelect">
                    <option value="">選択してください</option>
                    <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('condition')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="sellSection">
            <h2 class="sellSection__title">商品名と説明</h2>

            <div class="sellField">
                <label class="sellField__label" for="name">商品名</label>
                <input id="name" type="text" name="name" class="sellInput" value="{{ old('name') }}">
                @error('name')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sellField">
                <label class="sellField__label" for="brand">ブランド名</label>
                <input id="brand" type="text" name="brand" class="sellInput" value="{{ old('brand') }}">
                @error('brand')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sellField">
                <label class="sellField__label" for="description">商品の説明</label>
                <textarea id="description" name="description" class="sellTextarea" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sellField">
                <label class="sellField__label" for="price">販売価格</label>
                <div class="sellPrice">
                    <span class="sellPrice__prefix">¥</span>
                    <input id="price" type="number" name="price" class="sellInput sellPrice__input" min="0" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="sell__error">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <div class="sell__submitRow">
            <button type="submit" class="sell__submit">出品する</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput   = document.querySelector('.sellUpload__input');
    const previewImg  = document.getElementById('imagePreview');
    const uploadLabel = document.querySelector('.sellUpload');

    if (!fileInput || !previewImg || !uploadLabel) {
        return;
    }

    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];

        // ファイル未選択（キャンセル）の場合
        if (!file) {
            previewImg.src = '';
            previewImg.style.display = 'none';
            uploadLabel.classList.remove('sellUpload--hasImage');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImg.src = event.target.result;
            previewImg.style.display = 'block';
            uploadLabel.classList.add('sellUpload--hasImage');
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
