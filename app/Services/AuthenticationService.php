<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    /**
     * Check whether a user is authenticated.
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Authenticate a user with submitted credentials.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $this->validateLoginCredentials($request);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function validateLoginCredentials(Request $request): array
    {
        return $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    }
}
