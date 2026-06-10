<x-layouts::admin.app title="Edit Contact">
    <x-slot name="header">Edit Contact</x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="rounded-2xl bg-white p-6 shadow-sm">

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <ul class="space-y-0.5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.crm.contacts.update', $contact->id) }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- Name row --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                        <input name="first_name" type="text" value="{{ old('first_name', $contact->first_name) }}" required
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('first_name') border-red-400 @enderror" />
                        @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                        <input name="last_name" type="text" value="{{ old('last_name', $contact->last_name) }}" required
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('last_name') border-red-400 @enderror" />
                        @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contact row --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input name="email" type="email" value="{{ old('email', $contact->email) }}" required
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('email') border-red-400 @enderror" />
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input name="phone" type="tel" value="{{ old('phone', $contact->phone) }}"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" />
                    </div>
                </div>

                {{-- Stage --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stage <span class="text-red-500">*</span></label>
                    <select name="stage" required
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        @foreach ($stages as $value => $label)
                            <option value="{{ $value }}" @selected(old('stage', $contact->stage) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tags multi-select --}}
                @if ($tags->isNotEmpty())
                @php $selectedTagIds = old('tags', $contact->tags->pluck('id')->toArray()); @endphp
                <div x-data="{ open: false }">
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div class="relative mt-1.5">
                        <button type="button" @click="open = !open"
                            class="flex w-full items-center justify-between rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none">
                            <span>Select tags…</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white p-2 shadow-lg">
                            @foreach ($tags as $tag)
                                <label class="flex cursor-pointer items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-gray-50">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                        @checked(in_array($tag->id, $selectedTagIds))
                                        class="rounded border-gray-300 text-indigo-600" />
                                    <span class="h-3 w-3 rounded-full" style="background-color: {{ $tag->color }}"></span>
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="4"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('notes', $contact->notes) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 border-t border-gray-100 pt-4">
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.crm.contacts.show', $contact->id) }}"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::admin.app>
