<div>
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Employee Attendance Tracker</h1>

    <div class="flex flex-wrap gap-4 items-end mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Month</label>
            <input type="number" wire:model="month" min="1" max="12"
                   class="mt-1 block w-24 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Year</label>
            <input type="number" wire:model="year"
                   class="mt-1 block w-24 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
       
        <div>
            <button wire:click="submit"
                    class="mt-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">Send
            </button>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow">
        <table class="min-w-full border border-gray-300 text-sm text-center">
            <thead class="bg-gray-100 text-gray-800">
            <tr>
                <th class="border px-3 py-2">Date</th>
                <th class="border px-3 py-2">Day</th>
                @foreach($users as $user)
                    <th class="border px-3 py-2 whitespace-nowrap">
                        {{ $user->name }}
                        <div class="text-xs font-medium text-gray-600">PL: {{ $plCount[$user->id] ?? 0 }}</div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($matrix as $row)
                <tr class="{{ $row['day'] === 'Sunday' ? 'bg-blue-100' : ($row['day'] === 'Saturday' ? 'bg-blue-50' : 'bg-white') }}">
                    <td class="border px-3 py-2 font-medium text-gray-700">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                    <td class="border px-3 py-2 text-gray-600">{{ $row['day'] }}</td>
                    @foreach($users as $user)
                        @php
                            $status = $row['status_' . $user->id];
                            $extra = $row['extra_' . $user->id] ?? '';
                            $color = match($status) {
                                'HD' => 'bg-yellow-200 text-yellow-900 font-semibold',
                                'SL' => 'bg-orange-200 text-orange-900 font-semibold',
                                'Leave' => 'bg-red-300 text-white font-semibold',
                                'PL' => 'bg-green-200 text-green-900 font-semibold',
                                'Present' => 'bg-green-100 text-green-800 font-semibold',
                                default => ''
                            };
                        @endphp
                        <td class="border px-3 py-2 {{ $color }}">
                            <div>{{ $status }}</div>
                            @if($extra)
                                <div class="text-xs font-medium text-blue-700 mt-1">{{ $extra }}</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-white p-4 rounded shadow border max-w-md">
        <h3 class="text-lg font-semibold mb-2 text-gray-800">Working Hours</h3>
        <p class="text-gray-700">Total Worked: <strong>{{ $workedHours }} hours {{ $workedMins }} minutes</strong></p>
        <p class="text-gray-700 mt-4 font-semibold">Total Extra Hours Across All Users: <strong>{{ $totalExtraHours }} hours {{ $totalExtraMins }} minutes</strong></p>
    </div>
</div>
