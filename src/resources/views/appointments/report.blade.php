@extends('layouts.app')

@section('content')
    <h1 class="text-center">営業結果報告</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('appointments.change_status', compact('appointment')) }}" method="post" class="mt-3">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <strong class="col-md-3">ステータス</strong>
                    <div class="col-md-9">
                        <select name="status" class="custom-select custom-select-sm">
                            @foreach ($status_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong for="form-content" class="form-label">報告内容</strong>
                    </div>
                    <div class="col-md-9">
                        <textarea class="form-control" name="report" id="form-content" rows="7">{{ old('report') }}</textarea>
                        @error('report')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="送信" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
