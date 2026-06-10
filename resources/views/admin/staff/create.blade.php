<x-layouts::admin.app title="Add Staff">
    <x-slot name="header">Add Staff</x-slot>

    <div class="mx-auto max-w-lg space-y-4">

        {{-- Back link --}}
        <a href="{{ route('admin.staff.index') }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 transition-colors hover:text-gray-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Staff
        </a>

        <div>
            <h2 class="text-sm font-semibold text-gray-900">Create account directly</h2>
            <p class="mt-0.5 text-sm text-gray-500">
                Fill in the staff member's details. A temporary password will be emailed to them.
                They will be required to change it on first login.
            </p>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                <ul class="space-y-0.5 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl bg-white p-6 shadow-sm"
             x-data="{
                tempPassword: '{{ old('temp_password', $tempPassword) }}',
                copied: false,
                generate() {
                    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
                    let pass = '';
                    for (let i = 0; i < 12; i++) {
                        pass += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.tempPassword = pass;
                    this.copied = false;
                },
                copy() {
                    navigator.clipboard.writeText(this.tempPassword);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                }
             }">

            <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-5">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        autofocus
                        value="{{ old('name') }}"
                        placeholder="Jane Doe"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                               focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                               @error('name') border-red-400 @enderror"
                    />
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="jane@example.com"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                               focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                               @error('email') border-red-400 @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role (locked) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <div class="mt-1.5 flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5">
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700">
                            Staff
                        </span>
                        <span class="text-xs text-gray-400">(cannot be changed here)</span>
                    </div>
                    <input type="hidden" name="role" value="staff" />
                </div>

                {{-- Temporary Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Temporary Password
                    </label>
                    <p class="mb-1.5 text-xs text-gray-500">
                        This password will be emailed to the staff member. They must change it on first login.
                    </p>

                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                x-model="tempPassword"
                                name="temp_password"
                                readonly
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 font-mono text-sm text-gray-900 shadow-sm focus:outline-none"
                            />
                        </div>

                        {{-- Copy button --}}
                        <button
                            type="button"
                            @click="copy()"
                            title="Copy password"
                            class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-xs font-medium text-gray-600 shadow-sm transition-colors hover:bg-gray-50"
                        >
                            <svg x-show="!copied" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                            </svg>
                            <svg x-show="copied" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>

                        {{-- Regenerate button --}}
                        <button
                            type="button"
                            @click="generate()"
                            title="Generate new password"
                            class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-xs font-medium text-gray-600 shadow-sm transition-colors hover:bg-gray-50"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Regenerate
                        </button>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                    >
                        Create Account &amp; Send Email
                    </button>
                    <a href="{{ route('admin.staff.index') }}"
                       class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-700">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-layouts::admin.app>
