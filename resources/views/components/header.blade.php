<nav class="header navbar navbar-expand-md navbar-light shadow-sm fixed-top">
    <div class="d-flex align-items-center mx-3">
        <a class="navbar-brand mr-4" href="{{ url('/') }}">
            <span class="h2">SalesMaker</span>
        </a>
        <form class="d-flex mr-auto header-search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-success" type="submit">Search</button>
        </form>
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
                <h4 class="mt-3">メニュー</h4>
                <ul class="navbar-nav mr-auto">
                    <li>
                        @if (App\User::roleIs('seller'))
                            <a href="{{ route('appointments.index') }}">アポイント一覧</a>
                        @elseif (App\User::roleIs('appointer'))
                            <a href="{{ route('appointments.index') }}">アポイント登録</a>
                        @endif
                    </li>
                    <li>
                        @if (App\User::roleIs('seller'))
                            <a href="{{ route('users.calendar') }}">営業予定</a>
                        @elseif (App\User::roleIs('appointer'))
                            <a href="{{ route('users.calendar') }}">マイアポイント</a>
                        @endif
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}">顧客一覧</a>
                    </li>
                </ul>
            @endauth
        </div>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
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
