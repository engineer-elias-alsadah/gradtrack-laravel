@extends('layouts.app')

@section('title', 'معمل حالات الحافة - Edge Cases Lab')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Header Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(56, 189, 248, 0.12), rgba(129, 140, 248, 0.08)); border-color: rgba(56, 189, 248, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-shield-halved"></i> معيار تقييم المحاضرة الأولى (شريحة 10 - وزن 10 درجات)
                </div>
                <h2 style="font-size: 1.8rem; font-weight: 800;">معمل معالجة حالات الحافة (Edge Cases Showcase)</h2>
                <p style="color: var(--text-secondary); font-size: 0.95rem; max-width: 800px; margin-top: 0.35rem;">
                    "المسار الطبيعي لا يكفي؛ مهندس البرمجيات يفكر في ما قد يحدث عند الفشل أيضاً". يستعرض هذا المعمل السيناريوهات غير الطبيعية التسع المحددة في المحاضرة وكيف تمت معالجتها برمجياً وهندسياً في منصة <strong>GradTrack</strong>.
                </p>
            </div>

            <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 1rem 1.5rem; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--text-muted);">تغطية حالات الحافة في المشروع:</div>
                <div style="font-size: 1.6rem; font-weight: 900; color: #34d399; font-family: 'Outfit';">
                    9 / 9 حالات مكتملة
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Categories Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.75rem;">
        
        <!-- Category 1: Data Edge Cases -->
        <div class="card" style="border-top: 4px solid #38bdf8;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(56, 189, 248, 0.15); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800;">1. حالات حافة البيانات (Data)</h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">الحقول الفارغة، الروابط المشوهة، النصوص المفرطة</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Case 1 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">أ) الحقل الفارغ أو المسافات البيضاء</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> معالجة تلقائية</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: إرسال تسليم خاوٍ من الشرح أو يحتوي على مسافات فقط.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #7dd3fc; direction: ltr;">
                        'notes' => ['required', 'string', 'min:10', 'max:5000']
                    </div>
                </div>

                <!-- Case 2 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ب) رابط GitHub غير صالح أو مزيف</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> Regex Guard</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: إدخال رابط عشوائي يسبب خطأ 404 للمشرف عند الفحص.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #7dd3fc; direction: ltr;">
                        'github_url' => ['required', 'url', 'regex:/^https:\/\/github\.com\/.+/i']
                    </div>
                </div>

                <!-- Case 3 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ج) درجات خارج النطاق الأكاديمي</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> Range Guard</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: إدخال درجات سالبة أو تفوق 100 بالخطأ من المشرف.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #7dd3fc; direction: ltr;">
                        'score' => ['required', 'integer', 'min:0', 'max:100']
                    </div>
                </div>
            </div>
        </div>

        <!-- Category 2: Authorization Edge Cases -->
        <div class="card" style="border-top: 4px solid #a855f7;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(168, 85, 247, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800;">2. حالات حافة الصلاحيات (Authorization)</h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">عزل بيانات المشاريع ومنع التلاعب بالتقييم</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Case 1 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">أ) تسليم مرحلة لمشروع لا ينتمي للطالب</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> 403 Forbidden</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: محاولة طالب رفع تسليمات نيابة عن فريق آخر.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #d8b4fe; direction: ltr;">
                        public function authorize() { return $milestone->project->isMember($user->id); }
                    </div>
                </div>

                <!-- Case 2 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ب) محاولة الطالب تقييم نفسه</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> Role Policy</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: إرسال طلب POST من طالب لمحاكاة تقييم المشرف.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #d8b4fe; direction: ltr;">
                        if (! $user->isSupervisor() && ! $user->isAdmin()) abort(403);
                    </div>
                </div>

                <!-- Case 3 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ج) التعديل على مرحلة معتمدة ومغلقة</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> Immutability</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: تغيير كود أو تقرير بعد اعتماده من المشرف الأكاديمي.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #d8b4fe; direction: ltr;">
                        if ($milestone->isApproved()) return back()->with('error', 'المرحلة معتمدة ومغلقة');
                    </div>
                </div>
            </div>
        </div>

        <!-- Category 3: Time & Deadlines -->
        <div class="card" style="border-top: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(245, 158, 11, 0.15); color: #fbbf24; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800;">3. حالات حافة الوقت والتاريخ (Time)</h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">التسليم بعد الموعد، التواريخ الماضية</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Case 1 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">أ) التسليم بعد انتهاء الـ Deadline</strong>
                        <span class="status-badge status-needs_revision" style="font-size: 0.7rem;"><i class="fa-solid fa-flag"></i> Late Flagged</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المعالجة: لا يتم رفض التسليم لتجنب حرمان الطالب، بل يتم توثيقه كـ `is_late = true` مع تنبيه أحمر للمشرف.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #fde68a; direction: ltr;">
                        $isLate = Carbon::now()->isAfter($milestone->due_date->endOfDay());
                    </div>
                </div>

                <!-- Case 2 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ب) انتهاء الجلسة أثناء كتابة التقرير</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> Session Shield</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المعالجة: ضبط `SESSION_LIFETIME=120` وتخزين المسودة تلقائياً في LocalStorage للنماذج الطويلة.
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #fde68a; direction: ltr;">
                        localStorage.setItem('milestone_notes_draft', input.value);
                    </div>
                </div>
            </div>
        </div>

        <!-- Category 4: System & Concurrency -->
        <div class="card" style="border-top: 4px solid #10b981;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(16, 185, 129, 0.15); color: #34d399; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800;">4. حالات حافة النظام والتزامن (System)</h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">التسليم المزدوج، والعمليات الذرية DB Transactions</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Case 1 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">أ) النقر السريع المزدوج (Double Click)</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> JS Button Lock</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المخاطرة: إنشاء تسليمين متكررين في نفس اللحظة (Race condition).
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #a7f3d0; direction: ltr;">
                        btn.disabled = true; btn.innerText = 'جاري الإرسال...';
                    </div>
                </div>

                <!-- Case 2 -->
                <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <strong style="color: white; font-size: 0.9rem;">ب) تعطل الحفظ أثناء المعالجة (Atomic Transactions)</strong>
                        <span class="status-badge status-approved" style="font-size: 0.7rem;"><i class="fa-solid fa-check"></i> DB Transaction</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        المعالجة: تحديث حالة المرحلة وحفظ المراجعة في معاملة ذرية تمنع عدم الاتساق (Data inconsistency).
                    </p>
                    <div style="background: #0f172a; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: #a7f3d0; direction: ltr;">
                        DB::transaction(function() { Review::create(); $milestone->update(); });
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
