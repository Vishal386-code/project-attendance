<div>
    <h1 class="text-xl font-bold mb-4">Employee Attendance Tracker</h1>

    <!-- Success Message -->
    @if(session()->has('message'))
        <div class="text-green-600 mb-2">{{ session('message') }}</div>
    @endif

    <!-- Dropdown to select a user -->
    <label class="block mb-1 font-semibold">Select User</label>
    <select wire:model="sendableId" class="border px-2 py-1 mb-4">
        <option value="">Select a User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <!-- Submit Button -->
    <button wire:click="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>

    <!-- Divider -->
    <hr class="my-6">

    <!-- Display Messages -->
    <h2 class="font-semibold text-lg mb-2">Messages:</h2>
    <div class="space-y-2">
        @forelse($messages as $message)
            <p class="border p-2 rounded">{{ $message->body }}</p>
        @empty
            <p>No messages found.</p>
        @endforelse
    </div>

    <!-- Total Worked Time -->
    <div class="mt-6 p-4 bg-gray-100 rounded">
        <strong>Total Working Time:</strong> {{ $workedHours }} hour(s) {{ $workedMins }} minute(s)
    </div>
</div>
