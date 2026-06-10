<x-layouts::admin.app title="Dashboard">
    <x-slot name="header">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Welcome back, {{ auth()->user()->name }}
                    </h2>
                    <p class="mt-0.5 flex items-center gap-2 text-sm text-gray-500">
                        You are signed in as
                        @if (auth()->user()->isSuperAdmin())
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                                Super Admin
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700">
                                Staff
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @role('super_admin')
        {{-- Quick stats --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Total Staff</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'staff')->count() }}
                </p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Active Staff</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'staff')->where('is_active', true)->whereNotNull('invitation_accepted_at')->count() }}
                </p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Pending Invitations</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'staff')->whereNotNull('invitation_token')->count() }}
                </p>
            </div>
        </div>
        @endrole

        {{-- CRM Stats widget --}}
        @php
            $crmTotal = \App\Models\Contact::count();
            $crmStages = \App\Models\Contact::selectRaw('stage, count(*) as count')->groupBy('stage')->pluck('count', 'stage');
            $stageOrder = ['lead', 'contacted', 'qualified', 'proposal_sent', 'won', 'lost'];
            $stageLabels = ['lead' => 'Lead', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal Sent', 'won' => 'Won', 'lost' => 'Lost'];
            $stageColors = ['lead' => 'bg-gray-300', 'contacted' => 'bg-blue-400', 'qualified' => 'bg-yellow-400', 'proposal_sent' => 'bg-purple-400', 'won' => 'bg-green-400', 'lost' => 'bg-red-400'];
        @endphp
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">CRM</p>
                        <p class="text-xs text-gray-500">{{ $crmTotal }} total {{ Str::plural('contact', $crmTotal) }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.crm.contacts.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All →</a>
            </div>
            @if ($crmTotal > 0)
                <div class="space-y-2">
                    @foreach ($stageOrder as $stage)
                        @php $count = $crmStages[$stage] ?? 0; $pct = $crmTotal > 0 ? round($count / $crmTotal * 100) : 0; @endphp
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-24 shrink-0 text-gray-500">{{ $stageLabels[$stage] }}</span>
                            <div class="flex-1 rounded-full bg-gray-100 h-2">
                                <div class="h-2 rounded-full {{ $stageColors[$stage] }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-600">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">No contacts yet. <a href="{{ route('admin.crm.contacts.create') }}" class="text-indigo-600 hover:underline">Add your first contact.</a></p>
            @endif
        </div>

        {{-- Coming-soon modules --}}
        <div>
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Upcoming Modules</h3>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Bookings --}}
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM12 15h.008v.008H12V15zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM9.75 15h.008v.008H9.75V15zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Bookings</h4>
                    <p class="mt-1 text-sm text-gray-500">Schedule and track appointments.</p>
                    <span class="mt-3 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">
                        Coming soon
                    </span>
                </div>

                {{-- Workflow --}}
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Workflow</h4>
                    <p class="mt-1 text-sm text-gray-500">Automate tasks and team processes.</p>
                    <span class="mt-3 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">
                        Coming soon
                    </span>
                </div>

            </div>
        </div>

    </div>
</x-layouts::admin.app>
