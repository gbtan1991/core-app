<?php

namespace App\Livewire\Crm;

use App\Models\Contact;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Reactive, searchable, filterable contacts table with pagination.
 */
class ContactTable extends Component
{
    use WithPagination;

    /** @var string Live search term. */
    public string $search = '';

    /** @var string Filter by pipeline stage. */
    public string $filterStage = '';

    /** @var string Filter by tag ID. */
    public string $filterTag = '';

    /** @var int Rows per page. */
    public int $perPage = 15;

    /** Reset pagination whenever a filter changes. */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStage(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTag(): void
    {
        $this->resetPage();
    }

    /**
     * Delete a contact with confirmation already handled by Alpine.js in the view.
     */
    public function delete(int $contactId): void
    {
        $contact = Contact::findOrFail($contactId);
        $name    = $contact->full_name;
        $contact->delete();

        session()->flash('success', "{$name} has been deleted.");
    }

    public function render(): \Illuminate\View\View
    {
        $query = Contact::with(['tags', 'creator'])->latest();

        if ($this->search !== '') {
            $query->search($this->search);
        }

        if ($this->filterStage !== '') {
            $query->byStage($this->filterStage);
        }

        if ($this->filterTag !== '') {
            $query->byTag((int) $this->filterTag);
        }

        $contacts = $query->paginate($this->perPage);
        $tags     = Tag::orderBy('name')->get();

        return view('livewire.crm.contact-table', compact('contacts', 'tags'));
    }
}
