@extends('layouts.app')

@section('content')
    <h1 class="text-center">マイレコード</h1>
    <h3 class="text-center mt-4">{{ date('Y年n月', strtotime($period . '-01')) }}</h3>
    <div class="row justify-content-center">
        <div class="term-changer row justify-content-between col-md-10 p-0">
            <a href="{{ url('/appointers/record/?period=' . App\Appointment::getPrevPeriod($period)) }}"><i
                    class="fas fa-angle-left"></i>&nbsp;Prev</a>
            <a href="{{ url('/appointers/record/?period=' . App\Appointment::getNextPeriod($period)) }}">Next&nbsp;<i
                    class="fas fa-angle-right"></i></a>
        </div>
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
                            <td>
                                <a
                                    href="{{ route('customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                            </td>
                            <td>
                                <span class="{{ $appointment->statusIs()[0] }}">
                                    {{ $appointment->statusIs()[1] }}
                                </span>
                            </td>
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
