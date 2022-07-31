@extends('layouts.dashboard')

@section('content')
    @if ($user->role === 'seller')
        <h1 class="text-center mt-4">{{ date('Y年n月', strtotime($period . '-01')) }}営業成績</h1>
    @elseif ($user->role === 'appointer')
        <h1 class="text-center mt-4">{{ date('Y年n月', strtotime($period . '-01')) }}アポインター成績</h1>
    @endif
    <h2 class="text-center">氏名：{{ $user->name }}</h2>
    <div class="row justify-content-center mt-3">
        <div class="col-md-10 p-0">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>訪問件数</th>
                        <th>成約件数</th>
                        <th>成約率</th>
                        <th>ランク</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $total }}</td>
                        <td>{{ $contract_count }}</td>
                        <td>{{ $rate }}%</td>
                        <td>{{ $rank }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="term-changer mt-4">
            @if ($user->role == 'seller')
                <a
                    href="{{ url('/dashboard/sellers/record/?user=' . $user->id . '&period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
                <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
                <a
                    href="{{ url('/dashboard/sellers/record/?user=' . $user->id . '&period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
            @elseif ($user->role == 'appointer')
                <a
                    href="{{ url('/dashboard/appointers/record/?user=' . $user->id . '&period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
                <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
                <a
                    href="{{ url('/dashboard/appointers/record/?user=' . $user->id . '&period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
            @endif
        </div>
        <div class="col-md-10 p-0">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>日時</th>
                        <th>顧客名</th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    ステータス
                                </div>
                                <div class="dropdown-menu">
                                    @if ($user->role == 'seller')
                                        <a href="{{ route('dashboard.users.sellers_record', ['period' => $period, 'user_id' => $user]) }}"
                                            class="dropdown-item">全て表示</a>
                                        @foreach ($sort_query as $key => $val)
                                            <a href="{{ route('dashboard.users.sellers_record', ['sort' => $val, 'period' => $period, 'user_id' => $user]) }}"
                                                class="dropdown-item">{{ $val }}</a>
                                        @endforeach
                                    @elseif ($user->role == 'appointer')
                                        <a href="{{ route('dashboard.users.appointers_record', ['period' => $period, 'user_id' => $user]) }}"
                                            class="dropdown-item">全て表示</a>
                                        @foreach ($sort_query as $key => $val)
                                            <a href="{{ route('dashboard.users.appointers_record', ['sort' => $val, 'period' => $period, 'user_id' => $user]) }}"
                                                class="dropdown-item">{{ $val }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </th>
                        {{-- <th>アポインター</th> --}}
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>
                                <strong>{{ $i++ }}</strong>
                            </td>
                            <td>
                                {{ date('j日', strtotime($appointment->day)) }}&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時
                            </td>
                            <td>
                                <a
                                    href="{{ route('dashboard.customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                            </td>
                            <td class="status-color{{ $appointment->statusIs()[0] }}">
                                {{ $appointment->statusIs()[1] }}
                            </td>
                            {{-- <td>{{ $appointment->thisAppointerHas()->name }}</td> --}}
                            <td>
                                <a href="{{ route('dashboard.appointments.show', compact('appointment')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
