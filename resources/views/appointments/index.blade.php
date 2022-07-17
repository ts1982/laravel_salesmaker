@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-4">アポイント登録</h1>
    <div class="row justify-content-around mb-2">
        <table class="table @if (App\User::roleIs('appointer')) table-hover @endif col-md-5 text-center">
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
                                    <span>{{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}</span>
                                @elseif ($appointments_prev[$start_day->format('Y-m-d')][$time] === 0)
                                    <span class="link-disabled">0</span>
                                @else
                                    <a
                                        href="{{ route('appointments.create', ['day' => $start_day->toDateString(), 'hour' => $time]) }}">
                                        {{ $appointments_prev[$start_day->format('Y-m-d')][$time] }}
                                    </a>
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
                    <th>10時</th>
                    <th>13時</th>
                    <th>16時</th>
                    <th>19時</th>
                </tr>
            </thead>
            <tbody>
                @for ($middle_day; $middle_day <= $end_day; $middle_day->addDay())
                    <tr>
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
                                    <span>{{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}</span>
                                @elseif ($appointments_later[$middle_day->format('Y-m-d')][$time] === 0)
                                    <span class="link-disabled">0</span>
                                @else
                                    <a
                                        href="{{ route('appointments.create', ['day' => $middle_day->toDateString(), 'hour' => $time]) }}">
                                        {{ $appointments_later[$middle_day->format('Y-m-d')][$time] }}
                                    </a>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
