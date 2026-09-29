<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Valide ievadītos datus, veic autorizāciju,
     * atjauno sessijas identifikatoru, paradrese lietotaju
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()
            ->withErrors([
                'email' => __('messages.authError'),
            ])
            ->onlyInput('email');
    }

    public function showRegisterForm(): View {
        return view('pages.auth.register');
    }

    /**
     * Pārbauda ievadītos datus, pievieno jaunu lietotāju,
     * autorizē lietotāju un atgriež sākumlapā.
     */
    public function register(Request $request): RedirectResponse{
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // izveido jauno CSRF tokenu

        return redirect('/login');
    }
}
