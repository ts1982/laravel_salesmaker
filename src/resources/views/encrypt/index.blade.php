<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Encrypt Test</title>
</head>
<body>
{{--@if (auth('admins')->check())--}}
    <form action="{{ route('encrypt') }}" method="post">
        @csrf
        <textarea name="text"></textarea>
        <input type="submit">
    </form>
    <form action="{{ route('decrypt') }}" method="post">
        @csrf
        <textarea name="text"></textarea>
        <input type="submit">
    </form>
    <p>{{ session('encrypt') ?? '' }}</p>
    <p>{{ session('decrypt') ?? '' }}</p>
{{--@endif--}}
</body>
</html>
