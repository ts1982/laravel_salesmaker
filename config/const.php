<?php

use Illuminate\Support\Str;

return[
    // 管理画面用のクッキー名称
    'session_cookie_admin' => env('SESSION_COOKIE_ADMIN', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
]

?>
