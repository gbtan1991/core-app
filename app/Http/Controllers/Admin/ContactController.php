<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * CRUD controller for CRM contacts.
 */
class ContactController extends Controller
{
    /**
     * List all contacts (search + filter handled by Livewire ContactTable).
     */
    public function index(): View
    {
        return view('crm.contacts.index');
    }

    /**
     * Show the create-contact form.
     */
    public function create(): View
    {
        $tags   = Tag::orderBy('name')->get();
        $stages = $this->stages();

        return view('crm.contacts.create', compact('tags', 'stages'));
    }

    /**
     * Persist a new contact and attach tags.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $contact = Contact::create(array_merge(
            $request->safe()->except('tags'),
            ['created_by' => auth()->id()]
        ));

        if ($request->filled('tags')) {
            $contact->tags()->sync($request->tags);
        }

        session()->flash('success', "{$contact->full_name} has been added.");

        return redirect()->route('admin.crm.contacts.index');
    }

    /**
     * Show a single contact's detail page.
     */
    public function show(int $id): View
    {
        $contact = Contact::with(['tags', 'creator'])->findOrFail($id);
        $stages  = $this->stages();

        return view('crm.contacts.show', compact('contact', 'stages'));
    }

    /**
     * Show the edit-contact form.
     */
    public function edit(int $id): View
    {
        $contact = Contact::with('tags')->findOrFail($id);
        $tags    = Tag::orderBy('name')->get();
        $stages  = $this->stages();

        return view('crm.contacts.edit', compact('contact', 'tags', 'stages'));
    }

    /**
     * Update a contact's details.
     */
    public function update(UpdateContactRequest $request, int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->safe()->except('tags'));
        $contact->tags()->sync($request->tags ?? []);

        session()->flash('success', "{$contact->full_name} has been updated.");

        return redirect()->route('admin.crm.contacts.show', $id);
    }

    /**
     * Delete a contact.
     */
    public function destroy(int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        $name    = $contact->full_name;
        $contact->delete();

        session()->flash('success', "{$name} has been deleted.");

        return redirect()->route('admin.crm.contacts.index');
    }

    /** Ordered stage options used by forms. */
    private function stages(): array
    {
        return [
            'lead'          => 'Lead',
            'contacted'     => 'Contacted',
            'qualified'     => 'Qualified',
            'proposal_sent' => 'Proposal Sent',
            'won'           => 'Won',
            'lost'          => 'Lost',
        ];
    }
}
