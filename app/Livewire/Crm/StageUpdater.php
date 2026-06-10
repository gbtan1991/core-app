<?php

namespace App\Livewire\Crm;

use App\Models\Contact;
use Livewire\Component;

/**
 * Inline stage dropdown that persists changes to the contact immediately.
 */
class StageUpdater extends Component
{
    /** @var int The contact whose stage is being managed. */
    public int $contactId;

    /** @var string The current stage value. */
    public string $stage = '';

    public function mount(int $contactId): void
    {
        $this->contactId = $contactId;
        $this->stage     = Contact::findOrFail($contactId)->stage;
    }

    /**
     * Persist the new stage and dispatch a browser event so parent pages can react.
     */
    public function updateStage(): void
    {
        $contact = Contact::findOrFail($this->contactId);
        $contact->update(['stage' => $this->stage]);

        $this->dispatch('stage-updated');
        session()->flash('success', 'Stage updated.');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.crm.stage-updater');
    }
}
