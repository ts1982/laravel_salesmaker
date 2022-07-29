@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">休日設定</h1>
    <div class="text-center">
        @if ($half === 'first')
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . App\Appointment::getPrevPeriod(explode('/', $period)[0])) . '/' . $half }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime(explode('/', $period)[0] . '-01')) }}</span>
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . explode('/', $period)[0]) . '/' . $half }}">next&nbsp;&gt;&gt;</a>
        @elseif ($half === 'second')
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . explode('/', $period)[0]) . '/' . $half }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime(explode('/', $period)[0] . '-01')) }}</span>
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . App\Appointment::getNextPeriod(explode('/', $period)[0])) . '/' . $half }}">next&nbsp;&gt;&gt;</a>
        @endif
    </div>
    <div class="row justify-content-center mb-2">
        <form action="{{ route('dashboard.appointments.holiday_store', compact('period')) }}" method="post">
            @csrf
            <input type="hidden" name="start_day" value="{{ $start_day }}">
            <input type="hidden" name="end_day" value="{{ $end_day }}">
            <div class="row justify-content-center">
                <table class="table col-md-5 text-center">
                    <thead>
                        <tr>
                            <th></th>
                            @for ($start_day; $start_day < $end_day; $start_day->addDay())
                                <th>
                                    <span>{{ $start_day->format('n/j') }}</span>
                                    @if ($start_day->isSaturday())
                                        <span class="saturday">{{ mb_substr($start_day->dayName, 0, 1) }}</span>
                                    @elseif ($start_day->isSunday())
                                        <span class="sunday">{{ mb_substr($start_day->dayName, 0, 1) }}</span>
                                    @else
                                        {{ mb_substr($start_day->dayName, 0, 1) }}
                                    @endif
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calendar as $id => $val)
                            <tr>
                                <th>{{ App\User::find($id)->name }}</th>
                                @foreach ($val as $day => $count)
                                    <td>
                                        <input type="checkbox" name="holidays[]" value="{{ $id . ',' . $day }}"
                                            @if ($count === 1) checked @elseif ($count === 2) disabled @endif>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">更新</button>
            </div>
        </form>
    </div>
@endsection
