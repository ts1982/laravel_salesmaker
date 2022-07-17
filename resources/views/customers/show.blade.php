@extends('layouts.app')

@section('content')
    <h1 class="mb-5 text-center">顧客詳細情報</h1>
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>顧客名</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->name }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>住所</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->address }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>電話番号</strong>
                </div>
                <div class="col-md-8">
                    <span>{{ $customer->tel }}</span>
                </div>
            </div>
        </div>
    </div>
    <h3 class="text-center mt-5">アポイント履歴</h3>
    </div>
    </div>
@endsection
