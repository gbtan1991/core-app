<x-layouts.admin.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                {{ __('Dashboard') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Welcome back,') }} {{ auth()->user()->name }}
            </p>
        </div>

        <!-- Welcome Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <flux:icon.home class="h-6 w-6 text-zinc-500 dark:text-zinc-400" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ __('Welcome to CRM') }}
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('Your role:') }}
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ auth()->user()->isSuperAdmin()
                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
                                : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                            {{ auth()->user()->isSuperAdmin() ? __('Super Admin') : __('Staff') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        @if (auth()->user()->isSuperAdmin())
            <!-- Quick Stats -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('Total Staff') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ \App\Models\User::where('role', 'staff')->count() }}
                    </p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('Active Staff') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ \App\Models\User::where('role', 'staff')->where('is_active', true)->whereNotNull('invitation_accepted_at')->count() }}
                    </p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('Pending Invitations') }}</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ \App\Models\User::where('role', 'staff')->whereNotNull('invitation_token')->count() }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin.app>
