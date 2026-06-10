<x-layouts::admin.app title="Tags">
    <x-slot name="header">Tags</x-slot>

    <div class="space-y-4">

        {{-- Add tag form --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-400">Add New Tag</h2>
            <form method="POST" action="{{ route('admin.crm.tags.store') }}">
                @csrf
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Tag Name <span class="text-red-500">*</span></label>
                        <input name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. VIP"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('name') border-red-400 @enderror" />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <input name="color" type="color" value="{{ old('color', '#6366f1') }}"
                            class="mt-1.5 h-10 w-20 cursor-pointer rounded-lg border border-gray-300 px-1 py-1 shadow-sm" />
                    </div>
                    <div>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                            Add Tag
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tags table --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            @if ($tags->isEmpty())
                <div class="py-12 text-center text-sm text-gray-400">No tags yet.</div>
            @else
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tag</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Contacts</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($tags as $tag)
                        <tr
                            x-data="{ editing: false, name: '{{ addslashes($tag->name) }}', color: '{{ $tag->color }}' }"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-4 py-3">
                                {{-- View mode --}}
                                <div x-show="!editing" class="flex items-center gap-2">
                                    <span class="h-4 w-4 shrink-0 rounded-full" style="background-color: {{ $tag->color }}"></span>
                                    <span class="text-sm font-medium text-gray-900">{{ $tag->name }}</span>
                                </div>
                                {{-- Edit mode --}}
                                <form x-show="editing" method="POST" action="{{ route('admin.crm.tags.update', $tag->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="color" name="color" x-model="color"
                                        class="h-8 w-10 cursor-pointer rounded border border-gray-300 p-0.5" />
                                    <input type="text" name="name" x-model="name" required
                                        class="block rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none" />
                                    <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">Save</button>
                                    <button type="button" @click="editing = false" class="text-xs text-gray-500 hover:text-gray-700">Cancel</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tag->contacts_count }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editing = !editing" class="rounded p-1 text-gray-400 hover:text-indigo-600" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.crm.tags.destroy', $tag->id) }}"
                                          x-data
                                          @submit.prevent="if(confirm('Delete tag \'{{ addslashes($tag->name) }}\'?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded p-1 text-gray-400 hover:text-red-600" title="Delete">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-layouts::admin.app>
