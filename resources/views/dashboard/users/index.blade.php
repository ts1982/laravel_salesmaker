@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">{{ $role }}一覧</h1>
    <div class="row justify-content-center mt-3">
        <div class="col-sm-10">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        {{-- @if ($user->appointments->isNotEmpty()) --}}
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role === 'seller')
                                    <a href="{{ route('dashboard.users.sellers_record', compact('user')) }}">成績</a>
                                @elseif ($user->role === 'appointer')
                                    <a href="{{ route('dashboard.users.appointers_record', compact('user')) }}">成績</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.users.show', compact('user')) }}">詳細</a>
                            </td>
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
