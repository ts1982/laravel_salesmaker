@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">{{ $user->name }}の営業予定</h1>
    <form action="{{ route('dashboard.calendar.seller_calendar') }}" method="get">
        <input type="hidden" name="period" value="{{ $period }}">
        <div class="row">
            <label for="seller-select" class="col-md-1 col-form-label text-md-right ml-md-4"><strong>選択</strong></label>
            <select name="seller_id" id="seller-select" class="col-md-2 form-control" onchange="this.form.submit();">
                @foreach ($sellers as $seller)
                    @if ($seller->id === $user->id)
                        <option value="{{ $seller->id }}" selected>{{ $seller->name }}</option>
                    @else
                        <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="col-md-11 p-0">
            <div class="row justify-content-between">
                <a
                    href="{{ url('dashboard/seller/calendar/?period=' . App\Appointment::getPrevPeriod($period)) . '&seller_id=' . $seller_id }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
                <h3>{{ date('Y年n月', strtotime($period . '-01')) }}</h4>
                <a
                    href="{{ url('dashboard/seller/calendar/?period=' . App\Appointment::getNextPeriod($period)) . '&seller_id=' . $seller_id }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
            </div>
            <div class="d-md-flex mb-2" id="calendar">
                <table class="table text-center mr-5">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
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
                                    <span>{{ $start_day->format('n/j') }}</span>
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
                                            <span class="{{ App\Appointment::isNow($start_day, $time) }}">○</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
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
                                    <span>{{ $middle_day->format('n/j') }}</span>
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
                                            <span class="{{ App\Appointment::isNow($middle_day, $time) }}">○</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
