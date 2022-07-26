@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">顧客一覧</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-sm-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @if ($user->appointments->isNotEmpty())
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.show', compact('user')) }}">詳細</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
