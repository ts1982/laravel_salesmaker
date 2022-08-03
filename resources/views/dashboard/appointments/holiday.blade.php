@extends('layouts.dashboard')

@section('content')
    @if ($info)
        <div class="alert alert-warning">{{ $info }}</div>
    @endif
    <h1 class="text-center mb-4">休日設定</h1>
    <div class="row justify-content-between">
        @if ($half === 'later')
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . App\Appointment::getPrevPeriod(explode('/', $period)[0])) . '/' . $half }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
            <h4>{{ date('Y年n月', strtotime(explode('/', $period)[0] . '-01')) }}</h4>
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . explode('/', $period)[0]) . '/' . $half }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
        @elseif ($half === 'former')
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . explode('/', $period)[0]) . '/' . $half }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
            <h4>{{ date('Y年n月', strtotime(explode('/', $period)[0] . '-01')) }}</h4>
            <a
                href="{{ url('/dashboard/appointments/holiday/?period=' . App\Appointment::getNextPeriod(explode('/', $period)[0])) . '/' . $half }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
        @endif
    </div>
    <div class="row justify-content-center">
        <form action="{{ route('dashboard.appointments.holiday_store', compact('period')) }}" method="post">
            @csrf
            <input type="hidden" name="start_day" value="{{ $start_day }}">
            <input type="hidden" name="end_day" value="{{ $end_day }}">
            <table class="table col-md-12 text-center">
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
                                        @if ($count === 1) checked @elseif ($count === 2) disabled @elseif ($count === 3) class="display-hidden" @endif>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">更新</button>
            </div>
        </form>
    </div>
@endsection
