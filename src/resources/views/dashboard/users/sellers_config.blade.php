@extends('layouts.dashboard')

@section('content')
    <div id="user_config">
        @if (session('warning'))
            <div class="alert alert-danger">{{ session('warning') }}</div>
        @endif
        <h1 class="text-center">営業設定</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>氏名</th>
                            <th>開始日</th>
                            <th>終了日</th>
                            <th>ログイン</th>
                            <th>担当顧客</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $user)
                            <tr>
                                <td @if ($user->join_flag === 1) class="join" @endif>{{ $user->id }}</td>
                                <td @if ($user->join_flag === 1) class="join" @endif>{{ $user->name }}</td>
                                <td>
                                    <form action="{{ route('dashboard.users.term_update', compact('user')) }}" method="post"
                                        id="term_update{{ $user->id }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="end" value="{{ $user->end }}">
                                        <input type="date" name="start" value="{{ $user->start }}" id="form-start"
                                            class="form-control" onchange='this.form.submit();'>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('dashboard.users.term_update', compact('user')) }}"
                                        method="post" id="term_update{{ $user->id }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="start" value="{{ $user->start }}">
                                        <input type="date" name="end" value="{{ $user->end }}" id="form-start"
                                            class="form-control" onchange='this.form.submit();'>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('dashboard.users.join', compact('user')) }}" method="post">
                                        @csrf
                                        @method('put')
                                        @if ($user->join_flag === 1)
                                            <button type="submit" class="btn btn-success btn-sm">承認する</button>
                                        @else
                                            @if ($user->appointmentIsNotVisitedExists())
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#Seller{{ $user->id }}JoinModal">承認しない</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="Seller{{ $user->id }}JoinModal"
                                                    tabindex="-1"
                                                    aria-labelledby="Seller{{ $user->id }}JoinModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="Seller{{ $user->id }}JoinModalLabel">承認設定</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                未訪問のアポイントがありますがよろしいですか？
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">戻る</button>
                                                                <button type="submit" class="btn btn-danger">実行</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-sm">承認しない</button>
                                            @endif
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <!-- Button trigger modal -->
                                    @if ($user->customers->isNotEmpty())
                                        <button class="btn btn-danger btn-sm ml-3" type="button" data-toggle="modal"
                                            data-target="#Seller{{ $user->id }}Modal">解除</button>
                                    @else
                                        <button class="btn btn-success btn-sm ml-3" type="button">解除済</button>
                                    @endif
                                    <!-- Modal -->
                                    <div class="modal fade" id="Seller{{ $user->id }}Modal" tabindex="-1"
                                        aria-labelledby="Seller{{ $user->id }}ModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="Seller{{ $user->id }}ModalLabel">担当顧客解除
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    担当顧客を解除します。<br>この操作は元に戻せませんが、よろしいですか？
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">戻る</button>
                                                    <a href="{{ route('dashboard.users.reset_customers', compact('user')) }}"
                                                        class="btn btn-danger">実行</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
