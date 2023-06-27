@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">パスワード変更</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('users.update_password') }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <div class="col-md-4">パスワード</div>
                    <input type="password" name="password" class="form-control col-md-8">
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">パスワード確認</div>
                    <input type="password" name="confirm_password" class="form-control col-md-8">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">更新</button>
                </div>
            </form>
        </div>
    </div>
@endsection
