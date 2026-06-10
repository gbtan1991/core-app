<x-layouts.admin.app :title="__('Invite Staff')">
    <div class="flex flex-col gap-6 max-w-lg">
        <!-- Back Link -->
        <div>
            <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition" wire:navigate>
                <flux:icon.arrow-left class="h-4 w-4" />
                {{ __('Back to Staff') }}
            </a>
        </div>

        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ __('Invite Staff Member') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Send an invitation email with a secure link. The link expires in 48 hours.') }}
            </p>
        </div>

        <!-- Invite Form -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.staff.invite.store') }}" class="flex flex-col gap-6">
                @csrf

                <flux:input
                    name="email"
                    :label="__('Email Address')"
                    type="email"
                    required
                    autofocus
                    :value="old('email')"
                    placeholder="staff@example.com"
                    :description="__('The staff member will receive an invitation at this email address.')"
                />

                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <div class="flex items-center gap-3">
                    <flux:button variant="primary" type="submit">
                        {{ __('Send Invitation') }}
                    </flux:button>
                    <a href="{{ route('admin.staff.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin.app>
