<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Ad soyad zorunludur.',
            'email.required'     => 'E-posta zorunludur.',
            'email.unique'       => 'Bu e-posta zaten kayıtlı.',
            'password.required'  => 'Şifre zorunludur.',
            'password.min'       => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ]);

        $referrer = null;
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'referred_by' => $referrer?->id,
        ]);

        // Hoş geldin kredisi
        $welcomeCredits = (int) Setting::get('welcome_credits', 2);
        if ($welcomeCredits > 0) {
            $user->addCredits($welcomeCredits, 'bonus', 'Hoş geldin bonusu');
        }

        // Referans bonusu
        if ($referrer) {
            $referralCredits = (int) Setting::get('referral_credits', 10);
            $referrer->addCredits($referralCredits, 'referral', $user->name . ' referansı ile kazanıldı');
            $user->addCredits($referralCredits, 'referral', 'Referans bonusu');
        }

        Auth::login($user);

        // Paket seçilmişse ödeme sayfasına, yoksa dashboard'a
$slug = session()->pull('selected_package');
if ($slug) {
    return redirect()->route('payment.show', $slug);
}

return redirect()->route('dashboard');
    }
}