<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\FunnelApiKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Public API endpoint that captures leads from external funnel pages.
 *
 * Authentication: X-Funnel-Key header containing a valid, active FunnelApiKey.
 */
class FunnelCaptureController extends Controller
{
    /**
     * Accept an incoming lead from a funnel page.
     */
    public function store(Request $request): JsonResponse
    {
        $apiKey = FunnelApiKey::where('key', $request->header('X-Funnel-Key'))
            ->where('is_active', true)
            ->first();

        if (! $apiKey) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $apiKey->update(['last_used_at' => now()]);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'funnel_id'  => ['nullable', 'string'],
        ]);

        $existing = Contact::where('email', $data['email'])->first();

        if ($existing) {
            $existing->fill([
                'phone'     => $existing->phone     ?? ($data['phone']     ?? null),
                'funnel_id' => $existing->funnel_id ?? ($data['funnel_id'] ?? null),
            ])->save();

            return response()->json(['contact' => $existing->refresh()]);
        }

        $contact = Contact::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'funnel_id'  => $data['funnel_id'] ?? null,
            'source'     => 'funnel',
            'stage'      => 'lead',
        ]);

        return response()->json(['contact' => $contact]);
    }
}
