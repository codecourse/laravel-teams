@props(['type' => 'success'])

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    x-init="setTimeout(() => show = false, 3000)"
    class="fixed bottom-4 right-4 z-50 rounded-lg p-4 {{ $type === 'success' ? 'bg-green-500' : 'bg-red-500' }} text-white shadow-lg"
>
    {{ $slot }}
</div>
