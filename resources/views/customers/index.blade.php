@extends('layouts.app')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-sm-7">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>顧客名</th>
                        <th>ステータス</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td class="status-color{{ $customer->appointments->last()->statusIs()[0] }}">{{ $customer->appointments->last()->statusIs()[1] }}</td>
                            <td>
                                <a href="{{ route('customers.show', compact('customer')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">{{ $customers->links() }}</div>
        </div>
    </div>
@endsection
