<div class="sidebar container sidebar-offset">
    <h3 class="mb-4">メニュー</h3>
    <ul>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('users.seller_calendar') }}"><i class="far fa-calendar mr-1"></i>マイカレンダー</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('users.appointer_calendar') }}"><i class="far fa-calendar mr-1"></i>マイアポイント</a>
            @endif
        </li>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('users.seller_record') }}"><i class="fas fa-trophy mr-1"></i>マイレコード</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('users.appointer_record') }}"><i class="fas fa-trophy mr-1"></i>マイレコード</a>
            @endif
        </li>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('appointments.index') }}"><i class="fas fa-calendar-alt mr-1"></i>アポイント枠</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('appointments.index') }}"><i class="fas fa-calendar-alt mr-1"></i>アポイント登録</a>
            @endif
        </li>
        <li>
            <a href="{{ route('customers.index') }}"><i class="far fa-address-book mr-1"></i>顧客一覧</a>
        </li>
    </ul>
</div>
