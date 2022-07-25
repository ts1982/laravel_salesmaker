@extends('layouts.app')

@section('content')
    <h1 class="text-center mt-4">{{ date('Y年n月', strtotime($period . '-01')) }}アポインター成績</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-md-10 p-0">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>アポイント件数</th>
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
            <a
                href="{{ url('/appointers/record/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
            <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
            <a
                href="{{ url('/appointers/record/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
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
                                    <a href="{{ route('users.appointer_record', compact('period')) }}"
                                        class="dropdown-item">全て表示</a>
                                    @foreach ($sort_query as $key => $val)
                                        <a href="{{ route('users.appointer_record', ['sort' => $val, 'period' => $period]) }}"
                                            class="dropdown-item">{{ $val }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </th>
                        {{-- <th>営業担当者</th> --}}
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                {{ date('j日', strtotime($appointment->day)) }}&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時
                            </td>
                            <td>{{ $appointment->customer->name }}</td>
                            <td class="status-color{{ $appointment->statusIs()[0] }}">
                                {{ $appointment->statusIs()[1] }}
                            </td>
                            {{-- <td>{{ $appointment->thisSellerHas()->name }}</td> --}}
                            <td>
                                <a href="{{ route('appointments.show', compact('appointment')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
