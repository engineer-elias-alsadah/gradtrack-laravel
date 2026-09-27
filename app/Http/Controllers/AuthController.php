<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email.required' => 'البريد الإلكتروني الأكاديمي مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'مرحباً بك، تم تسجيل الدخول بنجاح.');
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد المدخلة غير صحيحة أو غير متطابقة.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:student,supervisor'],
            'university_id' => ['required', 'string', 'max:50'],
            'department' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'الاسم الثلاثي أو الرباعي مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً في النظام.',
            'role.required' => 'يجب تحديد نوع الحساب (طالب أو مشرف).',
            'role.in' => 'نوع الحساب يجب أن يكون إما طالب أو مشرف أكاديمي.',
            'university_id.required' => 'الرقم الجامعي أو الأكاديمي مطلوب.',
            'department.required' => 'اسم القسم الأكاديمي مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 6 خانات.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'university_id' => $validated['university_id'],
            'department' => $validated['department'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $roleText = $user->isSupervisor() ? 'مشرف أكاديمي' : 'طالب';
        return redirect()->route('dashboard')
            ->with('success', "تم إنشاء حسابك بنجاح بدور: ({$roleText}). أهلاً بك في منصة GradTrack.");
    }

    /**
     * Quick switcher for defense demonstration
     */
    public function quickLogin(int $userId)
    {
        $user = User::findOrFail($userId);
        Auth::login($user);
        request()->session()->regenerate();

        $roleText = $user->isSupervisor() ? 'مشرف أكاديمي' : 'طالب';
        return redirect()->route('dashboard')
            ->with('info', "تم التبديل بنجاح إلى حساب: {$user->name} ({$roleText}).");
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
