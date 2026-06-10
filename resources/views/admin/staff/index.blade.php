<x-layouts.admin.app :title="__('Staff Management')">
    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ __('Staff Management') }}</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage your team members and their access.') }}</p>
            </div>
            <a href="{{ route('admin.staff.invite') }}" wire:navigate>
                <flux:button variant="primary" icon="plus">
                    {{ __('Invite Staff') }}
                </flux:button>
            </a>
        </div>

        <!-- Staff Table -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            @if ($staff->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 mb-4">
                        <flux:icon.users class="h-7 w-7 text-zinc-400" />
                    </div>
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('No staff members yet') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Get started by inviting your first staff member.') }}</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.staff.invite') }}" wire:navigate>
                            <flux:button variant="primary" icon="plus" size="sm">
                                {{ __('Invite Staff') }}
                            </flux:button>
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Staff Member') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Date Added') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">{{ __('Actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($staff as $member)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-700 text-sm font-semibold text-zinc-600 dark:text-zinc-300">
                                                @if ($member->avatar)
                                                    <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="h-10 w-10 rounded-full object-cover" />
                                                @else
                                                    {{ $member->name ? $member->initials() : '?' }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $member->name ?: '—' }}
                                                </div>
                                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    {{ $member->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (! $member->invitation_accepted_at)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                {{ __('Pending') }}
                                            </span>
                                        @elseif ($member->is_active)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                {{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $member->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.staff.show', $member) }}" wire:navigate>
                                                <flux:button size="sm" variant="ghost" icon="eye">
                                                    {{ __('View') }}
                                                </flux:button>
                                            </a>

                                            @if ($member->invitation_accepted_at)
                                                <form method="POST" action="{{ route('admin.staff.toggle', $member) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <flux:button
                                                        size="sm"
                                                        variant="ghost"
                                                        :icon="$member->is_active ? 'no-symbol' : 'check-circle'"
                                                        type="submit"
                                                    >
                                                        {{ $member->is_active ? __('Deactivate') : __('Activate') }}
                                                    </flux:button>
                                                </form>
                                            @endif

                                            <div
                                                x-data="{ open: false }"
                                            >
                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="trash"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                                                    @click="open = true"
                                                >
                                                    {{ __('Delete') }}
                                                </flux:button>

                                                <!-- Delete Confirmation Modal -->
                                                <div
                                                    x-show="open"
                                                    x-cloak
                                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                    @keydown.escape.window="open = false"
                                                >
                                                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                                    <div class="relative w-full max-w-md rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6">
                                                        <div class="flex items-center gap-3 mb-4">
                                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                                                <flux:icon.trash class="h-5 w-5 text-red-600 dark:text-red-400" />
                                                            </div>
                                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Delete Staff Member') }}</h3>
                                                        </div>
                                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
                                                            {{ __('Are you sure you want to delete') }} <strong class="text-zinc-900 dark:text-zinc-100">{{ $member->name ?: $member->email }}</strong>? {{ __('This action cannot be undone.') }}
                                                        </p>
                                                        <div class="flex justify-end gap-3">
                                                            <flux:button variant="ghost" @click="open = false">{{ __('Cancel') }}</flux:button>
                                                            <form method="POST" action="{{ route('admin.staff.destroy', $member) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <flux:button variant="danger" type="submit">{{ __('Delete') }}</flux:button>
                                                            </form>
                                                        </div>
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
</x-layouts.admin.app>
