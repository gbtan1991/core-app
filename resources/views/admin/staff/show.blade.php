<x-layouts::admin.app :title="$staff->name ?: $staff->email">
    <x-slot name="header">Staff Detail</x-slot>

    <div class="mx-auto max-w-2xl space-y-6">

        {{-- Back link --}}
        <a
            href="{{ route('admin.staff.index') }}"
            class="inline-flex items-center gap-1 text-sm text-gray-500 transition-colors hover:text-gray-700"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Staff
        </a>

        {{-- Profile card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-start gap-5">
                {{-- Avatar --}}
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold uppercase text-indigo-600">
                    @if ($staff->avatar)
                        <img src="{{ $staff->avatar }}" alt="{{ $staff->name }}" class="h-16 w-16 rounded-full object-cover" />
                    @else
                        {{ $staff->name ? $staff->initials() : '?' }}
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold text-gray-900">{{ $staff->name ?: '—' }}</h2>
                    <p class="text-sm text-gray-500">{{ $staff->email }}</p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700">
                            Staff
                        </span>

                        @if (! $staff->invitation_accepted_at)
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                                Pending Invitation
                            </span>
                        @elseif ($staff->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                @if ($staff->invitation_accepted_at)
                    <form method="POST" action="{{ route('admin.staff.toggle', $staff) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors
                                {{ $staff->is_active
                                    ? 'bg-yellow-600 hover:bg-yellow-700'
                                    : 'bg-green-600 hover:bg-green-700' }}"
                        >
                            {{ $staff->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Account details --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Account Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Joined</dt>
                    <dd class="font-medium text-gray-900">{{ $staff->created_at->format('M d, Y') }}</dd>
                </div>
                @if ($staff->invitation_accepted_at)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Invitation accepted</dt>
                        <dd class="font-medium text-gray-900">{{ $staff->invitation_accepted_at->format('M d, Y') }}</dd>
                    </div>
                @endif
                @if ($staff->inviter)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Invited by</dt>
                        <dd class="font-medium text-gray-900">{{ $staff->inviter->name }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email verified</dt>
                    <dd class="font-medium text-gray-900">
                        {{ $staff->email_verified_at ? $staff->email_verified_at->format('M d, Y') : '—' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Google login</dt>
                    <dd class="font-medium text-gray-900">
                        {{ $staff->google_id ? 'Connected' : 'Not connected' }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Danger zone --}}
        <div class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-red-700">Danger Zone</h3>
            <p class="mb-4 text-sm text-gray-500">Once deleted, this account cannot be recovered.</p>

            <div x-data="{ open: false }">
                <button
                    @click="open = true"
                    class="rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-600 transition-colors hover:bg-red-50"
                >
                    Delete Staff Member
                </button>

                <div
                    x-show="open"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @keydown.escape.window="open = false"
                >
                    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
                    <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                        <h3 class="text-base font-semibold text-gray-900">Delete staff member?</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Are you sure you want to permanently delete
                            <strong class="text-gray-900">{{ $staff->name ?: $staff->email }}</strong>?
                        </p>
                        <div class="mt-5 flex justify-end gap-3">
                            <button
                                @click="open = false"
                                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700"
                                >
                                    Yes, delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts::admin.app>
