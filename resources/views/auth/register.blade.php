@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', '会員登録')

@section('content')
    <div class="auth">
        <div class="auth__card">
            <h1 class="auth__title">会員登録</h1>

        <form method="POST" action="{{ route('register') }}" class="auth__form">
        @csrf

        <div class="auth__field">
            <label class="auth__label" for="name">ユーザー名</label>
            <input id="name" type="text" name="name" class="auth__input" value="{{ old('name') }}">
            @error('name')
                <p class="auth__errorMessage">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth__field">
            <label class="auth__label" for="email">メールアドレス</label>
            <input id="email" type="email" name="email" class="auth__input" value="{{ old('email') }}">
            @error('email')
                <p class="auth__errorMessage">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth__field">
            <label class="auth__label" for="password">パスワード</label>
            <input id="password" type="password" name="password" class="auth__input">
            @error('password')
                <p class="auth__errorMessage">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth__field">
            <label class="auth__label" for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="auth__input">
            @error('password_confirmation')
                <p class="auth__errorMessage">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth__submit">登録する</button>

        <div class="auth__footer">
            <a class="auth__link" href="{{ route('login') }}">ログインはこちら</a>
        </div>
        </form>
        </div>
    </div>
@endsection
