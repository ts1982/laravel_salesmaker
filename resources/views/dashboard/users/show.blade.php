@extends('layouts.dashboard')

@section('content')
    <h1 class="mb-5 text-center">ユーザー詳細情報</h1>
    <div class="row justify-content-center">
        <div class="col-sm-6 p-0">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>氏名</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $user->name }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>メールアドレス</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $user->email }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>役割</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $user->role }}</span>
                </div>
            </div>
            @if ($user->role == 'seller')
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>開始日</strong>
                    </div>
                    <div class="col-md-8">
                        <span>{{ $user->start }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>終了日</strong>
                    </div>
                    <div class="col-md-8">
                        <span>{{ $user->end }}</span>
                    </div>
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <a href="{{ route('dashboard.users.edit', compact('user')) }}" class="btn btn-warning">編集</a>
            </div>
        </div>
    </div>
@endsection
