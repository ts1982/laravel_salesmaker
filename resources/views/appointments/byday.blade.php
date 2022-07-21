@extends('layouts.app')

@section('content')
    <h1 class="text-center">日別アポイント情報</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <h2 class="mb-3 text-center">{{ $day->copy()->format('Y年n月j日') }}&nbsp;({{ mb_substr($day->copy()->dayName, 0, 1) }})</h2>
            <div class="text-center">
                <a
                    href="{{ url('/appointments/byday/?day=' .$day->copy()->subDay()->format('Y-m-d')) }}">&lt;&lt;&nbsp;prev</a>
                <span>{{ $day->format('Y/n/j') }}</span>
                <a
                    href="{{ url('/appointments/byday/?day=' .$day->copy()->addDay()->format('Y-m-d')) }}">next&nbsp;&gt;&gt;</a>
            </div>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>日時</th>
                        <th>顧客名</th>
                        <th>ステータス</th>
                        <th></th>
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
                            <td class="status-color{{ $appointment->statusIs()[0] }}">
                                {{ $appointment->statusIs()[1] }}</td>
                            {{-- <td>
                                <form action="{{ route('appointments.change_status', compact('appointment')) }}"
                                    method="post">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="day" value="{{ $day }}">
                                    <select name="status" class="custom-select custom-select-sm w-75"
                                        onchange="this.form.submit();">
                                        @foreach ($status_list as $key => $value)
                                            @if ($appointment->status === $key)
                                                <option value="{{ $key }}" selected>{{ $value }}
                                                </option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                            </td> --}}
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
        </div>
    </div>
@endsection
