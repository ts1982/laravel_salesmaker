@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">アポイント作成</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 p-0">
            <form action="{{ route('appointments.store', compact('customer')) }}" method="post">
                @csrf
                <input type="hidden" name="day" value="{{ $day }}">
                <input type="hidden" name="hour" value="{{ $hour }}">
                <div class="row mb-3">
                    <strong class="col-md-3 text-md-right">日時</strong>
                    <div class="col-md-9">{{ date('Y年n月j日', strtotime($day)) }}&emsp;{{ $hour }}時</div>
                </div>
                @if ($customer)
                    <div class="row mb-3">
                        <strong class="col-md-3 text-md-right">氏名</strong>
                        <div class="col-md-9">{{ $customer->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <strong class="col-md-3 text-md-right">住所</strong>
                        <div class="col-md-9">{{ $customer->address }}</div>
                    </div>
                    <div class="row mb-3">
                        <strong class="col-md-3 text-md-right">電話番号</strong>
                        <div class="col-md-9">{{ $customer->tel }}</div>
                    </div>
                @else
                    <div class="row mb-3">
                        <label for="form-name" class="col-form-label col-md-3 text-md-right"><strong>氏名</strong></label>
                        <div class="col-md-9">
                            <input type="name" name="name" value="{{ old('name') }}" class="form-control"
                                id="form-name">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="form-address" class="col-form-label col-md-3 text-md-right"><strong>住所</strong></label>
                        <div class="col-md-9">
                            <input type="address" name="address" value="{{ old('address') }}" class="form-control"
                                id="form-addre
                            </div>ss">
                            @error('address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="form-tel" class="col-form-label col-md-3 text-md-right"><strong>電話番号</strong></label>
                        <div class="col-md-9">
                            <input type="tel" name="tel" value="{{ old('tel') }}" class="form-control"
                                id="form-tel">
                            @error('tel')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif
                <div class="row mb-3">
                    <label for="form-content" class="col-form-label col-md-3 text-md-right"><strong>ヒアリング内容</strong></label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="content" id="form-content" rows="5">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success mr-3" type="button">登録</button>
                </div>
            </form>
        </div>
    </div>
@endsection
