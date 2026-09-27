@extends('layouts.app')

@section('title', 'إنشاء مشروع تخرج جديد')

@section('content')
<div style="max-width: 800px; margin: 1.5rem auto;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">
            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">
                <i class="fa-solid fa-home"></i> لوحة التحكم
            </a>
            <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
            <span style="color: var(--primary);">تأسيس مشروع تخرج جديد</span>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-right"></i> إلغاء والعودة
        </a>
    </div>

    <div class="card">
        <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: rgba(168, 85, 247, 0.2); color: #d8b4fe; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fa-solid fa-folder-plus"></i>
            </div>
            <div>
                <h2 style="font-size: 1.45rem; font-weight: 800;">تأسيس وتعيين مشروع تخرج جديد</h2>
                <p style="color: var(--text-secondary); font-size: 0.85rem;">
                    بصفتك مشرفاً أكاديمياً، يمكنك إنشاء وتأطير المشروع واختيار قائد الفريق من الطلاب المسجلين
                </p>
            </div>
        </div>

        @if($errors->any())
        <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); border-radius: var(--radius-sm); padding: 0.85rem; margin-bottom: 1.25rem; font-size: 0.85rem; color: #fda4af;">
            <strong style="display: block; margin-bottom: 0.35rem;"><i class="fa-solid fa-triangle-exclamation"></i> يرجى مراجعة البيانات:</strong>
            <ul style="padding-right: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf

            <!-- Project Title -->
            <div>
                <label for="title" style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.35rem;">
                    عنوان مشروع التخرج <span style="color: #f43f5e;">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="مثال: نظام ذكي لإدارة الرعاية الصحية..."
                    style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
            </div>

            <!-- Description -->
            <div>
                <label for="description" style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.35rem;">
                    الوصف المعماري والهدف من المشروع <span style="color: #f43f5e;">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required placeholder="اشرح المشكلة والحل المقترح ونطاق العمل الهندسي..."
                    style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem; resize: vertical;">{{ old('description') }}</textarea>
            </div>

            <!-- Academic Year & GitHub Repo Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="academic_year" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">
                        العام الجامعي <span style="color: #f43f5e;">*</span>
                    </label>
                    <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', '2026/2027') }}" required
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                </div>

                <div>
                    <label for="github_repository" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">
                        مستودع GitHub (اختياري)
                    </label>
                    <input type="url" name="github_repository" id="github_repository" value="{{ old('github_repository') }}" placeholder="https://github.com/org/repo"
                        style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem; direction: ltr;">
                </div>
            </div>

            <!-- Team Leader Selector -->
            <div>
                <label for="leader_student_id" style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.35rem;">
                    تعيين قائد فريق المشروع (Student Lead) <span style="color: #f43f5e;">*</span>
                </label>
                <select name="leader_student_id" id="leader_student_id" required
                    style="width: 100%; padding: 0.75rem; background: #0f172a; border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.95rem;">
                    <option value="">-- اختر طالباً مسجلاً ليكون قائد المشروع --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('leader_student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} (الرقم: {{ $student->university_id }} - {{ $student->email }})
                        </option>
                    @endforeach
                </select>
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">
                    * يمكنك إضافة بقية أعضاء الفريق من صفحة تفاصيل المشروع لاحقاً.
                </span>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.85rem; font-size: 1rem; margin-top: 0.5rem; background: linear-gradient(135deg, #a855f7, #6366f1);">
                <i class="fa-solid fa-check"></i> حفظ وإنشاء المشروع
            </button>
        </form>
    </div>
</div>
@endsection
