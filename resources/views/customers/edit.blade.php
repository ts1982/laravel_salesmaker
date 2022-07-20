@extends('layouts.app')

@section('content')
    <h1 class="text-center">顧客情報編集</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-sm-7 p-0">
            <form action="{{ route('customers.update', compact('customer')) }}" method="post">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="form-name" class="col-form-label col-md-3">顧客名</label>
                    <div class="col-md-9">
                        <input type="name" name="name" id="name" value="{{ $customer->name }}"
                            class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="form-address" class="col-form-label col-md-3">住所</label>
                    <div class="col-md-9">
                        <input type="address" name="address" id="address" value="{{ $customer->address }}"
                            class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="form-tel" class="col-form-label col-md-3">電話番号</label>
                    <div class="col-md-9">
                        <input type="tel" name="tel" id="tel" value="{{ $customer->tel }}"
                            class="form-control">
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="submit" class="btn btn-success" value="更新">
                </div>
            </form>
        </div>
    </div>
@endsection
