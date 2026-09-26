<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $demoUsers = User::all();
        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'مرحباً بك مجدداً، تم تسجيل الدخول بنجاح.');
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد المدخلة غير متطابقة مع سجلاتنا.',
        ])->onlyInput('email');
    }

    /**
     * Quick switcher for defense demonstration
     */
    public function quickLogin(int $userId)
    {
        $user = User::findOrFail($userId);
        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('info', "تم التبديل بنجاح إلى حساب: {$user->name} ({$user->role}).");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'تم تسجيل الخروج بنجاح.');
    }
}
