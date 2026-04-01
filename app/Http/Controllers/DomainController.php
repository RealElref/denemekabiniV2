<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'domain_name'        => 'required|string|max:63|regex:/^[a-z0-9\-]+$/i',
            'tld'                => ['required', 'string', 'max:20', 'regex:/^\.[a-z]{2,}(\.[a-z]{2,})?$/'],
            'registration_years' => 'required|integer|in:1,2,3,5',
        ]);

        $user = Auth::user();

        $exists = Domain::where('user_id', $user->id)
            ->where('domain_name', strtolower($request->domain_name))
            ->where('tld', $request->tld)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => __('domain_already_exists'),
            ]);
        }

        Domain::create([
            'user_id'            => $user->id,
            'domain_name'        => strtolower($request->domain_name),
            'tld'                => $request->tld,
            'registration_years' => $request->registration_years,
            'status'             => 'pending',
            'credits_used'       => 0,
            'price_paid'         => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('domain_submitted'),
        ]);
    }

    public function destroy($id)
    {
        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'rejected'])
            ->firstOrFail();

        $domain->delete();

        return response()->json(['success' => true]);
    }
}
