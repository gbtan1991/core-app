<x-layouts::admin.app title="Funnel API Keys">
    <x-slot name="header">Funnel API Keys</x-slot>

    <div class="space-y-4">

        {{-- Explanation card --}}
        <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-indigo-800">Use these keys to connect your funnel pages to Meisterflow CRM</p>
                    <p class="mt-0.5 text-sm text-indigo-600">Send a <code class="rounded bg-indigo-100 px-1 font-mono text-xs">POST</code> request to <code class="rounded bg-indigo-100 px-1 font-mono text-xs">/api/funnel/capture</code> with header <code class="rounded bg-indigo-100 px-1 font-mono text-xs">X-Funnel-Key: &lt;key&gt;</code>.</p>
                </div>
            </div>
        </div>

        {{-- Generate new key --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-400">Generate New Key</h2>
            <form method="POST" action="{{ route('admin.crm.funnel-keys.store') }}">
                @csrf
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Key Label <span class="text-red-500">*</span></label>
                        <input name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Homepage Form"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('name') border-red-400 @enderror" />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                            Generate Key
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Keys table --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            @if ($keys->isEmpty())
                <div class="py-12 text-center text-sm text-gray-400">No API keys yet. Generate one above.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Label</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Key</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:table-cell">Last Used</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($keys as $apiKey)
                            <tr
                                x-data="{ visible: false, copied: false }"
                                class="hover:bg-gray-50"
                            >
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">{{ $apiKey->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <code class="rounded bg-gray-100 px-2 py-1 font-mono text-xs text-gray-700" x-text="visible ? '{{ $apiKey->key }}' : '{{ str_repeat('•', 16) }}'"></code>
                                        <button @click="visible = !visible" class="text-xs text-gray-400 hover:text-gray-600" :title="visible ? 'Hide' : 'Show'">
                                            <svg x-show="!visible" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <svg x-show="visible" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </button>
                                        <button
                                            @click="navigator.clipboard.writeText('{{ $apiKey->key }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="text-xs text-gray-400 hover:text-indigo-600"
                                            :title="copied ? 'Copied!' : 'Copy'"
                                        >
                                            <svg x-show="!copied" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                            </svg>
                                            <svg x-show="copied" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if ($apiKey->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="hidden whitespace-nowrap px-4 py-3 text-sm text-gray-500 sm:table-cell">
                                    {{ $apiKey->last_used_at ? $apiKey->last_used_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.crm.funnel-keys.toggle', $apiKey->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="rounded px-2 py-1 text-xs font-medium {{ $apiKey->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }}">
                                                {{ $apiKey->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.crm.funnel-keys.destroy', $apiKey->id) }}"
                                              x-data
                                              @submit.prevent="if(confirm('Delete key \'{{ addslashes($apiKey->name) }}\'?')) $el.submit()">
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
                </div>
            @endif
        </div>

    </div>
</x-layouts::admin.app>
