@extends('layouts.dashboard')

@section('content')
    <div id="user_config">
        <h1 class="text-center">アポインター設定</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>氏名</th>
                            <th>ログイン</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointers as $user)
                            <tr>
                                <td @if ($user->join_flag === 1) class="join" @endif>{{ $user->id }}</td>
                                <td @if ($user->join_flag === 1) class="join" @endif>{{ $user->name }}</td>
                                <td>
                                    <form action="{{ route('dashboard.users.join', compact('user')) }}" method="post">
                                        @csrf
                                        @method('put')
                                        @if ($user->join_flag === 1)
                                            <button type="submit" class="btn btn-success btn-sm">承認する</button>
                                        @else
                                            <button type="submit" class="btn btn-danger btn-sm">承認しない</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
