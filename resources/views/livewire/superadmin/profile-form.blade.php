<div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">User Management</h2>
    
    <!-- Add User Button -->
    <button wire:click="create" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Add New User
    </button>

    <!-- Users Table -->
    <div class="mt-4">
        <h3 class="text-lg font-semibold mb-2">User List</h3>
        
        @if(session('message'))
            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border text-left">SR No</th>
                        <th class="p-2 border text-left">Name</th>
                        <th class="p-2 border text-left">User Email</th>
                        <th class="p-2 border text-left">Designation</th>
                        <th class="p-2 border text-left">Status</th>
                        <th class="p-2 border text-left">Salary</th>
                        <th class="p-2 border text-left">Bank</th>
                        <th class="p-2 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="p-2 border">{{ $user->sr_no }}</td>
                            <td class="p-2 border">{{ $user->name }}</td>
                            <td class="p-2 border">{{ $user->user_email }}</td>
                            <td class="p-2 border">{{ $user->designation }}</td>
                            <td class="p-2 border">{{ $user->status }}</td>
                            <td class="p-2 border">{{ optional($user->salary)->salary }}</td>
                            <td class="p-2 border">{{ optional($user->bankDetail)->bank_name }}</td>
                            <td class="p-2 border">
                                <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:text-blue-800 mr-2">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-800" 
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-2 border text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-bold">{{ $isEdit ? 'Edit' : 'Add' }} User Profile</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-4">
                    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Personal Information -->
                        <div>
                            <label class="block">SR No</label>
                            <input type="text" wire:model="sr_no" class="w-full border px-2 py-1 rounded">
                        </div>
                        
                        <div>
                            <label class="block">Name*</label>
                            <input type="text" wire:model="name" class="w-full border px-2 py-1 rounded">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">Company Email*</label>
                            <input type="email" wire:model="email" class="w-full border px-2 py-1 rounded">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">Password*</label>
                            <input type="password" wire:model="password" class="w-full border px-2 py-1 rounded">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">User Email*</label>
                            <input type="email" wire:model="user_email" class="w-full border px-2 py-1 rounded">
                            @error('user_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">Designation</label>
                            <input type="text" wire:model="designation" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Reference No</label>
                            <input type="text" wire:model="ref_no" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Date Joined</label>
                            <input type="date" wire:model="date_joined" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Status</label>
                            <input type="text" wire:model="status" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">WFH/Hybrid</label>
                            <input type="text" wire:model="wfh_hybrid" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Date Left</label>
                            <input type="date" wire:model="date_left" class="w-full border px-2 py-1 rounded">
                        </div>

                        <!-- Additional Personal Info -->
                        <div>
                            <label class="block">Father's Name</label>
                            <input type="text" wire:model="father_name" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Date of Birth</label>
                            <input type="date" wire:model="dob" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Address</label>
                            <input type="text" wire:model="address" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Contact Number</label>
                            <input type="text" wire:model="contact_number" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">On Role Date</label>
                            <input type="date" wire:model="on_role_date" class="w-full border px-2 py-1 rounded">
                        </div>

                        <!-- Salary Information -->
                        <div>
                            <label class="block">Salary</label>
                            <input type="number" wire:model="salary" step="0.01" class="w-full border px-2 py-1 rounded">
                            @error('salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">Salary On Role</label>
                            <input type="number" wire:model="salary_on_role" step="0.01" class="w-full border px-2 py-1 rounded">
                            @error('salary_on_role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bank Information -->
                        <div>
                            <label class="block">Account Name</label>
                            <input type="text" wire:model="account_name" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Account Number</label>
                            <input type="text" wire:model="account_number" class="w-full border px-2 py-1 rounded">
                            @error('account_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block">IFSC Code</label>
                            <input type="text" wire:model="ifsc" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">Bank Name</label>
                            <input type="text" wire:model="bank_name" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div>
                            <label class="block">UPI ID</label>
                            <input type="text" wire:model="upi_id" class="w-full border px-2 py-1 rounded">
                        </div>

                        <div class="col-span-2 flex justify-end space-x-2 pt-4">
                            <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Reset
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                {{ $isEdit ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>