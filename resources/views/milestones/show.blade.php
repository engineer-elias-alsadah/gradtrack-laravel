@extends('layouts.app')

@section('title', $milestone->title)

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Breadcrumbs -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">
            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">
                <i class="fa-solid fa-home"></i> لوحة التحكم
            </a>
            <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
            <a href="{{ route('projects.show', $project) }}" style="color: var(--text-secondary); text-decoration: none;">
                {{ $project->title }}
            </a>
            <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
            <span style="color: var(--primary);">{{ $milestone->title }}</span>
        </div>

        <a href="{{ route('projects.show', $project) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-right"></i> العودة لقائمة المراحل
        </a>
    </div>

    <!-- Milestone Header Details Card -->
    <div class="card" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.95));">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #38bdf8; background: rgba(56, 189, 248, 0.1); padding: 0.2rem 0.6rem; border-radius: var(--radius-sm); border: 1px solid rgba(56, 189, 248, 0.2);">
                        المرحلة رقم {{ $milestone->order_num }}
                    </span>
                    <span class="status-badge status-{{ $milestone->status }}">
                        @if($milestone->status === 'approved')
                            <i class="fa-solid fa-circle-check"></i> معتمدة ومغلقة
                        @elseif($milestone->status === 'submitted')
                            <i class="fa-solid fa-hourglass-half"></i> بانتظار مراجعة المشرف
                        @elseif($milestone->status === 'needs_revision')
                            <i class="fa-solid fa-arrows-rotate"></i> مفتوحة لإعادة التعديل
                        @else
                            <i class="fa-solid fa-clock"></i> لم تُسلّم بعد
                        @endif
                    </span>
                </div>

                <h2 style="font-size: 1.7rem; font-weight: 800;">{{ $milestone->title }}</h2>
            </div>

            <!-- Score Badge if available -->
            @if($milestone->score !== null)
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); padding: 0.85rem 1.5rem; text-align: center;">
                <div style="font-size: 0.75rem; color: #34d399; font-weight: 700;">الدرجة المرصودة</div>
                <div style="font-size: 1.8rem; font-weight: 900; color: #34d399; font-family: 'Outfit';">
                    {{ $milestone->score }} <span style="font-size: 1rem; color: var(--text-muted);">/ 100</span>
                </div>
            </div>
            @endif
        </div>

        <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7; margin-bottom: 1.5rem;">
            {{ $milestone->description }}
        </p>

        <!-- Stats Bar -->
        <div style="display: flex; gap: 2rem; flex-wrap: wrap; padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.9rem;">
            <div>
                <span style="color: var(--text-muted);"><i class="fa-regular fa-calendar-xmark"></i> الموعد النهائي:</span>
                <strong style="color: {{ $milestone->isOverdue() ? '#f43f5e' : 'white' }};">
                    {{ $milestone->due_date->format('Y-m-d') }}
                    @if($milestone->isOverdue())
                        <span style="font-size: 0.75rem; color: #f43f5e;">(منتهي)</span>
                    @endif
                </strong>
            </div>

            <div>
                <span style="color: var(--text-muted);"><i class="fa-solid fa-scale-balanced"></i> الوزن النسبي:</span>
                <strong>{{ $milestone->weight_percentage }}% من التقييم العام</strong>
            </div>

            <div>
                <span style="color: var(--text-muted);"><i class="fa-solid fa-code-commit"></i> عدد التسليمات:</span>
                <strong>{{ $milestone->submissions->count() }} تسليم</strong>
            </div>
        </div>
    </div>

    <!-- Main Section: Submissions History & Actions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- Left: Submissions Timeline -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #38bdf8;"></i> سجل مخرجات التسليم والمراجعات (Submissions History)
            </h3>

            @forelse($milestone->submissions as $submission)
            <div class="card" style="border-right: 4px solid {{ $submission->latestReview && $submission->latestReview->decision === 'approved' ? '#10b981' : ($submission->latestReview && $submission->latestReview->decision === 'needs_revision' ? '#f59e0b' : '#38bdf8') }};">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 0.85rem; font-weight: 800; background: rgba(255,255,255,0.08); padding: 0.25rem 0.65rem; border-radius: var(--radius-sm); font-family: 'Outfit';">
                            Version {{ $submission->version }}
                        </span>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                            رُفعت بواسطة: <strong>{{ $submission->student->name }}</strong>
                        </div>
                    </div>

                    <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.75rem;">
                        <span><i class="fa-regular fa-clock"></i> {{ $submission->submitted_at->format('Y-m-d H:i') }}</span>
                        @if($submission->is_late)
                            <span style="color: #f43f5e; font-weight: 700; background: rgba(244,63,94,0.1); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid rgba(244,63,94,0.2);">
                                <i class="fa-solid fa-triangle-exclamation"></i> تسليم متأخر
                            </span>
                        @endif
                    </div>
                </div>

                <!-- GitHub link & Notes -->
                <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 1rem;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                        <i class="fa-brands fa-github"></i> مستودع أو رابط الكود المصدري على GitHub:
                    </div>
                    <a href="{{ $submission->github_url }}" target="_blank" style="color: #38bdf8; word-break: break-all; font-family: 'Outfit', sans-serif; font-size: 0.95rem; text-decoration: none;">
                        {{ $submission->github_url }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                    </a>

                    <div style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                        <i class="fa-solid fa-align-right"></i> تقرير وملاحظات التسليم:
                    </div>
                    <p style="color: var(--text-primary); font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">
                        {{ $submission->notes }}
                    </p>
                </div>

                <!-- Supervisor Review Card inside submission -->
                @if($submission->latestReview)
                <div style="background: rgba(168, 85, 247, 0.08); border: 1px solid rgba(168, 85, 247, 0.25); border-radius: var(--radius-sm); padding: 1rem; margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-user-check" style="color: #c084fc;"></i>
                            <strong style="color: #d8b4fe;">مراجعة وتقييم المشرف: {{ $submission->latestReview->reviewer->name }}</strong>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-weight: 800; font-size: 1.1rem; color: #34d399; font-family: 'Outfit';">
                                {{ $submission->latestReview->score }}/100
                            </span>
                            <span class="status-badge status-{{ $submission->latestReview->decision }}">
                                {{ $submission->latestReview->decision === 'approved' ? 'معتمد' : ($submission->latestReview->decision === 'needs_revision' ? 'يحتاج تعديل' : 'مرفوض') }}
                            </span>
                        </div>
                    </div>
                    <div style="color: #e2e8f0; font-size: 0.9rem; line-height: 1.6;">
                        <i class="fa-solid fa-quote-right" style="color: #c084fc; margin-left: 0.35rem;"></i>
                        {{ $submission->latestReview->feedback }}
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; text-align: left;">
                        تاريخ المراجعة: {{ $submission->latestReview->reviewed_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                @else
                <div style="font-size: 0.85rem; color: #fbbf24; background: rgba(245, 158, 11, 0.08); padding: 0.6rem 1rem; border-radius: var(--radius-sm); border: 1px solid rgba(245, 158, 11, 0.2); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-clock-rotate-left"></i> هذا الإصدار بانتظار مراجعة وتقييم المشرف الأكاديمي.
                </div>
                @endif

                <!-- Supervisor Review Form Trigger (Only shown if supervisor and this submission hasn't been approved yet) -->
                @if($user->isSupervisor() && (!$submission->latestReview || $submission->latestReview->decision !== 'approved'))
                <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <details>
                        <summary style="cursor: pointer; color: #c084fc; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-pen-to-square"></i> تسجيل تقييم وملاحظات المشرف لهذا الإصدار (v{{ $submission->version }})
                        </summary>

                        <form action="{{ route('reviews.store', [$project, $milestone, $submission]) }}" method="POST" style="margin-top: 1rem; display: flex; flex-direction: column; gap: 1rem;">
                            @csrf

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">الدرجة المستحقة (من 100):</label>
                                    <input type="number" name="score" min="0" max="100" value="{{ old('score', 95) }}" required
                                        style="width: 100%; padding: 0.6rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">قرار المراجعة الهندسية:</label>
                                    <select name="decision" required
                                        style="width: 100%; padding: 0.6rem; background: #0f172a; border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit;">
                                        <option value="approved">اعتماد المخرجات (Approved)</option>
                                        <option value="needs_revision">طلب إعادة تعديل وتحديث (Needs Revision)</option>
                                        <option value="rejected">رفض التسليم (Rejected)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">التغذية الراجعة والملاحظات الأكاديمية (Feedback):</label>
                                <textarea name="feedback" rows="3" required placeholder="اكتب الملاحظات التوجيهية للطلاب..."
                                    style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; resize: vertical;">تمت مراجعة مخرجات المرحلة ومطابقتها لمعايير هندسة البرمجيات والـ SRS بنجاح.</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" style="align-self: flex-start; background: linear-gradient(135deg, #a855f7, #6366f1);">
                                <i class="fa-solid fa-check-double"></i> اعتماد وحفظ التقييم
                            </button>
                        </form>
                    </details>
                </div>
                @endif

            </div>
            @empty
            <div class="card" style="text-align: center; padding: 3rem;">
                <i class="fa-solid fa-inbox" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-secondary);">لم يتم رفع أي تسليم لهذه المرحلة حتى الآن.</p>
            </div>
            @endforelse
        </div>

        <!-- Right: Action Form for Student Submission or Milestone Rules -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Student Submission Form Card -->
            @if($milestone->canBeSubmitted())
            <div class="card" style="border-color: rgba(56, 189, 248, 0.3); background: rgba(14, 165, 233, 0.03);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <i class="fa-solid fa-upload" style="color: #38bdf8; font-size: 1.25rem;"></i>
                    <h4 style="font-size: 1.15rem; font-weight: 800; color: #38bdf8;">
                        {{ $milestone->submissions->isEmpty() ? 'رفع تسليم المرحلة' : 'رفع إصدار معدل (v' . ($milestone->submissions->max('version') + 1) . ')' }}
                    </h4>
                </div>

                <form id="submissionForm" action="{{ route('submissions.store', [$project, $milestone]) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Edge Case Validation Feedback -->
                    @if ($errors->any())
                    <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); border-radius: var(--radius-sm); padding: 0.75rem; margin-bottom: 1rem; font-size: 0.85rem; color: #fda4af;">
                        <strong style="display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-triangle-exclamation"></i> تنبيهات التحقق من البيانات:</strong>
                        <ul style="padding-right: 1.25rem; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- GitHub URL -->
                    <div style="margin-bottom: 1rem;">
                        <label for="github_url" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">
                            رابط مستودع أو فرع GitHub <span style="color: #f43f5e;">*</span>
                        </label>
                        <input type="url" name="github_url" id="github_url" value="{{ old('github_url', 'https://github.com/elias-alsada/gradtrack-laravel') }}" required
                            placeholder="https://github.com/username/repository"
                            style="width: 100%; padding: 0.65rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: 'Outfit', inherit; font-size: 0.9rem; direction: ltr;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">
                            <i class="fa-solid fa-shield"></i> معالجة حالة الحافة: يتم التحقق تلقائياً من صحة نطاق github.com.
                        </span>
                    </div>

                    <!-- Notes -->
                    <div style="margin-bottom: 1rem;">
                        <label for="notes" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">
                            تقرير المخرجات والملاحظات <span style="color: #f43f5e;">*</span>
                        </label>
                        <textarea name="notes" id="notes" rows="4" required placeholder="اشرح ما تم إنجازه في هذه المرحلة بالتفصيل..."
                            style="width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: white; font-family: inherit; font-size: 0.9rem; resize: vertical;">{{ old('notes', 'تم استكمال كافة المتطلبات المحددة واختبار حالات الحافة والتأكد من نجاح الـ CI في GitHub Actions.') }}</textarea>
                    </div>

                    <!-- Attachment (Optional) -->
                    <div style="margin-bottom: 1.25rem;">
                        <label for="attachment" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">
                            ملف مرفق (اختياري - PDF / ZIP)
                        </label>
                        <input type="file" name="attachment" id="attachment"
                            style="width: 100%; font-size: 0.85rem; color: var(--text-secondary);">
                    </div>

                    <!-- Submit Button with Double-Click Protection -->
                    <button type="submit" id="submitBtn" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                        <i class="fa-solid fa-paper-plane"></i> إرسال التسليم للمشرف
                    </button>
                    
                    <span id="doubleClickNotice" style="display: none; font-size: 0.75rem; color: #38bdf8; text-align: center; margin-top: 0.5rem;">
                        <i class="fa-solid fa-spinner fa-spin"></i> جاري إرسال التسليم وحمايته من التكرار...
                    </span>
                </form>
            </div>
            @else
            <div class="card" style="text-align: center; border-color: rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.05); padding: 2rem 1.5rem;">
                <i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: #34d399; margin-bottom: 0.75rem;"></i>
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #34d399; margin-bottom: 0.5rem;">هذه المرحلة معتمدة نهائياً</h4>
                <p style="color: var(--text-secondary); font-size: 0.85rem;">
                    تم اعتماد مخرجات هذه المرحلة من قِبل المشرف الأكاديمي ولا يُسمح برفع تسليمات جديدة حفاظاً على سلامة التقييم.
                </p>
            </div>
            @endif

            <!-- SE Standards Box -->
            <div class="card">
                <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> معايير الجودة والتسليم:
                </h4>
                <ul style="font-size: 0.85rem; color: var(--text-secondary); padding-right: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li>التأكد من خلو الفرع من أخطاء الـ Tests قبل رفع رابط الـ PR.</li>
                    <li>كتابة ملخص واضح للتغييرات البرمجية في تقرير التسليم.</li>
                    <li>في حال طلب التعديل، قم بتصحيح المطلوب في نفس الفرع ثم أعد التسليم كإصدار جديد.</li>
                </ul>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    // Edge Case: Prevent double submission on fast double-clicks
    const submissionForm = document.getElementById('submissionForm');
    const submitBtn = document.getElementById('submitBtn');
    const doubleClickNotice = document.getElementById('doubleClickNotice');

    if (submissionForm && submitBtn) {
        submissionForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
            if (doubleClickNotice) {
                doubleClickNotice.style.display = 'block';
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 850px) {
        div[style*="grid-template-columns: 2fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection
