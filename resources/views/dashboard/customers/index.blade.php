@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <form action="{{ route('dashboard.customers.index') }}" method="get" class="d-flex row justify-content-center">
        <div class="d-flex col-md-4">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="seller_sort" value="{{ $seller_sort }}">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="顧客名">
            <button type="submit" class="btn btn-success w-25">検索</button>
        </div>
    </form>
    <div class="row justify-content-center mt-3">
        <div class="col-md-9">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>顧客名</th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    営業担当者
                                </div>
                                <div class="dropdown-menu">
                                    <a href="{{ route('dashboard.customers.index', ['search' => $search, 'sort' => $sort]) }}"
                                        class="dropdown-item">全て表示</a>
                                    <a href="{{ route('dashboard.customers.index', ['seller_sort' => '-', 'search' => $search, 'sort' => $sort]) }}"
                                        class="dropdown-item">-</a>
                                    @foreach ($sellers as $seller)
                                        <a href="{{ route('dashboard.customers.index', ['seller_sort' => $seller->id, 'search' => $search, 'sort' => $sort]) }}"
                                            class="dropdown-item">{{ $seller->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    ステータス
                                </div>
                                <div class="dropdown-menu">
                                    <a href="{{ route('dashboard.customers.index', ['search' => $search, 'seller_sort' => $seller_sort]) }}"
                                        class="dropdown-item">全て表示</a>
                                    @foreach ($sort_query as $key => $val)
                                        <a href="{{ route('dashboard.customers.index', ['sort' => $val, 'search' => $search, 'seller_sort' => $seller_sort]) }}"
                                            class="dropdown-item">{{ $val }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->getSellerNameInCharge() }}</td>
                            @if ($customer->appointments->isNotEmpty())
                                <td>
                                    <span class="{{ $customer->appointments->last()->statusIs()[0] }}">
                                        {{ $customer->appointments->last()->statusIs()[1] }}</span>
                                </td>
                            @else
                                <td></td>
                            @endif
                            <td>
                                <a href="{{ route('dashboard.customers.show', compact('customer')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">{{ $customers->appends(request()->query())->links() }}</div>
        </div>
    </div>
@endsection
