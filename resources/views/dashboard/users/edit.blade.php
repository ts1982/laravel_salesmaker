@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">ユーザー詳細情報</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-9 p-0">
            <form action="{{ route('dashboard.users.update', compact('user')) }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="form-name" class="col-form-label col-md-3 text-md-right">氏名</label>
                    <div class="col-md-9">
                        <input type="name" name="name" id="name" value="{{ $user->name }}" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="form-email" class="col-form-label col-md-3 text-md-right">メールアドレス</label>
                    <div class="col-md-9">
                        <input type="email" name="email" id="email" value="{{ $user->email }}"
                            class="form-control">
                    </div>
                </div>
                {{-- <div class="row mb-3">
                    <label for="role" class="col-form-label col-md-3">役割</label>
                    <div class="col-md-9">
                        <select name="role" id="role" type="role"
                            class="form-control @error('role') is-invalid @enderror">
                            <option value="">選択してください</option>
                            <option value="appointer">アポインター</option>
                            <option value="seller">営業</option>
                        </select>

                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> --}}
                <div class="col-12 d-flex justify-content-end">
                    <input type="submit" class="btn btn-success" value="更新">
                </div>
            </form>
        </div>
    </div>
@endsection
