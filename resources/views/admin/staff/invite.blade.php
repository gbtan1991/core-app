<x-layouts::admin.app title="Invite Staff">
    <x-slot name="header">Invite Staff</x-slot>

    <div class="mx-auto max-w-lg space-y-4">

        {{-- Back link --}}
        <a
            href="{{ route('admin.staff.index') }}"
            class="inline-flex items-center gap-1 text-sm text-gray-500 transition-colors hover:text-gray-700"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Staff
        </a>

        <div>
            <h2 class="text-sm font-semibold text-gray-900">Send an invitation</h2>
            <p class="mt-0.5 text-sm text-gray-500">
                Enter the email address of the person you'd like to invite. They'll receive a secure link valid for 48 hours.
            </p>
        </div>

        {{-- Livewire invite form --}}
        <livewire:admin.invite-staff />

    </div>
</x-layouts::admin.app>
