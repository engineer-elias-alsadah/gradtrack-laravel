# 🎓 منصة GradTrack لمشاريع التخرج وهندسة البرمجيات
### Graduation Project & Milestone Management Portal

![CI/CD Build](https://img.shields.io/badge/CI%2FCD-Passing-emerald?style=for-the-badge&logo=githubactions&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11%2F12-red?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-indigo?style=for-the-badge&logo=php&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-19%20Passed-success?style=for-the-badge&logo=checkmarx)
![Academic](https://img.shields.io/badge/Academic%20Year-2026%2F2027-blue?style=for-the-badge)

---

## 🏛️ بيانات المشروع الأكاديمية (Academic Info)
* **المقرر:** هندسة البرمجيات - العملي (Software Engineering Lab)
* **إشراف المهندس القدير:** **ساهر الهمداني**
* **إعداد وبرمجة الطالب:** **إلياس جمال الساده**
* **الكلية والقسم:** كلية الهندسة وتكنولوجيا المعلومات - قسم هندسة البرمجيات

---

## 📌 نبذة عن المشروع (Overview)
**GradTrack** هي منصة ويب متطورة مبنية بإطار العمل **Laravel**، تهدف إلى حوكمة وإدارة دورة حياة مشاريع التخرج والواجبات الهندسية. تم تصميم وبناء النظام وفق أعلى معايير هندسة البرمجيات لتطبيق الممارسات الفعلية في بيئة العمل:
- تقسيم المشروع إلى مراحل زمنية معيارية (**Milestones**) بأوزان نسبية ومواعيد نهائية محددة.
- ربط التسليمات بمستودعات الأكواد على **GitHub** ومتابعة التغييرات عبر الفروع (**Branches**).
- دورة مراجعة وتقييم تفاعلية للمشرف الأكاديمي مع تغذية راجعة (**Feedback**) فورية.
- لوحة تحكم إحصائية تحسب نسب الإنجاز التراكمية تلقائياً.
- معالجة متقدمة لـ 9 حالات حافة حرجة (**Edge Cases**) وتغطية شاملة باختبارات الجودة المؤتمتة (**CI/CD**).

---

### 1. حل المشاكل الخمس الأساسية لمشاريع التخرج (The 5 Core Problems & Solutions)
1. **المشكلة 1 (غياب الجدولة المرحلية):** يُحل عبر نظام جدولة الـ Milestones بأوزان محددة وحساب تراكمي لنسب الإنجاز.
2. **المشكلة 2 (تشتت الملاحظات):** يُحل عبر دورة مراجعة متكاملة (Approved, Needs Revision) مع أرقام الإصدارات (v1, v2).
3. **المشكلة 3 (عشوائية إدارة الأكواد):** يُحل بالربط الإلزامي بروابط مستودعات وفروع GitHub وتطبيق الـ Git Flow.
4. **المشكلة 4 (تداخل الصلاحيات):** يُحل عبر عزل الصلاحيات وحماية المشاريع (403 Forbidden) ومنع التقييم الذاتي.
5. **المشكلة 5 (إهمال حالات الحافة وغياب الاختبارات):** يُحل بمعمل حالات الحافة الـ 9 و 19 اختباراً مؤتمتاً في GitHub Actions CI.

### 2. إدارة الهوية وتوزيع الصلاحيات (RBAC - Role-Based Access Control)
- **المشرف الأكاديمي (Supervisor):** تأسيس مشاريع التخرج، جدولة المراحل، إضافة الطلاب للفرق، مراجعة التسليمات، ورصد الدرجات.
- **الطالب وقائد المشروع (Student / Team Lead):** رفع مخرجات المراحل، إرفاق روابط GitHub، ومتابعة التعديلات والملاحظات.
- **نظام تسجيل مفتوح ديناميكي:** يمكن لأي طالب أو مشرف جديد إنشاء حساب والانضمام عبر صفحة `/register`.

### 3. دورة التسليم والمراجعة المرنة (Milestone Submission & Review Cycle)
- دعم تعدد الإصدارات (**Versioning: v1, v2...**) للمرحلة الواحدة في حال طلب المشرف إعادة التعديل (`Needs Revision`).
- سجل تدقيق العمليات (**Activity Audit Trail**) لتوثيق كل حركة تتم داخل المشروع بالوقت والتاريخ.
- زر تبديل سريع للحسابات التجريبية (**Quick Switcher**) لتسهيل المناقشة الشفهية مع المشرف.

### 4. معمل حالات الحافة التفاعلي (Interactive Edge Cases Lab)
معالجة وتغطية سيناريوهات الفشل غير الطبيعية المحددة في المحاضرة:
1. **حالات حافة البيانات:** التحقق الصارم من الحقول الفارغة، منع الروابط المشوهة، وضبط نطاق الدرجات (0 - 100).
2. **حالات حافة الصلاحيات:** منع وصول أو تسليم طالب لمشروع فريق آخر (403 Forbidden)، وحظر التقييم الذاتي للطلاب.
3. **حالات حافة الوقت:** وسم التسليمات المتأخرة (`is_late = true`) وحماية مسودات التقارير عند انتهاء الجلسة.
4. **حالات حافة النظام والتزامن:** منع النقر المزدوج (Double-Click Prevention) واستخدام المعاملات الذرية (`DB::transaction`).

---

## 📂 هيكلية المستودع والوثائق الهندسية

```text
├── .github/
│   ├── workflows/
│   │   └── ci.yml               # خط أنابيب التكامل المستمر (GitHub Actions CI)
│   └── ISSUE_TEMPLATE/
│       ├── feature_request.md   # قالب طلب الميزات البرمجية
│       └── bug_report.md        # قالب الإبلاغ عن الأخطاء وحالات الحافة
├── app/                         # نماذج ومتحكمات Laravel (MVC Pattern)
│   ├── Http/Controllers/        # Controllers (Auth, Dashboard, Milestone, Submission, Review, EdgeCase)
│   ├── Http/Requests/           # Form Requests للتحقق الصارم من صحة البيانات
│   └── Models/                  # Eloquent Models والعلاقات التشاركية
├── database/                    # قواعد البيانات
│   ├── migrations/              # جداول النظام (Users, Projects, Milestones, Submissions, Reviews, Logs)
│   └── seeders/                 # البيانات الأولية المجهزة للمناقشة
├── docs/                        # وثائق هندسة البرمجيات المعتمدة
│   ├── SRS.md                   # وثيقة المتطلبات المصغرة (Mini-SRS) وقصص المستخدمين
│   └── KANBAN_BOARD.md          # لوحة المهام الخمس الأساسية وسير عمل Git
├── resources/views/             # واجهات المستخدم العصرية بدعم كامل للـ RTL
├── tests/                       # اختبارات الجودة الآلية (Feature & Unit Tests)
│   └── Feature/
│       ├── AuthTest.php
│       ├── SubmissionAndReviewWorkflowTest.php
│       └── EdgeCasesValidationTest.php
├── AI_Log.md                    # سجل استخدام الذكاء الاصطناعي بشفافية (شريحة 15)
├── AGENTS.md                    # إرشادات الوكلاء الأذكياء ومحددات الكود
└── GEMINI.md                    # إرشادات نموذج Google Gemini
```

---

## 🛠️ متطلبات التشغيل والبدء السريع (Quickstart)

### المتطلبات الأساسية:
* PHP 8.2 أو أحدث (مزود بإضافات: `pdo_sqlite`, `mbstring`, `fileinfo`)
* Composer 2.x
* Git

### خطوات التثبيت والتشغيل المحلي:

1. **استنساخ المستودع (Clone Repository):**
   ```bash
   git clone https://github.com/engineer-elias-alsadah/gradtrack-laravel.git
   cd gradtrack-laravel
   ```

2. **تثبيت الاعتماديات (Install Dependencies):**
   ```bash
   composer install
   ```

3. **إعداد ملف البيئة وتوليد المفتاح:**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **تشغيل الهجرة وتعبئة البيانات التجريبية (Migrate & Seed):**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **تشغيل الاختبارات الآلية للتحقق من صحة النظام:**
   ```bash
   php artisan test
   ```

6. **إطلاق السيرفر المحلي:**
   ```bash
   php artisan serve
   ```
   افتح المتصفح على: `http://localhost:8000`

---

## 🔑 الحسابات المجهزة للمناقشة (Demo Accounts)

| المستخدم | البريد الإلكتروني | كلمة المرور | الدور الأكاديمي |
| :--- | :--- | :--- | :--- |
| **المهندس ساهر الهمداني** | `saher@university.edu` | `password123` | **مشرف أكاديمي (Supervisor)** |
| **د. عادل الشميري** | `adel@university.edu` | `password123` | **مشرف أكاديمي ثانٍ (Supervisor)** |
| **إلياس جمال الساده** | `elias@university.edu` | `password123` | **طالب وقائد المشروع (Student)** |
| **محمد أحمد الصالح** | `mohammed@university.edu` | `password123` | **طالب عضو فريق (Student)** |

*(ملاحظة: المنصة مفتوحة وتتيح تسجيل أي مشرف أو طالب جديد عبر الرابط `/register`، كما يمكنك استخدام أزرار التبديل السريع المباشرة في شريط التنقل أعلى الشاشة أثناء المناقشة).*

---

## 🧪 الاختبارات الآلية والتكامل المستمر (CI/CD)

يحتوي المشروع على حزمة اختبارات برمجية تغطي كافة الوظائف وحالات الحافة، وتعمل آلياً على سحابة GitHub عبر **GitHub Actions**:
```bash
php artisan test
```
**نتائج الفحص:**
- `AuthTest`: فحص تسجيل الدخول السليم ورفض البيانات الخاطئة وحماية الجلسة.
- `SubmissionAndReviewWorkflowTest`: فحص دورة تسليم المرحلة واحتساب الدرجات ونسب الإنجاز.
- `EdgeCasesValidationTest`: فحص حماية الصلاحيات، ورفض التسليم لغير الأعضاء، ومنع تجاوز الدرجة لـ 100، ومنع التسليم لمرحلة معتمدة.

---

## 🤖 الشفافية والمسؤولية الأكاديمية (AI Transparency)

التزاماً بتوجيهات المحاضرة الأولى للمهندس **ساهر الهمداني**، تم توثيق جميع التفاعلات والقرارات الهندسية مع أدوات الذكاء الاصطناعي بشفافية في ملف:
👉 **[`AI_Log.md`](./AI_Log.md)**

* **الهدف:** توظيف التقنية كمساعد في التفكير الهندسي وصياغة معايير القبول وحالات الحافة، مع الفهم التام لكل سطر برمجي ومسار عمل تم بناؤه.
