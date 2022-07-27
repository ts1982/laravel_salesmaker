@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center mt-4">{{ date('Y年n月', strtotime($period . '-01')) }}営業成績</h1>
    <div class="term-changer mt-4 text-center">
        <a
            href="{{ url('/dashboard/records/appointers/?period=' . App\Appointment::getPrevPeriod($period)) }}">&lt;&lt;&nbsp;prev</a>
        <span>{{ date('Y年n月', strtotime($period . '-01')) }}</span>
        <a
            href="{{ url('/dashboard/records/appointers/?period=' . App\Appointment::getNextPeriod($period)) }}">next&nbsp;&gt;&gt;</a>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-8 p-0">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>氏名</th>
                        <th>訪問件数</th>
                        <th>成約件数</th>
                        <th>成約率</th>
                        <th>ランク</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointer_list as $key => $val)
                        <tr>
                            <td>{{ $val[0] }}</td>
                            <td>{{ $val[2] }}</td>
                            <td>{{ $val[3] }}</td>
                            <td>{{ $val[4] }}%</td>
                            <td>{{ $val[1] }}</td>
                            <td><a
                                    href="{{ route('dashboard.users.appointers_record', ['user_id' => $key, 'period' => $period]) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
