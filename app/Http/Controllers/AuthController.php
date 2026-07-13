<?php

namespace App\Http\Controllers;

use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthenticationService $authenticationService)
    {
    }

    // Tampilkan form login
    public function showLoginForm()
    {
        // Kalau sudah login, langsung arahkan ke dashboard
        if ($this->authenticationService->isAuthenticated()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        return $this->authenticationService->login($request);
    }

    // Proses logout
    public function logout(Request $request)
    {
        return $this->authenticationService->logout($request);
    }
}
