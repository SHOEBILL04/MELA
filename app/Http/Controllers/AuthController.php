<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,vendor,employee,visitor',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
            ]);

            Auth::login($user);

            return $this->redirectBasedOnRole($user->role);
        } catch (QueryException $e) {
            // MSSQL Error code for unique constraint violation is 2627
            if ($e->getCode() == '23000' || str_contains($e->getMessage(), 'Violation of UNIQUE KEY constraint')) {
                return back()->withInput()->withErrors(['email' => 'This email is already registered.']);
            }
            return back()->withInput()->withErrors(['error' => 'An unexpected database error occurred.']);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectBasedOnRole($role)
    {
        return redirect()->route('dashboard');
    }
}
