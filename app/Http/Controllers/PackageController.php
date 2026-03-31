<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function select(string $slug)
    {
        $package = Package::where('slug', $slug)->where('is_active', true)->firstOrFail();

      if (!Auth::check()) {
    session(['selected_package' => $slug]);
    return redirect()->route('register')->with('info', 'Paketi satın almak için önce üye olman gerekiyor.');
}

        return redirect()->route('payment.show', $slug);
    }
}