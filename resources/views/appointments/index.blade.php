@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント</h1>
    <div class="row justify-content-around mx-2">
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
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $start_day->toDateString(), 'hour' => 10]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $start_day->toDateString(), 'hour' => 13]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $start_day->toDateString(), 'hour' => 16]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $start_day->toDateString(), 'hour' => 19]) }}">click</a>
                        </td>
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
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $middle_day->toDateString(), 'hour' => 10]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $middle_day->toDateString(), 'hour' => 13]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $middle_day->toDateString(), 'hour' => 16]) }}">click</a>
                        </td>
                        <td>
                            <a
                                href="{{ route('appointments.index', ['day' => $middle_day->toDateString(), 'hour' => 19]) }}">click</a>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
