@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント詳細</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">日時</th>
                        <td>{{ date('Y年n月j日', strtotime($appointment->day)) }}&emsp;{{ $appointment->hour }}時</td>
                    </tr>
                    <tr>
                        <th scope="row">顧客名</th>
                        <td>{{ $appointment->customer->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">ヒアリング内容</th>
                        <td>{{ $appointment->content }}</td>
                    </tr>
                    <tr>
                        <th scope="row">アポインター</th>
                        <td>{{ $appointer->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">営業担当者</th>
                        <td>{{ $seller->name }}</td>
                    </tr>
                </tbody>
            </table>
            <a href="/appointments">アポイント一覧に戻る</a>
        </div>
    </div>
@endsection
