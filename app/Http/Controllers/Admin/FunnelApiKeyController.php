<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FunnelApiKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Manages funnel API keys used to capture leads from external funnels.
 */
class FunnelApiKeyController extends Controller
{
    /**
     * List all API keys.
     */
    public function index(): View
    {
        $keys = FunnelApiKey::with('creator')->latest()->get();

        return view('crm.funnel-keys.index', compact('keys'));
    }

    /**
     * Generate a new API key and persist it.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        FunnelApiKey::create([
            'name'       => $request->name,
            'key'        => Str::uuid()->toString(),
            'is_active'  => true,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', "API key \"{$request->name}\" generated.");

        return redirect()->route('admin.crm.funnel-keys.index');
    }

    /**
     * Activate or deactivate a key.
     */
    public function toggle(int $id): RedirectResponse
    {
        $key = FunnelApiKey::findOrFail($id);
        $key->update(['is_active' => ! $key->is_active]);

        $status = $key->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Key \"{$key->name}\" {$status}.");

        return redirect()->route('admin.crm.funnel-keys.index');
    }

    /**
     * Permanently delete an API key.
     */
    public function destroy(int $id): RedirectResponse
    {
        $key = FunnelApiKey::findOrFail($id);
        $key->delete();

        session()->flash('success', "Key \"{$key->name}\" deleted.");

        return redirect()->route('admin.crm.funnel-keys.index');
    }
}
