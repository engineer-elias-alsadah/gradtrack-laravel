@extends('layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div style="max-width: 650px; margin: 1.5rem auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); background: linear-gradient(135deg, #0ea5e9, #6366f1); display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 0.75rem;">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800;">تسجيل حساب جديد في منصة GradTrack</h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">
                انضم للمنصة كطالب لمتابعة وتسليم مشروعك أو كمشرف أكاديمي لإدارة المشاريع وتقييم المراحل
            </p>
        </div>

        @if($errors->any())
        <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); border-radius: var(--radius-sm); padding: 0.85rem; margin-bottom: 1.25rem; font-size: 0.85rem; color: #fda4af;">
            <strong style="display: block; margin-bottom: 0.35rem;"><i class="fa-solid fa-triangle-exclamation"></i> يرجى تصحيح الأخطاء التالية:</strong>
            <ul style="padding-right: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.1rem;">
            @csrf

            <!-- Role Selector -->
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem;">نوع الحساب الأكاديمي <span style="color: #f43f5e;">*</span></label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); padding: 0.85rem; border-radius: var(--radius-sm); cursor: pointer;">
                        <input type="radio" name="role" value="student" {{ old('role', 'student') === 'student' ? 'checked' : '' }} required>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #7dd3fc;"><i class="fa-solid fa-user-graduate"></i> طالب</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">تسليم مراحل ومشاريع التخرج</span>
                        </div>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); padding: 0.85rem; border-radius: var(--radius-sm); cursor: pointer;">
                        <input type="radio" name="role" value="supervisor" {{ old('role') === 'supervisor' ? 'checked' : '' }}>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #d8b4fe;"><i class="fa-solid fa-user-tie"></i> مشرف أكاديمي</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">إشراف وتقييم واعتماد المراحل</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label for="name" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">الاسم الكامل <span style="color: #f43f5e;">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="الاسم الثلاثي أو الرباعي"
                    style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
            </div>

            <!-- Email & University ID Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="email" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">البريد الإلكتروني <span style="color: #f43f5e;">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="user@university.edu"
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>

                <div>
                    <label for="university_id" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">الرقم الأكاديمي / الجامعي <span style="color: #f43f5e;">*</span></label>
                    <input type="text" name="university_id" id="university_id" value="{{ old('university_id') }}" required placeholder="مثال: 44102981"
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>

            <!-- Department & Phone Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="department" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">القسم الأكاديمي <span style="color: #f43f5e;">*</span></label>
                    <input type="text" name="department" id="department" value="{{ old('department', 'هندسة البرمجيات') }}" required
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>

                <div>
                    <label for="phone" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">رقم الهاتف (اختياري)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+967..."
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>

            <!-- Password & Confirmation Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="password" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">كلمة المرور <span style="color: #f43f5e;">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="6 خانات على الأقل"
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>

                <div>
                    <label for="password_confirmation" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">تأكيد كلمة المرور <span style="color: #f43f5e;">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="أعد إدخال كلمة المرور"
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.85rem; font-size: 1rem; margin-top: 0.5rem;">
                <i class="fa-solid fa-user-check"></i> إتمام التسجيل والدخول للمنصة
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-secondary);">
            لديك حساب مسجل بالفعل؟
            <a href="{{ route('login') }}" style="color: #38bdf8; font-weight: 700; text-decoration: none;">تسجيل الدخول هنا</a>
        </div>
    </div>
</div>
@endsection
