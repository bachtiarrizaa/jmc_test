<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Kode captcha tidak cocok.',
            'captcha.required' => 'Wajib mengisi captcha.',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' 
                    : (preg_match('/^[0-9]+$/', $request->login) ? 'phone' : 'username');

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'login' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
                ]);
            }

            $user->update([
                'last_login_at' => now(),
            ]);

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'login' => 'Kredensial yang Anda berikan tidak cocok dengan data kami.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }
}
