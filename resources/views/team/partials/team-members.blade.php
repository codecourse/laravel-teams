<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Team members') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            These are the members of your team
        </p>
    </header>

    <div class="mt-6">
        <ul class="divide-y divide-gray-100">
            @foreach($team->members as $member)
                <x-team-member-item :team="$team" :member="$member" />
            @endforeach
        </ul>

        @can('inviteToTeam', $team)
            <ul class="divide-y divide-gray-100">
                @foreach($team->invites as $invite)
                    <x-team-invite-item :invite="$invite" />
                @endforeach
            </ul>
        @endcan
    </div>

    @can('inviteToTeam', $team)
        <form method="post" action="{{ route('team.invites.store', $team) }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Invite') }}</x-primary-button>

                @if (session('status') === 'team-invited')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Invite sent.') }}</p>
                @endif
            </div>
        </form>
    @endcan
</section>
