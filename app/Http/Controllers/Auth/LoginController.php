<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

  public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Admin ise direkt admin paneline
        if ($user->is_admin) {
            return redirect('/admin');
        }

        // Paket seçilmişse ödeme sayfasına
        if (session('selected_package')) {
            $slug = session()->pull('selected_package');
            return redirect()->route('payment.show', $slug);
        }

        // Normal kullanıcı paneline
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'E-posta veya şifre hatalı.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}