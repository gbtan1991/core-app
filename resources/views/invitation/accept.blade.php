<x-layouts::auth :title="__('Accept Invitation')">
    <div class="flex flex-col gap-6">
        @if ($expired)
            <!-- Expired Token -->
            <div class="flex flex-col items-center gap-4 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-7 w-7 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ __('Invitation Expired') }}</h1>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('This invitation link has expired or is invalid. Please contact your administrator for a new invitation.') }}
                    </p>
                </div>
                <flux:link :href="route('login')" wire:navigate class="text-sm">
                    {{ __('Return to login') }}
                </flux:link>
            </div>
        @else
            <!-- Accept Form -->
            <x-auth-header
                :title="__('Activate Your Account')"
                :description="__('Welcome! Set your name and password to get started.')"
            />

            <!-- Pre-filled email (read-only) -->
            <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ __('Email:') }}</span>
                {{ $user->email }}
            </div>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.accept.store', request()->route('token')) }}" class="flex flex-col gap-6">
                @csrf

                <flux:input
                    name="name"
                    :label="__('Full Name')"
                    type="text"
                    required
                    autofocus
                    :value="old('name')"
                    placeholder="John Doe"
                />

                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    viewable
                />

                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    viewable
                />

                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Activate My Account') }}
                </flux:button>
            </form>
        @endif
    </div>
</x-layouts::auth>
