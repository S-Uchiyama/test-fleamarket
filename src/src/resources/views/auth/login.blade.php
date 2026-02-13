@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'ログイン')

@section('content')
    <div class="auth">
        <div class="auth__card">
            <h1 class="auth__title">ログイン</h1>

            <form method="POST" action="{{ route('login') }}" class="auth__form">
                @csrf

                <div class="auth__field">
                    <label class="auth__label" for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" class="auth__input" value="{{ old('email') }}" autocomplete="email">
                    @error('email')
                        <p class="auth__errorMessage">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth__field">
                    <label class="auth__label" for="password">パスワード</label>
                    <input id="password" type="password" name="password" class="auth__input" autocomplete="current-password">
                    @error('password')
                        <p class="auth__errorMessage">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth__submit">ログインする</button>

                <div class="auth__footer">
                    <a class="auth__link" href="{{ route('register') }}">会員登録はこちら</a>
                </div>
            </form>
        </div>
    </div>
@endsection
