@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント情報編集</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="row mb-3">
                <strong class="col-md-3">日時</strong>
                <div class="col-md-9">
                    {{ date('Y年n月j日', strtotime($appointment->day)) }}&emsp;{{ $appointment->hour }}時</div>
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
                <div class="d-flex justify-content-end">
                    <input type="submit" value="更新" class="btn btn-success">
                </div>
            </form>
            <div class="row mb-3">
                <strong class="col-md-3">アポインター</strong>
                <div class="col-md-9">{{ $appointer->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">営業担当者</strong>
                <div class="col-md-9">{{ $seller->name }}</div>
            </div>
        </div>
    </div>
@endsection
