@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">日別アポイント情報</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <h2 class="mb-3 text-center">
                {{ $day->copy()->format('Y年n月j日') }}&nbsp;({{ mb_substr($day->copy()->dayName, 0, 1) }})</h2>
            <div class="row justify-content-between mx-4">
                <a
                    href="{{ url('/dashboard/appointments/byday/?day=' .$day->copy()->subDay()->format('Y-m-d')) .'&seller_sort=' .$seller_sort .'&appointer_sort=' .$appointer_sort }}"><i class="fas fa-angle-left"></i>&nbsp;Prev</a>
                <a
                    href="{{ url('/dashboard/appointments/byday/?day=' .$day->copy()->addDay()->format('Y-m-d')) .'&seller_sort=' .$seller_sort .'&appointer_sort=' .$appointer_sort }}">Next&nbsp;<i class="fas fa-angle-right"></i></a>
            </div>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>日時</th>
                        <th>顧客名</th>
                        <th>ステータス</th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    アポインター
                                </div>
                                <div class="dropdown-menu">
                                    <a href="{{ route('dashboard.appointments.byday', ['day' => $day->copy()->format('Y-m-d'), 'seller_sort' => $seller_sort]) }}"
                                        class="dropdown-item">全て表示</a>
                                    @foreach ($appointers as $appointer)
                                        <a href="{{ route('dashboard.appointments.byday', ['day' => $day->copy()->format('Y-m-d'), 'appointer_sort' => $appointer->id, 'seller_sort' => $seller_sort]) }}"
                                            class="dropdown-item">{{ $appointer->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    営業担当者
                                </div>
                                <div class="dropdown-menu">
                                    <a href="{{ route('dashboard.appointments.byday', ['day' => $day->copy()->format('Y-m-d'), 'appointer_sort' => $appointer_sort]) }}"
                                        class="dropdown-item">全て表示</a>
                                    @foreach ($sellers as $seller)
                                        <a href="{{ route('dashboard.appointments.byday', ['day' => $day->copy()->format('Y-m-d'), 'seller_sort' => $seller->id, 'appointer_sort' => $appointer_sort]) }}"
                                            class="dropdown-item">{{ $seller->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->hour }}時</td>
                            <td>
                                <a
                                    href="{{ route('dashboard.customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                            </td>
                            <td><span  class="{{ $appointment->statusIs()[0] }}">{{ $appointment->statusIs()[1] }}</span></td>
                            <td>{{ $appointment->thisAppointerHas()->name }}</td>
                            <td>{{ $appointment->thisSellerHas()->name }}</td>
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
