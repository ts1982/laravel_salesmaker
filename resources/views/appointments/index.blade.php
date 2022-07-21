@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    @if (App\User::roleIs('seller'))
        <h1 class="text-center">アポイント一覧</h1>
    @elseif (App\User::roleIs('appointer'))
        <h1 class="text-center">アポイント登録</h1>
    @endif
    <div class="text-center">
        @if ($customer)
            <a
                href="{{ url('/appointments/?period=' . App\Appointment::getPrevPeriod($period) . '&customer=' . $customer) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/appointments/?period=' . App\Appointment::getNextPeriod($period) . '&customer=' . $customer) }}">next&nbsp;&gt;&gt;</a>
        @else
            <a href="{{ url('/appointments/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a href="{{ url('/appointments/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
        @endif
    </div>
    <div class="row justify-content-around mb-2">
        <table class="table @if (App\User::roleIs('appointer')) table-hover @endif col-md-5 text-center">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>曜日</th>
                    <th>{{ App\Appointment::TIME_ZONE[0] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[1] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[2] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[3] }}時</th>
                </tr>
            </thead>
            <tbody>
                @for ($start_day; $start_day < $middle_day; $start_day->addDay())
                    <tr @if ($start_day->isToday()) class="mark-today" @endif>
                        <td>{{ $start_day->format('n/j') }}</td>
                        <td>
                            @if ($start_day->isSaturday())
                                <span class="saturday">{{ mb_substr($start_day->dayName, 0, 1) }}</span>
                            @elseif ($start_day->isSunday())
                                <span class="sunday">{{ mb_substr($start_day->dayName, 0, 1) }}</span>
                            @else
                                {{ mb_substr($start_day->dayName, 0, 1) }}
                            @endif
                        </td>
                        @foreach ($time_zone as $time)
                            <td>
                                @if (App\User::roleIs('seller'))
                                    <span
                                        class="{{ App\Appointment::isNow($start_day, $time) }}">{{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}</span>
                                @elseif ($appointments_prev[$start_day->format('Y-m-d')][$time] === 0)
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }} link-disabled">0</span>
                                @else
                                    @if ($customer)
                                        <a href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}"
                                            class="{{ App\Appointment::isNow($start_day, $time) }}">
                                            {{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}
                                        </a>
                                    @else
                                        <a href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time]) }}"
                                            class="{{ App\Appointment::isNow($start_day, $time) }}">
                                            {{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}
                                        </a>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
        <table class="table @if (App\User::roleIs('appointer')) table-hover @endif col-md-5 text-center">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>曜日</th>
                    <th>{{ App\Appointment::TIME_ZONE[0] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[1] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[2] }}時</th>
                    <th>{{ App\Appointment::TIME_ZONE[3] }}時</th>
                </tr>
            </thead>
            <tbody>
                @for ($middle_day; $middle_day <= $end_day; $middle_day->addDay())
                    <tr @if ($middle_day->isToday()) class="mark-today" @endif>
                        <td>{{ $middle_day->format('n/j') }}</td>
                        <td>
                            @if ($middle_day->isSaturday())
                                <span class="saturday">{{ mb_substr($middle_day->dayName, 0, 1) }}</span>
                            @elseif ($middle_day->isSunday())
                                <span class="sunday">{{ mb_substr($middle_day->dayName, 0, 1) }}</span>
                            @else
                                {{ mb_substr($middle_day->dayName, 0, 1) }}
                            @endif
                        </td>
                        @foreach ($time_zone as $time)
                            <td>
                                @if (App\User::roleIs('seller'))
                                    <span
                                        class="{{ App\Appointment::isNow($middle_day, $time) }}">{{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}</span>
                                @elseif ($appointments_later[$middle_day->format('Y-m-d')][$time] === 0)
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }} link-disabled">0</span>
                                @else
                                    @if ($customer)
                                        <a href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}"
                                            class="{{ App\Appointment::isNow($middle_day, $time) }}">
                                            {{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}
                                        </a>
                                    @else
                                        <a href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time]) }}"
                                            class="{{ App\Appointment::isNow($middle_day, $time) }}">
                                            {{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}
                                        </a>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
