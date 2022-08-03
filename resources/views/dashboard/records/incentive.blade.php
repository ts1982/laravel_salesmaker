@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">インセンティブ</h1>
    <div class="row justify-content-center">
        <div class="term-changer my-4 text-center row justify-content-between col-md-8 p-0">
            <a
                href="{{ url('/dashboard/records/incentive/?period=' . App\Appointment::getPrevPeriod($period)) }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
            <h3>{{ date('Y年n月', strtotime($period . '-01')) }}</h4>
                <a
                    href="{{ url('/dashboard/records/incentive/?period=' . App\Appointment::getNextPeriod($period)) }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
        </div>
        <div class="col-md-8 p-0">
            <h2 class="text-center">営業</h2>
            <table class="table text-right">
                <thead>
                    <tr>
                        <th>氏名</th>
                        <th>ランク</th>
                        <th>単価 × 成約件数 × 歩合率</th>
                        <th>支給額</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seller_list as $key => $val)
                        <tr>
                            <td>{{ $val[0] }}</td>
                            <td>{{ $val[1] }}</td>
                            <td>￥{{ number_format(App\User::SELLER_INCENTIVE_BASIC) }} × {{ $val[3] }}件 ×
                                {{ $val[5] * 100 }}％</td>
                            <td>￥{{ number_format($val[6]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="h4 text-right">
                <span>小計：￥{{ number_format($seller_total) }}</span>
            </div>
            <h2 class="text-center mt-5">アポインター</h2>
            <table class="table text-right">
                <thead>
                    <tr>
                        <th>氏名</th>
                        <th>ランク</th>
                        <th>単価 × 成約件数 × 歩合率</th>
                        <th>支給額</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointer_list as $key => $val)
                        <tr>
                            <td>{{ $val[0] }}</td>
                            <td>{{ $val[1] }}</td>
                            <td>￥{{ number_format(App\User::APPOINTER_INCENTIVE_BASIC) }} × {{ $val[3] }}件 ×
                                {{ $val[5] * 100 }}％</td>
                            <td>￥{{ number_format($val[6]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="h4 text-right">
                <span>小計：￥{{ number_format($appointer_total) }}</span>
            </div>
            <hr>
            <div class="h3 text-right mt-4">
                <span>合計：￥{{ number_format($seller_total + $appointer_total) }}</span>
            </div>
        </div>
    </div>
@endsection
