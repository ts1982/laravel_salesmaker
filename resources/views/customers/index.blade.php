@extends('layouts.app')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-sm-7">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>顧客名</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
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
