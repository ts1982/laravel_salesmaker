@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント作成</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('appointments.store', compact('customer')) }}" method="post">
                @csrf
                <input type="hidden" name="day" value="{{ $day }}">
                <input type="hidden" name="hour" value="{{ $hour }}">
                <div class="row mb-3">
                    <strong class="col-md-3 p-0">日時</strong>
                    <div class="col-md-9">{{ date('Y年n月j日', strtotime($day)) }}&emsp;{{ $hour }}時</div>
                </div>
                @if ($customer)
                    <div class="row mb-3">
                        <strong class="col-md-3 p-0">氏名</strong>
                        <div class="col-md-9">{{ $customer->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <strong class="col-md-3 p-0">住所</strong>
                        <div class="col-md-9">{{ $customer->address }}</div>
                    </div>
                    <div class="row mb-3">
                        <strong class="col-md-3 p-0">電話番号</strong>
                        <div class="col-md-9">{{ $customer->tel }}</div>
                    </div>
                @else
                    <div class="mb-3">
                        <label for="form-name" class="form-label">氏名</label>
                        <input type="name" name="name" value="{{ old('name') }}" class="form-control"
                            id="form-name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="form-address" class="form-label">住所</label>
                        <input type="address" name="address" value="{{ old('address') }}" class="form-control"
                            id="form-address">
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="form-tel" class="form-label">電話番号</label>
                        <input type="tel" name="tel" value="{{ old('tel') }}" class="form-control"
                            id="form-tel">
                        @error('tel')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label for="form-content" class="form-label">ヒアリング内容</label>
                    <textarea class="form-control" name="content" id="form-content" rows="5">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success" type="button">登録</button>
                </div>
            </form>
        </div>
    </div>
@endsection
