@extends('layouts.dashboard')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">アポイント一覧</h1>
    <div class="text-center">
        <a
            href="{{ url('/dashboard/appointments/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
        <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
        <a
            href="{{ url('/dashboard/appointments/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
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
                    <tr>
                        <td>
                            <a href="{{ route('dashboard.appointments.byday', ['day' => $start_day->format('Y-m-d')]) }}">
                                <span>{{ $start_day->format('n/j') }}</span>
                            </a>
                        </td>
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
                                @if ($appointments_prev[$start_day->format('Y-m-d')][$time] === 0)
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }}">0</span>
                                @else
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }}">
                                        {{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}
                                    </span>
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
                    <tr>
                        <td>
                            <a href="{{ route('dashboard.appointments.byday', ['day' => $middle_day->format('Y-m-d')]) }}">
                                <span>{{ $middle_day->format('n/j') }}</span>
                            </a>
                        </td>
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
                                @if ($appointments_later[$middle_day->format('Y-m-d')][$time] === 0)
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }}">0</span>
                                @else
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }}">
                                        {{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
