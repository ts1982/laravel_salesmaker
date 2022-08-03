@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">営業成績</h1>
    <div class="row justify-content-center">
        <div class="term-changer mt-4 text-center row justify-content-between col-md-8 p-0">
            <a
                href="{{ url('/dashboard/records/sellers/?period=' . App\Appointment::getPrevPeriod($period)) }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
            <h3>{{ date('Y年n月', strtotime($period . '-01')) }}</h4>
                <a
                    href="{{ url('/dashboard/records/sellers/?period=' . App\Appointment::getNextPeriod($period)) }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
        </div>
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
                    @foreach ($seller_list as $key => $val)
                        <tr>
                            <td>{{ $val[0] }}</td>
                            <td>{{ $val[2] }}</td>
                            <td>{{ $val[3] }}</td>
                            <td>{{ $val[4] }}%</td>
                            <td>{{ $val[1] }}</td>
                            <td><a
                                    href="{{ route('dashboard.users.sellers_record', ['user_id' => $key, 'period' => $period]) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
