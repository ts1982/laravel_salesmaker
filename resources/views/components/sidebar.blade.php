<div class="sidebar container">
    <h3>メニュー</h3>
    <ul>
        <li>
            <a href="{{ route('appointments.index') }}">アポイント一覧</a>
        </li>
        <li>
            @if (App\User::roleIs('seller'))
                <a href="{{ route('users.calendar') }}">営業予定</a>
            @elseif (App\User::roleIs('appointer'))
                <a href="{{ route('users.calendar') }}">取得アポイント一覧</a>
            @endif
        </li>
        <li>
            <a href="{{ route('customers.index') }}">顧客一覧</a>
        </li>
    </ul>
</div>
