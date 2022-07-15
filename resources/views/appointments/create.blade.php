@extends('layouts.app')

@section('content')
    <h1 class="text-center">アポイント作成</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('appointments.store') }}" method="post">
                @csrf
                <input type="hidden" name="day" value="{{ $day }}">
                <input type="hidden" name="hour" value="{{ $hour }}">
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">ヒアリング内容</label>
                    <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="5"></textarea>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success" type="button">登録</button>
                </div>
            </form>
            <a href="/appointments">アポイント一覧に戻る</a>
        </div>
    </div>
@endsection
