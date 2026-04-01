<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Arayüzden gelen "sayfada kaç tane gösterilsin" sayısını alıyoruz.
        // Eğer kullanıcı bir şey seçmemişse varsayılan olarak 10 yapıyoruz.
        $perPage = $request->input('per_page', 3);

        // 2. paginate(3) kısmını paginate($perPage) olarak değiştirdik
        $recentGenerations = $user->generations()
            ->latest()
            ->paginate($perPage, ['*'], 'gen_page')
            ->appends($request->all()); // ÖNEMLİ: 2. sayfaya geçerken seçimin kaybolmamasını sağlar!

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest()
            ->paginate(3, ['*'], 'tx_page');

        $domains = $user->domains()
            ->latest()
            ->paginate(3, ['*'], 'domain_page');

        $packages = \App\Models\Package::active()->get();

        return view('dashboard.index', compact(
            'user',
            'recentGenerations',
            'recentTransactions',
            'domains',
            'packages'
        ));
    }

    public function domainsPartial(Request $request)
    {
        $user    = Auth::user();
        $domains = $user->domains()
            ->latest()
            ->paginate(3, ['*'], 'domain_page');

        return view('dashboard.tabs.domains', compact('domains'));
    }

    public function credits()
    {
        $user     = Auth::user();
        $packages = \App\Models\Package::active()->get();
        return view('dashboard.credits', compact('user', 'packages'));
    }
}