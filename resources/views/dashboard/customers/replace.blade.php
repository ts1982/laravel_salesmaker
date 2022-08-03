@extends('layouts.dashboard')

@section('content')
    @if (session('warning'))
        <div class="alert alert-danger">{{ session('warning') }}</div>
    @endif
    <h1 class="text-center">顧客振分</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-md-9 p-0">
            <form action="{{ route('dashboard.customers.replace_store') }}" method="post" name="replace">
                @csrf
                @method('put')
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label for="sellers-select">振分先選択</label>
                        <select name="sellers[]" id="sellers-select" class="form-control multiple-select"
                            multiple="multiple">
                            @foreach ($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mt-3">
                        <small>※選択した顧客が振分先の営業担当者に均等に振分けられます。</small><br>
                        <small>（振分先はCtrlキーを押しながら複数選択が可能です。）</small><br>
                        <div class="row mt-4">
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="all" id="all-select" class="form-check-input">
                                    <label for="all-select" class="form-check-label"><small>紐付けられていない顧客を一括選択</small></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">振分実行</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table text-center mt-3">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" class="mr-1"> 全選択</th>
                            <th>ID</th>
                            <th>顧客名</th>
                            <th>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        営業担当者
                                    </div>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('dashboard.customers.replace', ['sort' => 'all']) }}"
                                            class="dropdown-item">全て表示</a>
                                        <a href="{{ route('dashboard.customers.replace') }}" class="dropdown-item">-</a>
                                        @foreach ($sellers as $seller)
                                            <a href="{{ route('dashboard.customers.replace', ['sort' => $seller->id]) }}"
                                                class="dropdown-item">{{ $seller->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <input type="checkbox" name="customers[]" value="{{ $customer->id }}" class="checkbox">
                                </td>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>
                                    {{ $customer->getSellerNameInCharge() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            <div class="d-flex justify-content-center">{{ $customers->appends(request()->query())->links() }}</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkall = document.getElementById('checkall');
            const checks = document.getElementsByName('customers[]');

            checkall.addEventListener('change', function() {
                for (i = 0; i < checks.length; i++) {
                    if (checkall.checked) {
                        checks[i].checked = true;
                    } else {
                        checks[i].checked = false;
                    }
                }
            });
        })
    </script>
@endsection
