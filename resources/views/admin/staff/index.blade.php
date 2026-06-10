<x-layouts::admin.app title="Staff Management">
    <x-slot name="header">Staff Management</x-slot>

    <div class="space-y-4">

        {{-- Page actions bar --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Manage your team members and their access.
            </p>
            <a
                href="{{ route('admin.staff.invite') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Invite Staff
            </a>
        </div>

        {{-- Livewire staff table --}}
        <livewire:admin.staff-table />

    </div>
</x-layouts::admin.app>
