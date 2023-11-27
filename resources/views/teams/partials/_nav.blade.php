<ul class="list-group mb-4">
    <li class="list-group-item">
        <a href="{{ route('teams.show', $team) }}">Dashboard</a>
    </li>

    <li class="list-group-item">
        <a href="{{ route('teams.users.index', $team) }}">Users</a>
    </li>

    @permission('manage team subscription', $team->id)
        <li class="list-group-item">
            <a href="{{ route('teams.subscriptions.index', $team) }}">Subscription</a>
        </li>
    @endpermission
</ul>

<ul class="list-group mb-4">
    @permission('delete team', $team->id)
        <li class="list-group-item">
            <a href="{{ route('teams.delete', $team) }}">Delete team</a>
        </li>
    @endpermission
</ul>
