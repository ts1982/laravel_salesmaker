@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    @if ($seller_appointment)
        <div class="alert alert-warning">{{ date('Y年n月j日', strtotime($day)) }}&emsp;{{ $hour }}時から変更</div>
    @endif
    @if (App\User::roleIs('appointer') && $seller)
        <h1 class="text-center">{{ $seller->name }}の営業予定</h1>
    @else
        <h1 class="text-center">マイカレンダー</h1>
    @endif
    <div class="text-center">
        @if ($customer)
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getPrevPeriod($period) . '&customer=' . $customer) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getNextPeriod($period) . '&customer=' . $customer) }}">next&nbsp;&gt;&gt;</a>
        @elseif ($seller_appointment && $seller)
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getPrevPeriod($period) . '&seller_appointment=' . $seller_appointment . '&seller=' . $seller->id . '&day=' . $day . '&hour=' . $hour) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getNextPeriod($period) . '&seller_appointment=' . $seller_appointment . '&seller=' . $seller->id . '&day=' . $day . '&hour=' . $hour) }}">next&nbsp;&gt;&gt;</a>
        @else
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/sellers/calendar/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
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
                    <tr @if ($user->userHasHoliday($start_day->format('Y-m-d')) || !$user->betweenStartAndEnd($start_day)) class="tr-dark" @endif>
                        <td>
                            @if ($seller_appointment || $customer)
                                <span>{{ $start_day->format('n/j') }}</span>
                            @else
                                <a href="{{ route('appointments.byday', ['day' => $start_day->format('Y-m-d')]) }}">
                                    <span>{{ $start_day->format('n/j') }}</span>
                                </a>
                            @endif
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
                                @if (!$user->betweenStartAndEnd($start_day))
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }}">-</span>
                                @elseif (isset($hasAppointments[$start_day->format('Y-m-d')][$time]))
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }}">×</span>
                                @elseif ($user->userHasHoliday($start_day->format('Y-m-d')))
                                    <span class="{{ App\Appointment::isNow($start_day, $time) }}">-</span>
                                @else
                                    @if ($seller_appointment)
                                        <form
                                            action="{{ route('appointments.date_update', ['day' => $start_day->format('Y-m-d'), 'hour' => $time, 'appointment' => $seller_appointment]) }}"
                                            method="post" id="{{ $start_day . '&' . $time }}">
                                            @csrf
                                            @method('put')
                                            <a href="#"
                                                onclick="document.getElementById('{{ $start_day . '&' . $time }}').submit(); return false;"
                                                class="{{ App\Appointment::isNow($start_day, $time) }}">○</a>
                                        </form>
                                    @else
                                        @if ($customer)
                                            <a href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}"
                                                class="{{ App\Appointment::isNow($start_day, $time) }}">○</a>
                                        @else
                                            <a href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time]) }}"
                                                class="{{ App\Appointment::isNow($start_day, $time) }}">○</a>
                                        @endif
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
                    <tr @if ($user->userHasHoliday($middle_day->format('Y-m-d')) || !$user->betweenStartAndEnd($middle_day)) class="tr-dark" @endif>
                        <td>
                            @if ($seller_appointment || $customer)
                                <span>{{ $middle_day->format('n/j') }}</span>
                            @else
                                <a href="{{ route('appointments.byday', ['day' => $middle_day->format('Y-m-d')]) }}">
                                    <span>{{ $middle_day->format('n/j') }}</span>
                                </a>
                            @endif
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
                                @if (!$user->betweenStartAndEnd($middle_day))
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }}">-</span>
                                @elseif (isset($hasAppointments[$middle_day->format('Y-m-d')][$time]))
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }}">×</span>
                                @elseif ($user->userHasHoliday($middle_day->format('Y-m-d')))
                                    <span class="{{ App\Appointment::isNow($middle_day, $time) }}">-</span>
                                @else
                                    @if ($seller_appointment)
                                        <form
                                            action="{{ route('appointments.date_update', ['day' => $middle_day->format('Y-m-d'), 'hour' => $time, 'appointment' => $seller_appointment]) }}"
                                            method="post" id="{{ $middle_day . '&' . $time }}">
                                            @csrf
                                            @method('put')
                                            <a href="#"
                                                onclick="document.getElementById('{{ $middle_day . '&' . $time }}').submit(); return false;"
                                                class="{{ App\Appointment::isNow($middle_day, $time) }}">○</a>
                                        </form>
                                    @else
                                        @if ($customer)
                                            <a href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time, 'customer' => $customer]) }}"
                                                class="{{ App\Appointment::isNow($middle_day, $time) }}">○</a>
                                        @else
                                            <a href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time]) }}"
                                                class="{{ App\Appointment::isNow($middle_day, $time) }}">○</a>
                                        @endif
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
