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
                        <th>訪問ステータス</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->hour }}時</td>
                            <td>
                                <a
                                    href="{{ route('customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                            </td>
                            <td>
                                <form>
                                    <select name="status" class="custom-select custom-select-sm w-75">
                                        <option value="0" selected>{{ App\Appointment::STATUS_LIST[0] }}</option>
                                        <option value="1">{{ App\Appointment::STATUS_LIST[1] }}</option>
                                        <option value="2">{{ App\Appointment::STATUS_LIST[2] }}</option>
                                        <option value="3">{{ App\Appointment::STATUS_LIST[3] }}</option>
                                    </select>
                                </form>
                            </td>
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
