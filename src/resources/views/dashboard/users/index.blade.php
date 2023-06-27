@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">{{ $role }}一覧</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        @if ($role === '営業')
                            <th>開始日</th>
                            <th>終了日</th>
                        @endif
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr @if ($user->join_flag === 1) class="join" @endif>
                            <td class="text-center">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            @if ($role === '営業')
                                <td>{{ $user->start }}</td>
                                <td>{{ $user->end }}</td>
                            @endif
                            <td>
                                @if ($user->role === 'seller')
                                    <a href="{{ route('dashboard.users.sellers_record', compact('user')) }}">成績</a>
                                @elseif ($user->role === 'appointer')
                                    <a href="{{ route('dashboard.users.appointers_record', compact('user')) }}">成績</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.users.edit', compact('user')) }}">編集</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
