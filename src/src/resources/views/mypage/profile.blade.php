@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('title', 'プロフィール設定')

@section('content')
<div class="profile">
    <h1 class="profile__title">プロフィール設定</h1>

    <form class="profile__form" action="{{ route('mypage.profile.update') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="profile__avatarRow">
            <div class="profile__avatar">
                <img
                    src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '' }}"
                    alt="プロフィール画像"
                    class="profile__avatarImage"
                    style="{{ $user->profile_image ? '' : 'display:none;' }}"
                >
                <div class="profile__avatarPlaceholder" style="{{ $user->profile_image ? 'display:none;' : '' }}"></div>
            </div>

            <label class="profile__fileBtn">
                画像を選択する
                <input class="profile__fileInput" type="file" name="profile_image" accept=".jpeg,.jpg,.png">
            </label>

            @error('profile_image')
                <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__field">
            <label class="profile__label" for="name">ユーザー名</label>
            <input class="profile__input" id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__field">
            <label class="profile__label" for="postcode">郵便番号</label>
            <input class="profile__input" id="postcode" type="text" name="postcode" placeholder="123-4567" value="{{ old('postcode', $user->postcode) }}">
            @error('postcode')
                <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__field">
            <label class="profile__label" for="address">住所</label>
            <input class="profile__input" id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__field">
            <label class="profile__label" for="building">建物名</label>
            <input class="profile__input" id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
            @error('building')
                <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <button class="profile__submit" type="submit">更新する</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('.profile__fileInput');
    const preview = document.querySelector('.profile__avatarImage');
    const placeholder = document.querySelector('.profile__avatarPlaceholder');

    if (!input || !preview) return;

    input.addEventListener('change', function (e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
            preview.style.display = '';
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
