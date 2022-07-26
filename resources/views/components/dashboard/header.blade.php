<nav class="header navbar navbar-expand-md navbar-dark shadow-sm fixed-top">
    <div class="d-flex align-items-center mx-3">
        <a class="navbar-brand mr-4" href="{{ url('/dashboard') }}">
            <span class="h2">SalesMaker</span>
        </a>
        {{-- <form class="d-flex mr-auto header-search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-success" type="submit">Search</button>
        </form> --}}
    </div>

    <div class="d-flex ml-auto">
        <button class="navbar-toggler ml-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        @auth
            <div class="sidebar-list">
                <h3 class="mt-4 mb-3">管理メニュー</h3>
                <h4>一覧メニュー</h4>
                <ul>
                    <li>
                        <a href="{{ route('dashboard.users.sellers_index') }}">営業</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.users.appointers_index') }}">アポインター</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.customers.index') }}">顧客</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.appointments.index') }}">アポイント</a>
                    </li>
                </ul>
                <h4>その他</h4>
                <ul>
                    <li>
                        インセンティブ
                    </li>
                </ul>
            @endauth
            @guest
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.login') }}">{{ __('ログイン') }}</a>
                    </li>
                    {{-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('ユーザー登録') }}</a>
                        </li>
                    @endif --}}
                </ul>
            @else
            </div>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        {{-- <a href="{{ route('users.edit_password') }}" class="dropdown-item mb-3">パスワード変更</a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            ログアウト
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
