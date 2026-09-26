@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div style="max-width: 900px; margin: 2rem auto; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch;">

    <!-- Left: Welcome & Project Mission -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.9));">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(56, 189, 248, 0.1); color: #38bdf8; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 1.5rem; border: 1px solid rgba(56, 189, 248, 0.2);">
                <i class="fa-solid fa-graduation-cap"></i> مشروع هندسة البرمجيات العملي
            </div>
            
            <h2 style="font-size: 1.75rem; font-weight: 800; line-height: 1.3; margin-bottom: 1rem;">
                حوكمة مشاريع التخرج وفق أفضل المعايير الهندسية
            </h2>
            
            <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 1.5rem;">
                منصة <strong>GradTrack</strong> صُممت لتنظيم دورة حياة مشاريع التخرج، بدءاً من صياغة وثائق المتطلبات (SRS)، وإدارة المراحل (Milestones)، وتتبع التسليمات والربط مع GitHub Actions و CI/CD.
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; color: #cbd5e1;">
                    <i class="fa-solid fa-circle-check" style="color: #38bdf8;"></i>
                    <span>إشراف وتوجيه: <strong>المهندس ساهر الهمداني</strong></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; color: #cbd5e1;">
                    <i class="fa-solid fa-circle-check" style="color: #34d399;"></i>
                    <span>إعداد وبرمجة: <strong>إلياس جمال الساده</strong></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; color: #cbd5e1;">
                    <i class="fa-solid fa-circle-check" style="color: #a855f7;"></i>
                    <span>تطبيق كامل لـ Git Flow, Edge Cases, و CI/CD</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color); font-size: 0.85rem; color: var(--text-muted);">
            <i class="fa-solid fa-quote-right" style="color: #38bdf8; margin-left: 0.5rem;"></i>
            "مهندس البرمجيات لا يسأل: كيف أكتب الكود فقط؟ بل يسأل أيضاً: لماذا أبني؟ وكيف نختبر؟"
        </div>
    </div>

    <!-- Right: Login Form & Quick Access Cards -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: center;">
        <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 0.5rem;">تسجيل الدخول إلى النظام</h3>
        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem;">
            أدخل بريدك الإلكتروني الأكاديمي وكلمة المرور، أو اختر حساباً تجريبياً للمناقشة:
        </p>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">البريد الإلكتروني الأكاديمي</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-envelope" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="email" name="email" id="email" value="{{ old('email', 'elias@university.edu') }}" required 
                        style="width: 100%; padding: 0.75rem 2.75rem 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition);">
                </div>
                @error('email')
                    <span style="color: #f43f5e; font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">كلمة المرور</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="password" name="password" id="password" value="password123" required 
                        style="width: 100%; padding: 0.75rem 2.75rem 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition);">
                </div>
                @error('password')
                    <span style="color: #f43f5e; font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 1rem;">
                <i class="fa-solid fa-right-to-bracket"></i> دخول المنصة
            </button>
        </form>

        <!-- Quick One-Click Switcher for Professor & Student Evaluation -->
        <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.75rem; text-align: center;">
                <i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> دخول سريع بضغطة زر (لمناقشة الدكتور):
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <a href="{{ route('quick-login', 1) }}" class="btn btn-outline btn-sm" style="border-color: rgba(168, 85, 247, 0.4); background: rgba(168, 85, 247, 0.08); text-align: right; justify-content: flex-start;">
                    <i class="fa-solid fa-user-tie" style="color: #c084fc;"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem;">م. ساهر الهمداني</div>
                        <div style="font-size: 0.7rem; color: #d8b4fe;">المشرف الأكاديمي</div>
                    </div>
                </a>

                <a href="{{ route('quick-login', 2) }}" class="btn btn-outline btn-sm" style="border-color: rgba(56, 189, 248, 0.4); background: rgba(56, 189, 248, 0.08); text-align: right; justify-content: flex-start;">
                    <i class="fa-solid fa-user-graduate" style="color: #38bdf8;"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem;">إلياس جمال الساده</div>
                        <div style="font-size: 0.7rem; color: #7dd3fc;">طالب ومبرمج المشروع</div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection
