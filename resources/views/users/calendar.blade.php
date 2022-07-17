@extends('layouts.app')

@section('content')
    @if (App\User::roleIs('seller') || $seller_appointment)
        <h1 class="text-center mb-4">{{ $user->name }}の営業予定</h1>
    @elseif (App\User::roleIs('appointer'))
        <h1 class="text-center mb-4">マイアポイント</h1>
    @endif
    <div class="row justify-content-around mb-2">
        <table class="table table-hover col-md-5 text-center">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>曜日</th>
                    <th>10時</th>
                    <th>13時</th>
                    <th>16時</th>
                    <th>19時</th>
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
                                            <a
                                                href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time]) }}">○</a>
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
                    <th>10時</th>
                    <th>13時</th>
                    <th>16時</th>
                    <th>19時</th>
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
                                            <a
                                                href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time]) }}">○</a>
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
