@extends('layouts.dashboard')

@section('content')
    <h1 class="text-center">アポイント編集</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 p-0">
            <form action="{{ route('dashboard.appointments.update', compact('appointment')) }}" method="post" class="mt-3">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <strong class="col-md-3">日時</strong>
                    <div class="col-md-5">
                        <span>{{ date('Y/m/d', strtotime($appointment->day)) }}&nbsp;({{ $appointment->getDayName() }})&emsp;{{ $appointment->hour }}時</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">顧客名</strong>
                    <div class="col-md-9">{{ $appointment->customer->name }}</div>
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
                    <div class="col-md-9 p-0">
                        <select name="appointer" class="custom-select custom-select-sm">
                            @foreach ($appointers as $appointer)
                                @if ($appointer->id === $appointment->user_id)
                                    <option value="{{ $appointer->id }}" selected>{{ $appointer->name }}</option>
                                @else
                                    <option value="{{ $appointer->id }}">{{ $appointer->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">営業担当者</strong>
                    <div class="col-md-9 p-0">
                        <select name="seller" class="custom-select custom-select-sm">
                            @foreach ($sellers as $seller)
                                @if ($seller->id === $appointment->seller_id)
                                    <option value="{{ $seller->id }}" selected>{{ $seller->name }}</option>
                                @else
                                    <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <strong class="col-md-3">ステータス</strong>
                    <div class="col-md-9 p-0">
                        <select name="status" class="custom-select custom-select-sm">
                            @foreach ($status_list as $key => $value)
                                @if ($key === $appointment->status)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
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
                        @foreach ($appointment->contents as $content)
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
                    <input type="submit" value="完了" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
