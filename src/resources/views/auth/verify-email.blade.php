@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'メール認証')

@section('content')
    <div class="auth">
        <div class="auth__card">
            <h1 class="auth__title">メール認証</h1>

            <p>登録したメールアドレスに認証メールを送信しました。</p>
            <p>メールに記載されたリンクをクリックして認証を完了してください。</p>

            @if (session('status') == 'verification-link-sent')
                <p>認証メールを再送信しました。</p>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="auth__form">
                @csrf
                <button type="submit" class="auth__submit">認証メールを再送する</button>
            </form>
        </div>
    </div>
@endsection
