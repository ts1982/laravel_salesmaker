@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">営業結果報告</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('appointments.change_status', compact('appointment')) }}" method="post" class="mt-3">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <strong class="col-md-3">ステータス</strong>
                    <div class="col-md-9 p-0">
                        <select name="status" class="custom-select custom-select-sm">
                            @foreach ($status_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <strong for="form-content" class="form-label col-md-3">報告内容</strong>
                    <textarea class="form-control col-md-9" name="report" id="form-content" rows="7">{{ old('report') }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="送信" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
