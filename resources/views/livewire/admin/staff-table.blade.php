<div>
    {{-- Flash message (Livewire-scoped) --}}
    @if (session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-4 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
        >
            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

        @if ($staff->isEmpty())
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                    <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900">No staff members yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by inviting your first staff member.</p>
                <a
                    href="{{ route('admin.staff.invite') }}"
                    class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Invite Staff
                </a>
            </div>

        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Staff Member
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Date Added
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($staff as $member)
                            <tr class="transition-colors hover:bg-gray-50">

                                {{-- Avatar + name + email --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold uppercase text-indigo-600">
                                            @if ($member->avatar)
                                                <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="h-10 w-10 rounded-full object-cover" />
                                            @else
                                                {{ $member->name ? $member->initials() : '?' }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-gray-900">
                                                {{ $member->name ?: '—' }}
                                            </p>
                                            <p class="truncate text-sm text-gray-500">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status badge --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if (! $member->invitation_accepted_at)
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                                            Pending
                                        </span>
                                    @elseif ($member->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Date added --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $member->created_at->format('M d, Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        x-data="{ confirmDelete: false, confirmToggle: false }"
                                        class="flex items-center justify-end gap-2"
                                    >
                                        {{-- View detail link --}}
                                        <a
                                            href="{{ route('admin.staff.show', $member) }}"
                                            class="rounded px-2.5 py-1.5 text-xs font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
                                        >
                                            View
                                        </a>

                                        {{-- Toggle active (only after invitation accepted) --}}
                                        @if ($member->invitation_accepted_at)
                                            <button
                                                @click="confirmToggle = true"
                                                class="rounded px-2.5 py-1.5 text-xs font-medium transition-colors
                                                    {{ $member->is_active
                                                        ? 'text-yellow-700 hover:bg-yellow-50'
                                                        : 'text-green-700 hover:bg-green-50' }}"
                                            >
                                                {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>

                                            {{-- Toggle confirm dialog --}}
                                            <div
                                                x-show="confirmToggle"
                                                x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                @keydown.escape.window="confirmToggle = false"
                                            >
                                                <div class="absolute inset-0 bg-black/40" @click="confirmToggle = false"></div>
                                                <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                                                    <h3 class="text-base font-semibold text-gray-900">
                                                        {{ $member->is_active ? 'Deactivate' : 'Activate' }} staff member?
                                                    </h3>
                                                    <p class="mt-2 text-sm text-gray-500">
                                                        @if ($member->is_active)
                                                            <strong>{{ $member->name ?: $member->email }}</strong> will no longer be able to log in.
                                                        @else
                                                            <strong>{{ $member->name ?: $member->email }}</strong> will regain access to the app.
                                                        @endif
                                                    </p>
                                                    <div class="mt-5 flex justify-end gap-3">
                                                        <button
                                                            @click="confirmToggle = false"
                                                            class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button
                                                            wire:click="toggle({{ $member->id }})"
                                                            @click="confirmToggle = false"
                                                            class="rounded-lg px-4 py-2 text-sm font-medium text-white transition-colors
                                                                {{ $member->is_active
                                                                    ? 'bg-yellow-600 hover:bg-yellow-700'
                                                                    : 'bg-green-600 hover:bg-green-700' }}"
                                                        >
                                                            {{ $member->is_active ? 'Yes, deactivate' : 'Yes, activate' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Delete --}}
                                        <button
                                            @click="confirmDelete = true"
                                            class="rounded px-2.5 py-1.5 text-xs font-medium text-red-600 transition-colors hover:bg-red-50 hover:text-red-700"
                                        >
                                            Delete
                                        </button>

                                        {{-- Delete confirm dialog --}}
                                        <div
                                            x-show="confirmDelete"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            @keydown.escape.window="confirmDelete = false"
                                        >
                                            <div class="absolute inset-0 bg-black/40" @click="confirmDelete = false"></div>
                                            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                                                <div class="mb-4 flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                                                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base font-semibold text-gray-900">Delete staff member?</h3>
                                                </div>
                                                <p class="text-sm text-gray-500">
                                                    Are you sure you want to delete
                                                    <strong class="text-gray-900">{{ $member->name ?: $member->email }}</strong>?
                                                    This action cannot be undone.
                                                </p>
                                                <div class="mt-5 flex justify-end gap-3">
                                                    <button
                                                        @click="confirmDelete = false"
                                                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button
                                                        wire:click="delete({{ $member->id }})"
                                                        @click="confirmDelete = false"
                                                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
                                                    >
                                                        Yes, delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
