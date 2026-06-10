<div class="rounded-2xl bg-white p-6 shadow-sm">

    @if (session('invite_success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
        >
            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('invite_success') }}
        </div>
    @endif

    <form wire:submit="send" class="space-y-5">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email address
            </label>
            <input
                id="email"
                type="email"
                wire:model="email"
                placeholder="colleague@example.com"
                autocomplete="off"
                class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                       focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                       @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-xs text-gray-500">
                The staff member will receive an invitation link that expires in 48 hours.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 disabled:opacity-60"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>
                    Send Invite
                </span>
                <span wire:loading class="flex items-center gap-1.5">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Sending…
                </span>
            </button>

            <a
                href="{{ route('admin.staff.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-700"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
