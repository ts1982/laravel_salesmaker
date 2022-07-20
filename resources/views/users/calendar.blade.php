@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    @if ($day && $hour)
        <div class="alert alert-warning">{{ date('Y年n月j日', strtotime($day)) }}&emsp;{{ $hour }}時から変更</div>
    @endif
    @if (App\User::roleIs('seller') || $seller_appointment)
        <h1 class="text-center">{{ $user->name }}の営業予定</h1>
    @elseif (App\User::roleIs('appointer'))
        <h1 class="text-center">マイアポイント</h1>
    @endif
    <div class="text-center">
        @if ($customer)
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getPrevPeriod($period) . '&customer=' . $customer) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getNextPeriod($period) . '&customer=' . $customer) }}">next&nbsp;&gt;&gt;</a>
        @elseif ($seller_appointment && $seller)
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getPrevPeriod($period) . '&seller_appointment=' . $seller_appointment . '&seller=' . $seller) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getNextPeriod($period) . '&seller_appointment=' . $seller_appointment . '&seller=' . $seller) }}">next&nbsp;&gt;&gt;</a>
        @else
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/users/calendar/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
        @endif
    </div>
    <div class="row justify-content-around mb-2">
        <table class="table table-hover col-md-5 text-center">
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
                            <a href="{{ route('appointments.byday', ['day' => $start_day->format('Y-m-d')]) }}">
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
                                @if (App\User::roleIs('seller') || $seller_appointment)
                                    @if (isset($hasAppointments[$start_day->format('Y-m-d')][$time]))
                                        <span>×</span>
                                    @else
                                        @if ($seller_appointment)
                                            <a
                                                href="{{ route('appointments.edit', ['day' => $start_day->toDateString(), 'hour' => $time, 'appointment' => $seller_appointment]) }}">○</a>
                                        @else
                                            @if ($customer)
                                                <a
                                                    href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}">○</a>
                                            @else
                                                <a
                                                    href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time]) }}">○</a>
                                            @endif
                                        @endif
                                    @endif
                                @elseif (App\User::roleIs('appointer'))
                                    @if (isset($hasAppointments[$start_day->format('Y-m-d')][$time]))
                                        <span>{{ $hasAppointments[$start_day->format('Y-m-d')][$time] }}</span>
                                    @else
                                        <span>0</span>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
        <table class="table table-hover col-md-5 text-center">
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
                            <a href="{{ route('appointments.byday', ['day' => $middle_day->format('Y-m-d')]) }}">
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
                                @if (App\User::roleIs('seller') || $seller_appointment)
                                    @if (isset($hasAppointments[$middle_day->format('Y-m-d')][$time]))
                                        <span>×</span>
                                    @else
                                        @if ($seller_appointment)
                                            <a
                                                href="{{ route('appointments.edit', ['day' => $middle_day->toDateString(), 'hour' => $time, 'appointment' => $seller_appointment]) }}">○</a>
                                        @else
                                            @if ($customer)
                                                <a
                                                    href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}">○</a>
                                            @else
                                                <a
                                                    href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time]) }}">○</a>
                                            @endif
                                        @endif
                                    @endif
                                @elseif (App\User::roleIs('appointer'))
                                    @if (isset($hasAppointments[$middle_day->format('Y-m-d')][$time]))
                                        <span>{{ $hasAppointments[$middle_day->format('Y-m-d')][$time] }}</span>
                                    @else
                                        <span>0</span>
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
