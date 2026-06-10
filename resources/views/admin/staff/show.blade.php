<x-layouts.admin.app :title="$staff->name ?: $staff->email">
    <div class="flex flex-col gap-6 max-w-2xl">
        <!-- Back Link -->
        <div>
            <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition" wire:navigate>
                <flux:icon.arrow-left class="h-4 w-4" />
                {{ __('Back to Staff') }}
            </a>
        </div>

        <!-- Profile Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-700 text-lg font-semibold text-zinc-600 dark:text-zinc-300">
                    @if ($staff->avatar)
                        <img src="{{ $staff->avatar }}" alt="{{ $staff->name }}" class="h-16 w-16 rounded-full object-cover" />
                    @else
                        {{ $staff->name ? $staff->initials() : '?' }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ $staff->name ?: '—' }}
                    </h1>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $staff->email }}</p>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ __('Staff') }}
                        </span>

                        @if (! $staff->invitation_accepted_at)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                {{ __('Pending Invitation') }}
                            </span>
                        @elseif ($staff->is_active)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                {{ __('Active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                {{ __('Inactive') }}
                            </span>
                        @endif
                    </div>
                </div>

                @if ($staff->invitation_accepted_at)
                    <form method="POST" action="{{ route('admin.staff.toggle', $staff) }}">
                        @csrf
                        @method('PATCH')
                        <flux:button
                            size="sm"
                            :variant="$staff->is_active ? 'danger' : 'primary'"
                            type="submit"
                        >
                            {{ $staff->is_active ? __('Deactivate') : __('Activate') }}
                        </flux:button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Account Details') }}</h2>
            <dl class="space-y-4">
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Joined') }}</dt>
                    <dd class="text-zinc-900 dark:text-zinc-100">{{ $staff->created_at->format('M d, Y') }}</dd>
                </div>
                @if ($staff->invitation_accepted_at)
                    <div class="flex justify-between text-sm">
                        <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Invitation Accepted') }}</dt>
                        <dd class="text-zinc-900 dark:text-zinc-100">{{ $staff->invitation_accepted_at->format('M d, Y') }}</dd>
                    </div>
                @endif
                @if ($staff->inviter)
                    <div class="flex justify-between text-sm">
                        <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Invited By') }}</dt>
                        <dd class="text-zinc-900 dark:text-zinc-100">{{ $staff->inviter->name }}</dd>
                    </div>
                @endif
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Email Verified') }}</dt>
                    <dd class="text-zinc-900 dark:text-zinc-100">
                        {{ $staff->email_verified_at ? $staff->email_verified_at->format('M d, Y') : '—' }}
                    </dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Google Login') }}</dt>
                    <dd class="text-zinc-900 dark:text-zinc-100">
                        {{ $staff->google_id ? __('Connected') : __('Not connected') }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Danger Zone -->
        <div class="rounded-xl border border-red-200 dark:border-red-900/50 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-2">{{ __('Danger Zone') }}</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ __('Once deleted, this account cannot be recovered.') }}</p>

            <div x-data="{ open: false }">
                <flux:button variant="danger" size="sm" @click="open = true">
                    {{ __('Delete Staff Member') }}
                </flux:button>

                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="open = false">
                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                    <div class="relative w-full max-w-md rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2">{{ __('Confirm Deletion') }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
                            {{ __('Are you sure you want to delete') }} <strong>{{ $staff->name ?: $staff->email }}</strong>? {{ __('This cannot be undone.') }}
                        </p>
                        <div class="flex justify-end gap-3">
                            <flux:button variant="ghost" @click="open = false">{{ __('Cancel') }}</flux:button>
                            <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}">
                                @csrf
                                @method('DELETE')
                                <flux:button variant="danger" type="submit">{{ __('Delete') }}</flux:button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin.app>
