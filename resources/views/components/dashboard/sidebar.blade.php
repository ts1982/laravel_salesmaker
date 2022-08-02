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
    <h3>月別成績</h3>
    <ul>
        <li>
            <a href="{{ route('dashboard.records.sellers') }}">営業</a>
        </li>
        <li>
            <a href="{{ route('dashboard.records.appointers') }}">アポインター</a>
        </li>
        <li>
            <a href="{{ route('dashboard.records.incentive') }}">インセンティブ</a>
        </li>
    </ul>
    <h3>設定</h3>
    <ul>
        <li>
            <a href="{{ route('dashboard.appointments.holiday') }}">休日設定</a>
        </li>
        <li>
            <a href="{{ route('dashboard.customers.replace') }}">顧客振替</a>
        </li>
    </ul>
</div>
