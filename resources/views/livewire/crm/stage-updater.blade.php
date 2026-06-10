<div>
    <select
        wire:model.live="stage"
        wire:change="updateStage"
        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
    >
        <option value="lead">Lead</option>
        <option value="contacted">Contacted</option>
        <option value="qualified">Qualified</option>
        <option value="proposal_sent">Proposal Sent</option>
        <option value="won">Won</option>
        <option value="lost">Lost</option>
    </select>
</div>
