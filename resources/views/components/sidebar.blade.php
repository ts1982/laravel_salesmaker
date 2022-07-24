<div class="sidebar container">
    <h3>メニュー</h3>
    <ul>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('users.calendar') }}">マイカレンダー</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('users.calendar') }}">マイアポイント</a>
            @endif
        </li>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('users.seller_record') }}">営業成績</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('users.appointer_record') }}">アポインター成績</a>
            @endif
        </li>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('appointments.index') }}">アポイント一覧</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('appointments.index') }}">アポイント登録</a>
            @endif
        </li>
        <li>
            <a href="{{ route('customers.index') }}">顧客一覧</a>
        </li>
    </ul>
</div>
