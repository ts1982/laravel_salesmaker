@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">アポイント詳細</h1>
    <div class="row justify-content-center mt-4">
        <div class="col-md-10 p-0">
            <div class="row mb-3">
                <strong class="col-md-3">日時</strong>
                <div class="col-md-9">
                    <span><a
                            href="{{ route('dashboard.appointments.byday', ['day' => $appointment->day]) }}">{{ date('Y/m/d', strtotime($appointment->day)) }}</a>&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時</span>
                </div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">顧客名</strong>
                <div class="col-md-9">
                    <a
                        href="{{ route('dashboard.customers.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a>
                </div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">住所</strong>
                <div class="col-md-9">{{ $appointment->customer->address }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">電話番号</strong>
                <div class="col-md-9">{{ $appointment->customer->tel }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">アポインター</strong>
                <div class="col-md-9">{{ $appointment->user->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">営業担当者</strong>
                <div class="col-md-9">{{ $appointment->thisSellerHas()->name }}</div>
            </div>
            <div class="row mb-3">
                <strong class="col-md-3">ステータス</strong>
                <div class="col-md-9 status-color">
                    <span class="{{ $appointment->statusIs()[0] }}">{{ $appointment->statusIs()[1] }}</span
                        class="{{ $appointment->statusIs()[0] }}">
                </div>
            </div>
            @if ($appointment->status != 0)
                <div class="row mb-3">
                    <strong class="col-md-3">営業結果報告</strong>
                    <div class="col-md-9">{{ $appointment->report }}</div>
                </div>
            @endif
            <div class="row mb-3">
                <strong class="col-md-3">ヒアリング内容</strong>
                <div class="col-md-9">
                    @foreach ($contents as $content)
                        <div class="mb-4">
                            <div>
                                {{ date("Y/m/d ({$content->appointment->getDayName()}) H:i", strtotime($content->created_at)) }}&emsp;登録者：{{ $content->user->name }}
                            </div>
                            <div>{{ $content->content }}</div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('dashboard.appointments.edit', compact('appointment')) }}"
                    class="btn btn-warning mr-2">編集</a>
                <form action="{{ route('dashboard.appointments.destroy', compact('appointment')) }}" method="post">
                    @csrf
                    @method('delete')
                    <!-- Button trigger modal -->
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#exampleModal"
                        @if ($appointment->status != 0) disabled @endif>削除</button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">削除</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    このアポイントを削除します。この操作は元に戻せませんが、よろしいですか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">戻る</button>
                                    <button type="submit" class="btn btn-danger">実行
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
