<x-layouts::admin.app :title="$contact->full_name">
    <x-slot name="header">{{ $contact->full_name }}</x-slot>

    <div class="space-y-4">

        {{-- Top actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.crm.contacts.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Contacts
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.crm.contacts.edit', $contact->id) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.crm.contacts.destroy', $contact->id) }}"
                      x-data
                      @submit.prevent="if(confirm('Delete {{ addslashes($contact->full_name) }}? This cannot be undone.')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm transition-colors hover:bg-red-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Two-column layout --}}
        <div class="grid gap-4 lg:grid-cols-3">

            {{-- Left: Contact info + Notes --}}
            <div class="space-y-4 lg:col-span-2">

                {{-- Contact info card --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-400">Contact Information</h2>
                    <dl class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">First Name</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $contact->first_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Last Name</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $contact->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Email</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">
                                <a href="mailto:{{ $contact->email }}" class="text-indigo-600 hover:underline">{{ $contact->email }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Phone</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $contact->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Source</dt>
                            <dd class="mt-0.5">
                                @if ($contact->source === 'funnel')
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">Funnel</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Manual</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Added By</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $contact->creator?->name ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Notes card --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Notes</h2>
                    @if ($contact->notes)
                        <p class="whitespace-pre-line text-sm text-gray-700">{{ $contact->notes }}</p>
                    @else
                        <p class="text-sm text-gray-400">No notes added.</p>
                    @endif
                </div>

            </div>

            {{-- Right: Stage + Tags + Activity --}}
            <div class="space-y-4">

                {{-- Stage card --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Pipeline Stage</h2>
                    <livewire:crm.stage-updater :contactId="$contact->id" />
                </div>

                {{-- Tags card --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Tags</h2>
                    @if ($contact->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($contact->tags as $tag)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $tag->color }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No tags assigned.</p>
                    @endif
                </div>

                {{-- Activity card --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Activity</h2>
                    <ol class="space-y-3">
                        <li class="flex items-start gap-3 text-sm">
                            <span class="mt-0.5 h-5 w-5 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">+</span>
                            <div>
                                <p class="text-gray-700">Contact created
                                    @if ($contact->source === 'funnel')
                                        <span class="ml-1 inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">via Funnel</span>
                                    @else
                                        <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Manual</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $contact->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </li>
                        @if ($contact->updated_at->gt($contact->created_at))
                        <li class="flex items-start gap-3 text-sm">
                            <span class="mt-0.5 h-5 w-5 shrink-0 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold">↑</span>
                            <div>
                                <p class="text-gray-700">Contact updated</p>
                                <p class="text-xs text-gray-400">{{ $contact->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </li>
                        @endif
                    </ol>
                </div>

            </div>
        </div>
    </div>
</x-layouts::admin.app>
