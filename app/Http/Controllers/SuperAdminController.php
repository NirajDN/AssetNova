<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $companies = Company::withCount(['users', 'parts'])->get();
        return view('super-admin.dashboard', compact('companies'));
    }

    public function createCompany()
    {
        return view('super-admin.create-company');
    }

    public function storeCompany(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'industry'       => 'required|string|max:255',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8',
        ]);

        // 1. Create Company
        $company = Company::create([
            'name'     => $request->name,
            'slug'     => Str::slug($request->name),
            'industry' => $request->industry,
            'logo_url' => '/images/assetnova-logo.png', // Default for now
        ]);

        // 2. Create Admin User for this company
        User::create([
            'company_id' => $company->id,
            'name'       => $request->admin_name,
            'email'      => $request->admin_email,
            'password'   => Hash::make($request->admin_password),
            'role'       => 'company_admin',
        ]);

        return redirect()->route('super-admin.dashboard')->with('success', 'New Company Registered Successfully!');
    }
}
