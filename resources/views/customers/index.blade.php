@extends('layouts.app')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>顧客名</th>
                        <th>住所</th>
                        <th>電話番号</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->tel }}</td>
                            <td>
                                <a href="{{ route('customers.show', compact('customer')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
