@extends('layouts.app')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <form action="{{ route('customers.index') }}" method="get" class="d-flex row justify-content-center">
        <div class="d-flex col-md-4">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="ID,顧客名">
            <button type="submit" class="btn btn-success w-25">検索</button>
        </div>
    </form>
    <div class="row justify-content-center mt-3">
        <div class="col-sm-7">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>顧客名</th>
                        <th>
                            <div class="dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    ステータス
                                </div>
                                <div class="dropdown-menu">
                                    <a href="{{ route('customers.index', ['search' => $search]) }}"
                                        class="dropdown-item">全て表示</a>
                                    @foreach ($sort_query as $key => $val)
                                        <a href="{{ route('customers.index', ['sort' => $val, 'search' => $search]) }}"
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
                            @if ($customer->appointments->isNotEmpty())
                                <td class="status-color{{ $customer->appointments->last()->statusIs()[0] }}">
                                    {{ $customer->appointments->last()->statusIs()[1] }}</td>
                            @else
                                <td></td>
                            @endif
                            <td>
                                <a href="{{ route('customers.show', compact('customer')) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">{{ $customers->appends(request()->query())->links() }}</div>
        </div>
    </div>
@endsection
