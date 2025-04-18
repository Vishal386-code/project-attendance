<div class="w-full mx-auto p-4">
    <!-- Form Section -->
    <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}" class="space-y-4">
        @if($editId)
            <!-- Edit Form Fields -->
            <div>
                <label class="block font-semibold">Occasion</label>
                <input type="text" wire:model="editOccasion" class="w-full border p-2 rounded" />
                @error('editOccasion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold">Date</label>
                <input type="date" wire:model="editDate" class="w-full border p-2 rounded" />
                @error('editDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update Holiday</button>
                <button type="button" wire:click="cancelEdit" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
            </div>
        @else
            <!-- Original Form Fields -->
            <div>
                <label class="block font-semibold">Occasion</label>
                <input type="text" wire:model="occasion" class="w-full border p-2 rounded" />
                @error('occasion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold">Date</label>
                <input type="date" wire:model="date" class="w-full border p-2 rounded" />
                @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Holiday</button>
        @endif
    </form>

    @if (session()->has('success'))
        <div class="text-green-600 mt-4">{{ session('success') }}</div>
    @endif

    <hr class="my-6" />

    <!-- Holidays Table -->
    <table class="w-full table-auto border-collapse mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">#</th>
                <th class="border px-4 py-2 text-left">Occasion</th>
                <th class="border px-4 py-2 text-left">Date</th>
                <th class="border px-4 py-2 text-left">Day</th>
                <th class="border px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holidays as $index => $holiday)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $holiday->occasion }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($holiday->date)->format('d M Y') }}</td>
                    <td class="border px-4 py-2">{{ $holiday->day }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $holiday->id }})" class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                        <button wire:click="delete({{ $holiday->id }})" class="text-red-600 hover:text-red-800" 
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border px-4 py-2 text-center">No holidays found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
