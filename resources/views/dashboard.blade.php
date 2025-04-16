<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="w-full">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if(auth()->user()->role === 'admin')
                {{ __("Welcome, Admin! You have full access.") }}      
            @elseif(auth()->user()->role === 'superadmin')
                <livewire:superadmin.dashboard />
            @else
                {{ __("Welcome, User! You have limited access.") }}
            @endif
        </div>
    </div>
</div>

</x-app-layout>
