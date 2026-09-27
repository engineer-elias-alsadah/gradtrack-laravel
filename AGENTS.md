# AGENTS.md - دليل الوكلاء الأذكياء وتعليمات التطوير الهندسية
## مشروع منصة GradTrack لمشاريع التخرج وهندسة البرمجيات
### إشراف: المهندس ساهر الهمداني | إعداد الطالب: إلياس جمال الساده

هذا المستند يُحدد القواعد الهندسية والمعايير البرمجية لتوجيه نماذج الذكاء الاصطناعي (AI Coding Assistants / Agents) والمطورين عند المساهمة في هذا المستودع.

---

## 1. الهوية والمعمارية (Architecture & Tech Stack)
* **الإطار البرمجي (Framework):** Laravel 11/12 (PHP 8.3+)
* **قاعدة البيانات:** SQLite (بيئة التطوير والاختبار و CI) / MySQL (بيئة الإنتاج)
* **المعمارية الأساسية:** MVC Pattern (Model-View-Controller) مع FormRequests و Policies.
* **الواجهة الأمامية (Frontend):** Laravel Blade مع CSS مخصص نظيف وعصري، يدعم الـ RTL للغة العربية.
* **أدوات الجودة والاختبار:** PHPUnit / Pest للاختبارات، و GitHub Actions للـ CI/CD.

---

## 2. هيكلية البيانات ونماذج الـ Eloquent (Data Models)
1. **User:** يدير الحسابات، وله دور محدد: `student`، أو `supervisor`.
2. **Project:** يمثل مشروع التخرج وله مشرف (`supervisor_id`) وأعضاء من الطلاب.
3. **ProjectMember:** جدول وسيط يربط الطلاب بالمشروع مع تحديد دورهم (`leader` أو `member`).
4. **Milestone:** يمثل مرحلة تسليم (مثل: SRS, System Design, Prototype, Final Defense) مع تاريخ استحقاق (`due_date`) ووزن مئوي (`weight_percentage`) وحالة (`status`).
5. **Submission:** تسليمات الطلاب للمرحلة (ملاحظات، رابط GitHub، ملف مرفق، رقم الإصدار `version`).
6. **Review:** مراجعة المشرف للتسليم (درجة، تغذية راجعة، قرار الاعتماد: `approved`, `needs_revision`, `rejected`).
7. **ActivityLog:** سجل حركات وتفاعلات المشروع لتوثيق العمل وسهولة التدقيق.

---

## 3. قواعد كتابة الكود (Coding Conventions & Rules)
* **معايير PHP:** الالتزام الصارم بـ PSR-12 وتنسيق Laravel Pint.
* **التحقق من البيانات (Validation):**
  * لا تضع منطق التحقق داخل الـ Controllers مباشرة؛ استخدم دائماً `FormRequest` مخصص (مثل `StoreSubmissionRequest`, `StoreReviewRequest`).
  * تحقق دائماً من سلامة الروابط (GitHub URLs) وحالات الحافة (الحقول الفارغة، تكرار البيانات).
* **الصلاحيات والأمان (Authorization & Policies):**
  * أي عملية حساسة (رفع تسليم، كتابة مراجعة، تعديل مشروع) يجب حمايتها باستخدام `Policy` أو `Gate`.
  * الطالب لا يملك صلاحية تقييم نفسه أو استعراض تسليمات مشاريع أخرى.
* **التحكم بالتكرار والتعامل المالي/الحرج:**
  * استخدم `DB::transaction` عند تحديث حالة المرحلة بالتزامن مع حفظ المراجعة أو التسليم.

---

## 4. إرشادات سير عمل Git (Git Flow for Agents)
* الفرع الرئيسي `main` محمي؛ لا تقم بإجراء `commit` مباشر إليه.
* استخدم دائماً فروع الميزات: `feature/milestone-submission`, `feature/supervisor-reviews`, `fix/edge-case-double-click`.
* رسائل الـ Commit تتبع نسق Conventional Commits:
  * `feat: add milestone submission workflow with github repo validation`
  * `fix: prevent duplicate submission on fast double click`
  * `test: add feature tests for unauthorized submission attempts`
  * `docs: update mini-srs with acceptance criteria`

---

## 5. معايير كتابة الاختبارات (Testing Guidelines)
* كل مسار (Route) ووظيفة وظيفية (FR) أو حالة حافة (Edge Case) يجب أن يقابلها اختبار Feature Test.
* استخدم أسماء دوال اختبار وصفية باللغة الإنجليزية تبدأ بـ `test_`:
  * `test_student_can_submit_milestone_with_valid_github_url()`
  * `test_unauthorized_student_cannot_submit_to_another_project()`
  * `test_submission_fails_when_notes_are_empty()`
  * `test_supervisor_can_approve_submission_and_update_milestone_status()`
* تحقق دائماً من نجاح جميع الاختبارات بتشغيل: `php artisan test`.
