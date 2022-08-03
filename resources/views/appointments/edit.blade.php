@extends('layouts.app')

@section('content')
    <h1 class="text-center">ヒアリング内容追加</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <div class="row mb-3">
                <strong class="col-md-3">日時</strong>
                <div class="col-md-5">
                    <span>{{ date('Y/m/d', strtotime($appointment->day)) }}&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時</span>
                </div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">顧客名</strong>
                <div class="col-md-9">{{ $appointment->customer->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">住所</strong>
                <div class="col-md-9">{{ $appointment->customer->address }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">電話番号</strong>
                <div class="col-md-9">{{ $appointment->customer->tel }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">アポインター</strong>
                <div class="col-md-9">{{ $appointment->user->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">営業担当者</strong>
                <div class="col-md-9">{{ $appointment->thisSellerHas()->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">ステータス</strong>
                <div class="col-md-9">
                    <span class="{{ $appointment->statusIs()[0] }}">{{ $appointment->statusIs()[1] }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">前回</strong>
                <div class="col-md-9">
                    @foreach ($appointment->contents as $content)
                        <div class="mb-4">
                            <div>
                                {{ date("Y/m/d ({$content->appointment->getDayName()}) H:i", strtotime($content->created_at)) }}&emsp;登録者：{{ $content->user->name }}
                            </div>
                            <div>{{ $content->content }}</div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
            <form action="{{ route('appointments.update', compact('appointment')) }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="form-content" class="form-label col-md-3">
                        <strong>ヒアリング内容</strong>
                    </label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="content" id="form-content" rows="7">{{ $appointment->content }}</textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="登録" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
