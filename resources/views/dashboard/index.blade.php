@extends('layouts.app')

@section('title', 'لوحة التحكم والمتابعة')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Top Hero Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.12), rgba(99, 102, 241, 0.08)); border-color: rgba(56, 189, 248, 0.25); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <span class="role-badge {{ $user->role }}">
                    <i class="fa-solid {{ $user->role === 'supervisor' ? 'fa-user-tie' : 'fa-user-graduate' }}"></i>
                    {{ $user->role === 'supervisor' ? 'لوحة تحكم المشرف الأكاديمي' : 'لوحة تحكم الطالب' }}
                </span>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    <i class="fa-regular fa-clock"></i> العام الأكاديمي: 2026 / 2027
                </span>
            </div>
            <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 0.5rem;">
                أهلاً بك، {{ $user->name }}
            </h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; max-width: 650px;">
                مرحباً بك في منصة GradTrack لمتابعة مراحل مشاريع التخرج وفق منهجية هندسة البرمجيات والتكامل المستمر مع مستودعات GitHub.
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            @if($user->isSupervisor())
            <a href="{{ route('projects.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #a855f7, #6366f1);">
                <i class="fa-solid fa-folder-plus"></i> تأسيس مشروع جديد
            </a>
            @endif
            <a href="{{ route('edge-cases.index') }}" class="btn btn-outline" style="border-color: rgba(56, 189, 248, 0.4); color: #38bdf8;">
                <i class="fa-solid fa-microscope"></i> معمل حالات الحافة (10 درجات)
            </a>
            @if($projects->isNotEmpty())
            <a href="{{ route('projects.show', $projects->first()) }}" class="btn btn-primary">
                <i class="fa-solid fa-folder-open"></i> استعراض تفاصيل المشروع
            </a>
            @endif
        </div>
    </div>

    <!-- 4 Key Metrics Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
        
        <!-- Card 1 -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-sm); background: rgba(56, 189, 248, 0.15); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">المشاريع النشطة</div>
                <div style="font-size: 1.75rem; font-weight: 800;">{{ $projects->count() }}</div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-sm); background: rgba(16, 185, 129, 0.15); color: #34d399; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">المراحل المعتمدة</div>
                <div style="font-size: 1.75rem; font-weight: 800;">
                    {{ $projects->flatMap->milestones->where('status', 'approved')->count() }} / {{ $projects->flatMap->milestones->count() }}
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-sm); background: rgba(245, 158, 11, 0.15); color: #fbbf24; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">بانتظار المراجعة والتعديل</div>
                <div style="font-size: 1.75rem; font-weight: 800;">
                    {{ $projects->flatMap->milestones->whereIn('status', ['submitted', 'needs_revision'])->count() }}
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-sm); background: rgba(168, 85, 247, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-percent"></i>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">متوسط نسبة الإنجاز</div>
                <div style="font-size: 1.75rem; font-weight: 800;">
                    {{ $projects->isEmpty() ? 0 : round($projects->avg('progress_percentage')) }}%
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- Left: Project & Milestones -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            @forelse($projects as $project)
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <span class="status-badge status-{{ $project->status }}">
                                <i class="fa-solid fa-circle-dot"></i> قيد التطوير الهندسي
                            </span>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">
                                <i class="fa-solid fa-hashtag"></i> كود المشروع: PROJ-{{ $project->id }}
                            </span>
                        </div>
                        <h3 style="font-size: 1.35rem; font-weight: 800;">{{ $project->title }}</h3>
                    </div>

                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline btn-sm">
                        عرض التفاصيل <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.25rem;">
                    {{ $project->description }}
                </p>

                <!-- Progress Bar -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                        <span style="font-weight: 700; color: var(--text-secondary);">نسبة الإنجاز التراكمية للمشروع:</span>
                        <span style="font-weight: 800; color: #38bdf8;">{{ $project->progress_percentage }}%</span>
                    </div>
                    <div style="height: 10px; background: rgba(255, 255, 255, 0.08); border-radius: 9999px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $project->progress_percentage }}%; background: linear-gradient(90deg, #0ea5e9, #10b981); border-radius: 9999px; transition: width 0.8s ease;"></div>
                    </div>
                </div>

                <!-- Supervisor & Team Info -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.85rem; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-user-tie" style="color: #c084fc;"></i>
                        <span style="color: var(--text-muted);">المشرف الأكاديمي:</span>
                        <strong style="color: #d8b4fe;">{{ $project->supervisor->name ?? 'غير محدد' }}</strong>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-users" style="color: #38bdf8;"></i>
                        <span style="color: var(--text-muted);">أعضاء الفريق:</span>
                        <span>{{ $project->members->pluck('name')->join('، ') }}</span>
                    </div>

                    @if($project->github_repository)
                    <a href="{{ $project->github_repository }}" target="_blank" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fa-brands fa-github"></i> مستودع الكود
                    </a>
                    @endif
                </div>

                <!-- Milestones Quick List inside Project -->
                <div style="margin-top: 1.5rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.75rem;">
                        <i class="fa-solid fa-bars-progress"></i> مراحل المشروع (Milestones):
                    </h4>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($project->milestones as $milestone)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1rem; background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-sm); transition: var(--transition);">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                    {{ $milestone->order_num }}
                                </div>
                                <div>
                                    <a href="{{ route('milestones.show', [$project, $milestone]) }}" style="font-weight: 700; color: var(--text-primary); text-decoration: none; font-size: 0.95rem;">
                                        {{ $milestone->title }}
                                    </a>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; gap: 1rem; margin-top: 0.2rem;">
                                        <span><i class="fa-regular fa-calendar"></i> الموعد: {{ $milestone->due_date->format('Y-m-d') }}</span>
                                        <span><i class="fa-solid fa-weight-scale"></i> الوزن: {{ $milestone->weight_percentage }}%</span>
                                        @if($milestone->score !== null)
                                            <span style="color: #34d399; font-weight: 700;"><i class="fa-solid fa-star"></i> الدرجة: {{ $milestone->score }}/100</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span class="status-badge status-{{ $milestone->status }}">
                                    @if($milestone->status === 'approved')
                                        <i class="fa-solid fa-check"></i> معتمد
                                    @elseif($milestone->status === 'submitted')
                                        <i class="fa-solid fa-inbox"></i> بانتظار المراجعة
                                    @elseif($milestone->status === 'needs_revision')
                                        <i class="fa-solid fa-rotate-left"></i> مطلوب تعديل
                                    @else
                                        <i class="fa-solid fa-hourglass-start"></i> لم يُسلم بعد
                                    @endif
                                </span>

                                <a href="{{ route('milestones.show', [$project, $milestone]) }}" class="btn btn-outline btn-sm">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            @empty
            <div class="card" style="text-align: center; padding: 3rem;">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p>لا توجد مشاريع مسجلة حالياً.</p>
            </div>
            @endforelse
        </div>

        <!-- Right: Activity Feed & Defense Guidelines -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            
            <!-- Defense Criteria Checklist (100 Marks Rubric) -->
            <div class="card" style="border-color: rgba(56, 189, 248, 0.3); background: rgba(14, 165, 233, 0.04);">
                <h4 style="font-size: 1rem; font-weight: 800; color: #38bdf8; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-award"></i> مخرجات التقييم (100 درجة):
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.85rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> وضوح الفكرة وهدفها:</span>
                        <strong>10 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> جودة Mini-SRS:</span>
                        <strong>20 درجة</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> قصص المستخدمين (Given/When/Then):</span>
                        <strong>15 درجة</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> حالات الحافة (Edge Cases):</span>
                        <strong>10 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> إدارة المهام و GitHub Issues:</span>
                        <strong>10 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> سير عمل Git Workflow:</span>
                        <strong>10 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> مراجعات الـ Pull Requests:</span>
                        <strong>10 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> شفافية الذكاء الاصطناعي (AI Log):</span>
                        <strong>5 درجات</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><i class="fa-solid fa-check-circle" style="color: #34d399;"></i> تنظيم العمل الجماعي والفهم:</span>
                        <strong>10 درجات</strong>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Audit Log -->
            <div class="card">
                <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #818cf8;"></i> سجل العمليات الأخير (Audit Log):
                </h4>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($activityLogs as $log)
                    <div style="display: flex; gap: 0.75rem; font-size: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 0.75rem;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #38bdf8; margin-top: 6px; flex-shrink: 0;"></div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary);">{{ $log->action }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.8rem; margin: 0.2rem 0;">{{ $log->details }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">
                                {{ $log->user->name }} • {{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="color: var(--text-muted); font-size: 0.85rem;">لا توجد حركات مسجلة.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@push('styles')
<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 2fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection
