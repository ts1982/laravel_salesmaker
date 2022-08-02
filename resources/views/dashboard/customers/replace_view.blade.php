@extends('layouts.dashboard')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">顧客振替</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-md-7">
            <a href="{{ route('dashboard.customers.replace') }}">&laquo;&nbsp;戻る</a>
            <table class="table text-center mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>顧客名</th>
                        <th>営業担当者</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->getSellerNameInCharge() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
