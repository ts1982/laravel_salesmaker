@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント情報編集</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <div class="row mb-3">
                <strong class="col-md-3">日時</strong>
                <div class="col-md-5">
                    @if ($day && $hour)
                        <span>{{ date('Y/m/d', strtotime($day)) }}&nbsp;({{ mb_substr(Carbon\Carbon::parse($day)->dayName, 0, 1) }})&emsp;{{ $hour }}時</span>
                    @else
                        <span>{{ date('Y/m/d', strtotime($appointment->day)) }}&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <a href="{{ route('users.calendar', ['seller_appointment' => $seller_appointment, 'seller' => $seller, 'day' => $appointment->day, 'hour' => $appointment->hour]) }}"
                        class="btn btn-outline-primary btn-sm">日時変更</a>
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
            <form action="{{ route('appointments.update', compact('appointment')) }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <strong for="form-content" class="form-label col-md-3">ヒアリング内容</strong>
                    <textarea class="form-control col-md-9" name="content" id="form-content" rows="7">{{ $appointment->content }}</textarea>
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">アポインター</strong>
                    <div class="col-md-9">{{ $appointer->name }}</div>
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">営業担当者</strong>
                    <div class="col-md-9">{{ $seller->name }}</div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="hidden" name="day" value="{{ $day }}">
                    <input type="hidden" name="hour" value="{{ $hour }}">
                    <input type="submit" value="更新" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
