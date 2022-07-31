@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="mb-5 text-center">顧客詳細情報</h1>
    <div class="row justify-content-center">
        <div class="col-sm-6 p-0">
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
                <a href="{{ route('customers.edit', compact('customer')) }}" class="btn btn-warning">編集</a>
            </div>
        </div>
    </div>
    <h3 class="text-center mt-4">アポイント履歴</h3>
    <div class="row justify-content-center">
        <div class="col-md-10 p-0">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>日時</th>
                        <th class="text-center">ステータス</th>
                        <th>アポインター</th>
                        <th>営業担当</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>
                                <a
                                    href="{{ route('appointments.byday', ['day' => $appointment->day]) }}">{{ date('Y/m/d', strtotime($appointment->day)) }}</a>&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時
                            </td>
                            <td class="status-color{{ $appointment->statusIs()[0] }}">
                                {{ $appointment->statusIs()[1] }}</td>
                            <td>{{ $appointment->thisAppointerHas()->name }}</td>
                            <td>{{ $appointment->thisSellerHas()->name }}</td>
                            <td>
                                <a href="{{ route('appointments.show', compact('appointment')) }}">詳細</a>
                            </td>
                            <td>
                                @if (App\User::roleIs('seller'))
                                    @if ($appointment->status === 0)
                                        <a href="{{ route('appointments.report', compact('appointment')) }}">報告</a>
                                    @else
                                        <a href="#" class="event-none">報告済</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <a href="{{ route('users.seller_calendar', compact('customer')) }}"
                    class="btn btn-outline-success">アポイント作成</a>
            </div>
        </div>
    </div>
@endsection
