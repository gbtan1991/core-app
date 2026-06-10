@php
    $classes = match($stage) {
        'lead'          => 'bg-gray-100 text-gray-600',
        'contacted'     => 'bg-blue-100 text-blue-700',
        'qualified'     => 'bg-yellow-100 text-yellow-700',
        'proposal_sent' => 'bg-purple-100 text-purple-700',
        'won'           => 'bg-green-100 text-green-700',
        'lost'          => 'bg-red-100 text-red-700',
        default         => 'bg-gray-100 text-gray-600',
    };
    $label = match($stage) {
        'lead'          => 'Lead',
        'contacted'     => 'Contacted',
        'qualified'     => 'Qualified',
        'proposal_sent' => 'Proposal Sent',
        'won'           => 'Won',
        'lost'          => 'Lost',
        default         => ucfirst($stage),
    };
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
