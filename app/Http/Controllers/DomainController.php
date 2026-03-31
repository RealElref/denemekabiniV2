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
            'domain_name'        => 'required|string|max:100|regex:/^[a-z0-9\-]+$/i',
            'tld'                => 'required|string|in:.com,.net,.org,.com.tr,.net.tr,.org.tr,.tr,.io,.co',
            'registration_years' => 'required|integer|in:1,2,3,5',
        ]);

        $user = Auth::user();

        $fullDomain = strtolower($request->domain_name) . $request->tld;

        $exists = Domain::where('domain_name', strtolower($request->domain_name))
            ->where('tld', $request->tld)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Bu domain zaten kayıtlı veya onay bekliyor.',
            ], 422);
        }

        $domain = Domain::create([
            'user_id'            => $user->id,
            'domain_name'        => strtolower($request->domain_name),
            'tld'                => $request->tld,
            'registration_years' => $request->registration_years,
            'credits_used'       => 0,
            'status'             => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Domain talebiniz alındı. Admin onayı bekleniyor.',
            'domain'  => [
                'id'          => $domain->id,
                'full_domain' => $domain->full_domain,
                'status'      => $domain->status,
                'status_label'=> $domain->status_label,
            ],
        ]);
    }

    public function destroy(int $id)
    {
        $user   = Auth::user();
        $domain = Domain::where('id', $id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->firstOrFail();

        $domain->delete();

        return response()->json([
            'success' => true,
            'message' => 'Domain talebi silindi.',
        ]);
    }
}
