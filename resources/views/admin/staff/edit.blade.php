<x-layouts::admin.app title="Edit Staff">
    <x-slot name="header">Edit Staff</x-slot>

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
            <h2 class="text-sm font-semibold text-gray-900">Edit staff member</h2>
            <p class="mt-0.5 text-sm text-gray-500">
                Update {{ $staff->name ?: $staff->email }}'s name or email address.
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

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.staff.update', $staff) }}" class="space-y-5">
                @csrf
                @method('PATCH')

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
                        value="{{ old('name', $staff->name) }}"
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
                        value="{{ old('email', $staff->email) }}"
                        placeholder="jane@example.com"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                               focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                               @error('email') border-red-400 @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role (display-only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <div class="mt-1.5 flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5">
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700">
                            Staff
                        </span>
                        <span class="text-xs text-gray-400">(cannot be changed here)</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                    >
                        Save Changes
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
