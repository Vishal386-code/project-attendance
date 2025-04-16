{{-- Sidebar --}}
    <div class="w-64 bg-gray-900 text-white">
        <div class="p-6 text-2xl font-bold text-center border-b border-gray-700">
            Super Admin
        </div>
        <nav class="mt-4">
            <ul class="space-y-1">
                <li>
                    <a href="#" wire:navigate
                       class="flex items-center px-6 py-3 text-sm font-medium hover:bg-gray-800 hover:text-indigo-400 transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10h4v11H3zM17 3h4v18h-4zM10 14h4v7h-4z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('super.attendance') }}" wire:navigate
                       class="flex items-center px-6 py-3 text-sm font-medium hover:bg-gray-800 hover:text-indigo-400 transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M4 20h5v-2a4 4 0 013-3.87"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 3.13a4 4 0 110 7.75 4 4 0 010-7.75zM8 3.13a4 4 0 110 7.75 4 4 0 010-7.75z"/>
                        </svg>
                        Attendance Sheets
                    </a>
                </li>
                <li>
                    <a href="#" wire:navigate
                       class="flex items-center px-6 py-3 text-sm font-medium hover:bg-gray-800 hover:text-indigo-400 transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>