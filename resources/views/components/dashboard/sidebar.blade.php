<div class="sidebar container">
    <h2 class="mb-4">管理メニュー</h2>
    <h3>各種一覧</h3>
    <ul>
        <li>
            <a href="{{ route('dashboard.appointments.index') }}">アポイント</a>
        </li>
        <li>
            <a href="{{ route('dashboard.users.sellers_index') }}">営業</a>
        </li>
        <li>
            <a href="{{ route('dashboard.users.appointers_index') }}">アポインター</a>
        </li>
        <li>
            <a href="{{ route('dashboard.customers.index') }}">顧客</a>
        </li>
    </ul>
    <h3>その他</h3>
    <ul>
        <li>
            <a href="#">月別営業成績</a>
        </li>
        <li>
            <a href="#">月別アポインター成績</a>
        </li>
        <li>
            <a href="#">インセンティブ</a>
        </li>
        <li>
            <a href="#">休日設定</a>
        </li>
    </ul>
</div>
