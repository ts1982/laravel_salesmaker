@extends('layouts.app')

@section('content')
    <h1 class="text-center">日別アポイント情報</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <h2 class="mb-3">{{ $day->format('Y年n月j日') }}&nbsp;({{ mb_substr($day->dayName, 0, 1) }})</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>日時</th>
                        <th>顧客名</th>
                        <th>住所</th>
                        <th>電話番号</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->hour }}時</td>
                            <td>{{ $appointment->customer->name }}</td>
                            <td>{{ $appointment->customer->address }}</td>
                            <td>{{ $appointment->customer->tel }}</td>
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
