@extends('layouts.dashboard')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">ユーザー詳細情報</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-9 p-0">
            <form action="{{ route('dashboard.users.update', compact('user')) }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="form-name" class="col-form-label col-md-3 text-md-right">氏名</label>
                    <div class="col-md-9">
                        <input type="name" name="name" id="name" value="{{ $user->name }}"
                            class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="form-email" class="col-form-label col-md-3 text-md-right">メールアドレス</label>
                    <div class="col-md-9">
                        <input type="email" name="email" id="email" value="{{ $user->email }}"
                            class="form-control">
                    </div>
                </div>
                @if ($user->role === 'seller')
                    <div class="row mb-3">
                        <label for="form-start" class="col-form-label col-md-3 text-md-right">開始日</label>
                        <div class="col-md-9">
                            <input type="date" name="start" value="{{ $user->start }}" id="form-start"
                                class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="form-start" class="col-form-label col-md-3 text-md-right">終了日</label>
                        <div class="col-md-9">
                            <input type="date" name="end" value="{{ $user->end }}" id="form-start"
                                class="form-control">
                        </div>
                    </div>
                @endif
                <div class="col-12 d-flex justify-content-end">
                    <input type="submit" class="btn btn-success" value="更新">
                </div>
            </form>
        </div>
    </div>
@endsection
