<div class="container">
    <h3>AAA</h3>
    <ul>
        <li>
            <a href="{{ route('appointments.index') }}">アポイント</a>
        </li>
        @if (App\User::roleIs('seller'))
            <li>
                <a href="{{ route('users.sells') }}">マイカレンダー</a>
            </li>
        @endif
        <li>
            <a href="#">ccc</a>
        </li>
    </ul>
    <h3>BBB</h3>
    <ul>
        <li>
            <a href="#">ddd</a>
        </li>
        <li>
            <a href="#">eee</a>
        </li>
    </ul>
</div>
