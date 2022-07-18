@extends('layouts.app')

@section('content')
    <h1 class="mb-5 text-center">顧客詳細情報</h1>
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>顧客名</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->name }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>住所</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->address }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>電話番号</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->tel }}</span>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @if (App\User::roleIs('seller'))
                    <a href="{{ route('users.calendar', compact('customer')) }}" class="btn btn-primary mr-3">作成</a>
                @else
                    <a href="{{ route('appointments.index', compact('customer')) }}" class="btn btn-primary mr-3">作成</a>
                @endif
                <a href="{{ route('customers.edit', compact('customer')) }}" class="btn btn-warning">編集</a>
            </div>
        </div>
    </div>
    <h3 class="text-center mt-4">アポイント履歴</h3>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>日時</th>
                        <th>顧客名</th>
                        <th>アポインター</th>
                        <th>営業担当</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ date('Y/m/d', strtotime($appointment->day)) }}&emsp;{{ $appointment->hour }}時</td>
                            <td>{{ $appointment->customer->name }}</td>
                            <td>{{ $appointment->thisAppointerHas()->name }}</td>
                            <td>{{ $appointment->thisSellerHas()->name }}</td>
                            <td>
                                <a href="{{ route('appointments.show', compact('appointment')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
