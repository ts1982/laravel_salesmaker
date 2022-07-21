@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント詳細</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <div class="row mb-3">
                <strong class="col-md-3">日時</strong>
                <div class="col-md-9">
                    <span><a
                            href="{{ route('appointments.byday', ['day' => $appointment->day]) }}">{{ date('Y/m/d', strtotime($appointment->day)) }}</a>&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時</span>
                </div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">顧客名</strong>
                <div class="col-md-9">
                    <a
                        href="{{ route('customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                </div>
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
                <strong class="col-md-3">ヒアリング内容</strong>
                <div class="col-md-9">{{ $appointment->content }}</div>
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
                <a href="{{ route('appointments.edit', compact('appointment')) }}"
                    class="btn btn-warning mr-2 @if ($appointment->status != 0) event-none @endif">編集</a>
                <form action="{{ route('appointments.destroy', compact('appointment')) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger" @if ($appointment->status != 0) disabled @endif>削除
                    </button>
                </form>
            </div>
            @if ($appointment->status != 0)
                <h2 class="text-center mt-4">営業結果報告</h2>
                <div class="row mb-3">
                    <strong class="col-md-3">ステータス</strong>
                    <div class="col-md-9">{{ $appointment->statusIs()[1] }}</div>
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">報告内容</strong>
                    <div class="col-md-9">{{ $appointment->report }}</div>
                </div>
            @endif
        </div>
    </div>
@endsection
