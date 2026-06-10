<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD controller for contact tags (super_admin only).
 */
class TagController extends Controller
{
    /**
     * List all tags with contact counts.
     */
    public function index(): View
    {
        $tags = Tag::withCount('contacts')->orderBy('name')->get();

        return view('crm.tags.index', compact('tags'));
    }

    /**
     * Create a new tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'unique:tags,name'],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        Tag::create($request->only('name', 'color'));

        session()->flash('success', "Tag \"{$request->name}\" created.");

        return redirect()->route('admin.crm.tags.index');
    }

    /**
     * Update a tag's name and/or color.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name'  => ['required', 'string', 'max:50', "unique:tags,name,{$id}"],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $tag->update($request->only('name', 'color'));

        session()->flash('success', "Tag updated.");

        return redirect()->route('admin.crm.tags.index');
    }

    /**
     * Delete a tag, automatically detaching it from all contacts.
     */
    public function destroy(int $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);
        $tag->contacts()->detach();
        $tag->delete();

        session()->flash('success', "Tag \"{$tag->name}\" deleted.");

        return redirect()->route('admin.crm.tags.index');
    }
}
