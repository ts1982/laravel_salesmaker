@extends('layouts.dashboard')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">ユーザー情報編集</h1>
    <div class="row justify-content-center mt-4">
        <div class="col-md-9 p-0">
            @if ($user->role === 'seller')
                <!-- Button trigger modal -->
                <div class="d-flex justify-content-end mb-4">
                    <button class="btn btn-danger btn-sm mr-3" type="button" data-toggle="modal"
                        data-target="#exampleModal">担当顧客削除</button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">担当顧客削除</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                担当顧客を削除します。この操作は元に戻せませんが、よろしいですか？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">戻る</button>
                                <a href="{{ route('dashboard.users.reset_customers', compact('user')) }}"
                                    class="btn btn-danger">実行</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
