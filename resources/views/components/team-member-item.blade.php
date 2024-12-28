@php use Spatie\Permission\Models\Role; @endphp
<li class="py-4">
    <div class="flex items-center space-x-2">
        <img src="{{ $member->profilePhotoUrl() }}" alt="{{ $member->name }}" class="size-6 rounded-full">
        <div class="text-sm font-semibold text-gray-900 flex-auto">
            {{ $member->name }} ({{ $member->email }})
        </div>

        @canany(['removeTeamMember', 'changeMemberRole'], [$team, $member])
            <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                @can('removeTeamMember', [auth()->user()->currentTeam, $member])
                    <x-dropdown-link>
                        <form action="{{ route('team.members.destroy', [$team, $member]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Remove from team</button>
                        </form>
                    </x-dropdown-link>
                @endcan

                @can('changeMemberRole', [auth()->user()->currentTeam, $member])
                    <x-dropdown-link href="">
                        <button
                            x-on:click.prevent="$dispatch('open-modal', 'change-member-{{ $member->id }}-role')"
                        >{{ __('Change member role') }}</button>
                    </x-dropdown-link>
                @endcan
            </x-slot>
        </x-dropdown>
        @endcanany
    </div>

    <div class="mt-3 text-sm text-gray-500">
        Role: <span class="text-gray-700">{{ $member->roles->pluck('name')->join(',') }}</span>
    </div>

    @can('changeMemberRole', [auth()->user()->currentTeam, $member])
        <x-modal name="change-member-{{ $member->id }}-role" focusable>
            <form method="post" action="{{ route('team.members.update', [$team, $member]) }}" class="p-6">
                @csrf
                @method('PATCH')

                <h2 class="text-lg font-medium text-gray-900">
                    Change role for {{ $member->name }} ({{ $member->email }})
                </h2>

                <div class="mt-6">
                    <x-input-label for="role" value="Role" class="sr-only" />

                    <x-select-input class="w-full" name="role" id="role">
                        @foreach(Role::get() as $role)
                            <option value="{{ $role->name }}" @selected($member->hasRole($role))>{{ $role->name }}</option>
                        @endforeach
                    </x-select-input>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('Change role') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endcan
</li>

