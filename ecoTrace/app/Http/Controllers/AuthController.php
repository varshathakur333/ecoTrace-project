<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,collector',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            // Collector specific fields
            'business_name' => 'required_if:role,collector|string|max:255',
            'license_no' => 'required_if:role,collector|string|max:255',
        ], [
            'business_name.required_if' => 'The business name is required for collectors.',
            'license_no.required_if' => 'The license number is required for collectors.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
            'phone' => $request->phone,
            'business_name' => $request->role === 'collector' ? $request->business_name : null,
            'license_no' => $request->role === 'collector' ? $request->license_no : null,
            'is_verified' => false, // Pending admin approval
        ]);

        // If it's a collector, submit a verification request
        if ($user->role === 'collector') {
            VerificationRequest::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'license_no' => $request->license_no,
                'status' => 'pending',
            ]);
            
            // Send verification required email notification (dummy log email)
            try {
                \Illuminate\Support\Facades\Mail::to('admin@ecotrace.com')->send(new \App\Mail\CollectorVerificationMail($user));
            } catch (\Exception $e) {
                // Ignore mail errors locally
            }
        }

        Auth::login($user);

        // Custom cookies set demo
        cookie()->queue('last_login_role', $user->role, 1440);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to EcoTrace.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Set cookie for role state
            cookie()->queue('last_login_role', Auth::user()->role, 1440);

            return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully!');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
