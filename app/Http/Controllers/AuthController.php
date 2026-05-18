<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isSuperAdmin() 
                ? redirect()->route('super-admin.dashboard')
                : redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email'    => trim($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return Auth::user()->isSuperAdmin()
                ? redirect()->route('super-admin.dashboard')
                : redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ── Self-service Signup ──────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'industry'     => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8|confirmed',
        ]);

        // Handle logo upload
        $logoUrl = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file    = $request->file('logo');
            $filename = Str::slug($request->company_name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('logos'), $filename);
            $logoUrl = '/logos/' . $filename;
        }

        // Create Company
        $company = Company::create([
            'name'     => $request->company_name,
            'slug'     => Str::slug($request->company_name) . '-' . Str::random(4),
            'industry' => $request->industry,
            'logo_url' => $logoUrl,
        ]);

        // Create Admin User for this company
        $user = User::create([
            'company_id' => $company->id,
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'company_admin',
        ]);

        // Auto login after registration
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', "Welcome to AssetNova, {$user->name}! Your workspace is ready.");
    }
}
