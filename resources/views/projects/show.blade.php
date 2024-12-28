<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->title }}
            </h2>
            <div class="flex gap-4">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline"
                        x-data="{ showConfirm: false }"
                        @submit.prevent="if (showConfirm || confirm('Are you sure you want to delete this project?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <x-notification>
                    {{ session('success') }}
                </x-notification>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Description') }}</h3>
                        <p class="text-gray-600">{{ $project->description }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Progress') }}</h3>
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $project->completion_percentage }}%"></div>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ number_format($project->completion_percentage, 0) }}%</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Team') }}</h3>
                        <p class="text-gray-600">{{ $project->team->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
