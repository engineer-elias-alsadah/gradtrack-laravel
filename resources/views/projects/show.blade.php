@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Breadcrumb & Back -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">
            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">
                <i class="fa-solid fa-home"></i> لوحة التحكم
            </a>
            <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
            <span style="color: var(--primary);">{{ $project->title }}</span>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-right"></i> العودة للرئيسية
        </a>
    </div>

    <!-- Project Header Card -->
    <div class="card" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.9));">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                    <span class="status-badge status-{{ $project->status }}">
                        <i class="fa-solid fa-circle-play"></i> مشروع نشط
                    </span>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                        <i class="fa-solid fa-calendar"></i> العام الدراسي: {{ $project->academic_year }}
                    </span>
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 800; line-height: 1.3;">{{ $project->title }}</h2>
            </div>

            @if($project->github_repository)
            <a href="{{ $project->github_repository }}" target="_blank" class="btn btn-outline" style="border-color: rgba(255,255,255,0.2);">
                <i class="fa-brands fa-github" style="font-size: 1.2rem;"></i> مستودع المشروع على GitHub
            </a>
            @endif
        </div>

        <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7; margin-bottom: 1.75rem;">
            {{ $project->description }}
        </p>

        <!-- Progress Metrics Bar -->
        <div style="background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-weight: 700; font-size: 0.95rem;">مستوى الإنجاز التراكمي المعتمد:</span>
                <span style="font-size: 1.25rem; font-weight: 800; color: #38bdf8;">{{ $project->progress_percentage }}%</span>
            </div>
            <div style="height: 12px; background: rgba(255, 255, 255, 0.08); border-radius: 9999px; overflow: hidden;">
                <div style="height: 100%; width: {{ $project->progress_percentage }}%; background: linear-gradient(90deg, #0ea5e9, #10b981); border-radius: 9999px; transition: width 1s ease;"></div>
            </div>
        </div>

        <!-- Supervisor & Members Bar -->
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            
            <!-- Supervisor -->
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(168, 85, 247, 0.06); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid rgba(168, 85, 247, 0.2);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(168, 85, 247, 0.2); color: #d8b4fe; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #c084fc; font-weight: 700;">المشرف الأكاديمي</div>
                    <div style="font-weight: 800; font-size: 1rem;">{{ $project->supervisor->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $project->supervisor->department }}</div>
                </div>
            </div>

            <!-- Team Members -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem; background: rgba(56, 189, 248, 0.06); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid rgba(56, 189, 248, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.8rem; color: #38bdf8; font-weight: 700;">
                        <i class="fa-solid fa-users"></i> فريق عمل المشروع ({{ $project->members->count() }} طلاب)
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @foreach($project->members as $member)
                    <div style="font-size: 0.9rem; background: rgba(0,0,0,0.25); padding: 0.35rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <strong>{{ $member->name }}</strong>
                        <span style="font-size: 0.75rem; color: {{ $member->pivot->role_in_team === 'leader' ? '#38bdf8' : 'var(--text-muted)' }};">
                            ({{ $member->pivot->role_in_team === 'leader' ? 'قائد الفريق' : 'عضو مطور' }})
                        </span>
                    </div>
                    @endforeach
                </div>

                <!-- Supervisor: Add Member Form -->
                @if($user->isSupervisor() && $project->supervisor_id === $user->id && !empty($availableStudents) && count($availableStudents) > 0)
                <details style="margin-top: 0.5rem; font-size: 0.85rem;">
                    <summary style="cursor: pointer; color: #38bdf8; font-weight: 700;">
                        <i class="fa-solid fa-user-plus"></i> إضافة عضو جديد للفريق
                    </summary>
                    <form action="{{ route('projects.members.add', $project) }}" method="POST" style="margin-top: 0.75rem; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
                        @csrf
                        <select name="student_id" required style="padding: 0.5rem; background: #0f172a; border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.85rem;">
                            <option value="">-- اختر طالباً لإضافته --</option>
                            @foreach($availableStudents as $avail)
                                <option value="{{ $avail->id }}">{{ $avail->name }} ({{ $avail->university_id }})</option>
                            @endforeach
                        </select>

                        <select name="role_in_team" style="padding: 0.5rem; background: #0f172a; border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.85rem;">
                            <option value="member">عضو مطور (Member)</option>
                            <option value="leader">قائد مشترك (Leader)</option>
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm">إضافة</button>
                    </form>
                </details>
                @endif
            </div>

        </div>
    </div>

    <!-- Milestones Section -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-size: 1.35rem; font-weight: 800;">
                    <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> مراحل تسليم المشروع (Milestones)
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.85rem;">
                    مقسمة زمنياً وفق خطة مادة هندسة البرمجيات مع أوزان ومعايير قبول واضحة:
                </p>
            </div>

            <!-- Supervisor: Add Milestone Trigger -->
            @if($user->isSupervisor() && $project->supervisor_id === $user->id)
            <details>
                <summary class="btn btn-outline btn-sm" style="cursor: pointer; border-color: rgba(168, 85, 247, 0.4); color: #d8b4fe;">
                    <i class="fa-solid fa-plus"></i> إضافة مرحلة تسليم جديدة
                </summary>
                <div class="card" style="margin-top: 1rem; border-color: rgba(168, 85, 247, 0.3);">
                    <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; color: #d8b4fe;">إضافة مرحلة جديدة للمشروع</h4>
                    <form action="{{ route('milestones.store', $project) }}" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem;">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem;">عنوان المرحلة:</label>
                            <input type="text" name="title" required placeholder="مثال: المرحلة 5: النشر التجريبي واختبارات الأداء"
                                style="width: 100%; padding: 0.55rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem;">وصف المخرجات المطلوبة:</label>
                            <textarea name="description" rows="2" required placeholder="اشرح ما يجب على الطلاب تسليمه..."
                                style="width: 100%; padding: 0.55rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;"></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem;">الموعد النهائي:</label>
                                <input type="date" name="due_date" required min="{{ date('Y-m-d') }}"
                                    style="width: 100%; padding: 0.55rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem;">الوزن النسبي (%):</label>
                                <input type="number" name="weight_percentage" required min="1" max="100" value="20"
                                    style="width: 100%; padding: 0.55rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm" style="align-self: flex-start; background: linear-gradient(135deg, #a855f7, #6366f1);">
                            حفظ المرحلة
                        </button>
                    </form>
                </div>
            </details>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @foreach($project->milestones as $milestone)
            <div class="card" style="display: grid; grid-template-columns: auto 1fr auto; gap: 1.5rem; align-items: center;">
                
                <!-- Order Number & Icon -->
                <div style="width: 52px; height: 52px; border-radius: var(--radius-sm); background: rgba(255, 255, 255, 0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                    <span style="font-size: 0.7rem; color: var(--text-muted);">مرحلة</span>
                    <strong style="font-size: 1.3rem; color: var(--primary); font-family: 'Outfit';">{{ $milestone->order_num }}</strong>
                </div>

                <!-- Info -->
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.4rem; flex-wrap: wrap;">
                        <h4 style="font-size: 1.15rem; font-weight: 700;">
                            <a href="{{ route('milestones.show', [$project, $milestone]) }}" style="color: var(--text-primary); text-decoration: none;">
                                {{ $milestone->title }}
                            </a>
                        </h4>

                        <span class="status-badge status-{{ $milestone->status }}">
                            @if($milestone->status === 'approved')
                                <i class="fa-solid fa-circle-check"></i> معتمد
                            @elseif($milestone->status === 'submitted')
                                <i class="fa-solid fa-inbox"></i> تم الرفع (بانتظار المراجعة)
                            @elseif($milestone->status === 'needs_revision')
                                <i class="fa-solid fa-triangle-exclamation"></i> مطلوب إعادة تعديل
                            @else
                                <i class="fa-solid fa-clock"></i> بانتظار التسليم
                            @endif
                        </span>

                        @if($milestone->score !== null)
                        <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-weight: 800; font-size: 0.85rem; padding: 0.2rem 0.6rem; border-radius: var(--radius-sm); border: 1px solid rgba(16, 185, 129, 0.3);">
                            الدرجة: {{ $milestone->score }} / {{ $milestone->max_score }}
                        </span>
                        @endif
                    </div>

                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.6rem;">
                        {{ $milestone->description }}
                    </p>

                    <div style="display: flex; gap: 1.5rem; font-size: 0.8rem; color: var(--text-muted); flex-wrap: wrap;">
                        <span><i class="fa-regular fa-calendar-xmark"></i> الموعد النهائي: <strong>{{ $milestone->due_date->format('Y-m-d') }}</strong></span>
                        <span><i class="fa-solid fa-chart-pie"></i> الوزن في التقييم: <strong>{{ $milestone->weight_percentage }}%</strong></span>
                        <span><i class="fa-solid fa-layer-group"></i> عدد التسليمات: <strong>{{ $milestone->submissions->count() }} إصدارات</strong></span>
                    </div>
                </div>

                <!-- Actions -->
                <div>
                    <a href="{{ route('milestones.show', [$project, $milestone]) }}" class="btn btn-outline" style="white-space: nowrap;">
                        تفاصيل المرحلة والتسليم <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>

</div>

@push('styles')
<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: auto 1fr auto"] {
            grid-template-columns: 1fr !important;
        }
        div[style*="grid-template-columns: 1fr 2fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection
