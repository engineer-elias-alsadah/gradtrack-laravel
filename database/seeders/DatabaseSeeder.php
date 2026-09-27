<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Review;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Supervisor (Eng. Saher Al-Hamdani)
        $supervisor = User::create([
            'name' => 'المهندس ساهر الهمداني',
            'email' => 'saher@university.edu',
            'role' => 'supervisor',
            'university_id' => 'ENG-2026-01',
            'department' => 'هندسة البرمجيات',
            'phone' => '+967770000001',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 2. Create Lead Student (Elias Jamal Al-Sada)
        $elias = User::create([
            'name' => 'إلياس جمال الساده',
            'email' => 'elias@university.edu',
            'role' => 'student',
            'university_id' => '44102981',
            'department' => 'هندسة البرمجيات',
            'phone' => '+967771234567',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 3. Create Peer Student (Team Member)
        $peerStudent = User::create([
            'name' => 'محمد أحمد الصالح',
            'email' => 'mohammed@university.edu',
            'role' => 'student',
            'university_id' => '44102982',
            'department' => 'هندسة البرمجيات',
            'phone' => '+967772345678',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 4. Create Unrelated Student (For testing Edge Case: Unauthorized access)
        $otherStudent = User::create([
            'name' => 'خالد عبد الله العولقي',
            'email' => 'khaled@university.edu',
            'role' => 'student',
            'university_id' => '44103000',
            'department' => 'علوم الحاسوب',
            'phone' => '+967773456789',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 5. Create Second Supervisor (Dr. Adel Al-Shemiri) to demonstrate multi-supervisor support
        $secondSupervisor = User::create([
            'name' => 'د. عادل الشميري',
            'email' => 'adel@university.edu',
            'role' => 'supervisor',
            'university_id' => 'ENG-2026-02',
            'department' => 'هندسة البرمجيات',
            'phone' => '+967770000002',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 6. Create Primary Project: GradTrack
        $project = Project::create([
            'title' => 'منصة GradTrack لإدارة وتتبع مراحل ومشاريع التخرج الهندسية',
            'slug' => 'gradtrack-milestone-management',
            'description' => 'مشروع هندسي متكامل يهدف إلى حوكمة وتتبع تسليمات مشاريع التخرج الأكاديمية، وربطها بروابط مستودعات GitHub، وتطبيق أتمتة الاختبارات وممارسات التكامل المستمر CI/CD، وتوثيق حالات الحافة وقصص المستخدمين.',
            'academic_year' => '2026/2027',
            'status' => 'in_progress',
            'supervisor_id' => $supervisor->id,
            'github_repository' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel',
            'progress_percentage' => 50,
        ]);

        // Attach Project Members
        $project->members()->attach($elias->id, ['role_in_team' => 'leader']);
        $project->members()->attach($peerStudent->id, ['role_in_team' => 'member']);

        // 7. Seed Milestones
        // Milestone 1: SRS
        $m1 = Milestone::create([
            'project_id' => $project->id,
            'title' => 'المرحلة 1: وثيقة المتطلبات المصغرة (Mini-SRS) وهندسة الاحتياجات',
            'description' => 'تحديد المشكلة والحل، صياغة قصص المستخدمين ومعايير القبول (Given/When/Then)، وتحديد حالات الحافة (Edge Cases) وسجل استخدام الذكاء الاصطناعي AI_Log.',
            'order_num' => 1,
            'due_date' => Carbon::now()->subDays(14),
            'weight_percentage' => 25,
            'status' => 'approved',
            'max_score' => 100,
            'score' => 98,
        ]);

        $sub1 = Submission::create([
            'milestone_id' => $m1->id,
            'user_id' => $elias->id,
            'version' => 1,
            'notes' => 'تم استكمال وثيقة الـ Mini-SRS وفق النموذج المعتمد في السلايدات وحصر 9 حالات حافة رئيسية مع توثيق AI_Log.',
            'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel/blob/main/docs/SRS.md',
            'attachment_path' => null,
            'is_late' => false,
            'submitted_at' => Carbon::now()->subDays(15),
        ]);

        Review::create([
            'submission_id' => $sub1->id,
            'reviewer_id' => $supervisor->id,
            'score' => 98,
            'feedback' => 'عمل ممتاز جداً ومتقن. صياغة معايير القبول بالـ Given/When/Then واضحة جداً ودقيقة والتزام تام بالشفافية في الـ AI Log.',
            'decision' => 'approved',
            'reviewed_at' => Carbon::now()->subDays(13),
        ]);

        // Milestone 2: System Architecture
        $m2 = Milestone::create([
            'project_id' => $project->id,
            'title' => 'المرحلة 2: تصميم البنية التحتية وقاعدة البيانات وتأسيس بيئة Git',
            'description' => 'تصميم الـ Database Schema، وإنشاء الـ Migrations والـ Models مع إعداد مستودع GitHub ولوحة Kanban بـ 5 Issues أساسية.',
            'order_num' => 2,
            'due_date' => Carbon::now()->subDays(7),
            'weight_percentage' => 25,
            'status' => 'approved',
            'max_score' => 100,
            'score' => 95,
        ]);

        $sub2 = Submission::create([
            'milestone_id' => $m2->id,
            'user_id' => $elias->id,
            'version' => 1,
            'notes' => 'تم إنشاء قاعدة البيانات SQLite مع العلاقات ونماذج Eloquent، وإنشاء الـ Project Board على GitHub.',
            'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel/tree/main/database/migrations',
            'attachment_path' => null,
            'is_late' => false,
            'submitted_at' => Carbon::now()->subDays(8),
        ]);

        Review::create([
            'submission_id' => $sub2->id,
            'reviewer_id' => $supervisor->id,
            'score' => 95,
            'feedback' => 'البنية التحتية ممتازة وتراعي الـ Foreign Keys والـ Cascading. جاهز للمرحلة القادمة.',
            'decision' => 'approved',
            'reviewed_at' => Carbon::now()->subDays(6),
        ]);

        // Milestone 3: Implementation & CI/CD
        $m3 = Milestone::create([
            'project_id' => $project->id,
            'title' => 'المرحلة 3: بناء التطبيق البرمجي والاختبارات الآلية (CI/CD)',
            'description' => 'تطوير واجهات المستخدم وربط الـ Controllers، وإعداد ملف GitHub Actions والـ Feature Tests لفحص حالات الحافة.',
            'order_num' => 3,
            'due_date' => Carbon::now()->addDays(4),
            'weight_percentage' => 30,
            'status' => 'needs_revision',
            'max_score' => 100,
            'score' => 85,
        ]);

        $sub3 = Submission::create([
            'milestone_id' => $m3->id,
            'user_id' => $elias->id,
            'version' => 1,
            'notes' => 'تم كتابة متحكمات المنظومة وإعداد سير العمل الأوتوماتيكي في GitHub Actions واختبار تسجيل الدخول.',
            'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel/pull/3',
            'attachment_path' => null,
            'is_late' => false,
            'submitted_at' => Carbon::now()->subDays(2),
        ]);

        Review::create([
            'submission_id' => $sub3->id,
            'reviewer_id' => $supervisor->id,
            'score' => 85,
            'feedback' => 'أداء متميز ولكن يرجى إضافة اختبار Feature Test خاص بالتحقق من رفض تسليم غير المصرح لهم ورفع الإصدار 2 (v2).',
            'decision' => 'needs_revision',
            'reviewed_at' => Carbon::now()->subDay(),
        ]);

        // Milestone 4: Final Defense
        $m4 = Milestone::create([
            'project_id' => $project->id,
            'title' => 'المرحلة 4: النشر السحابي، إعداد التقرير، والمناقشة النهائية',
            'description' => 'رفع المشروع على منصة استضافة، تجهيز العرض التقديمي (PowerPoint) وتوثيق مخرجات هندسة البرمجيات للاختبار الشفوي.',
            'order_num' => 4,
            'due_date' => Carbon::now()->addDays(15),
            'weight_percentage' => 20,
            'status' => 'pending',
            'max_score' => 100,
            'score' => null,
        ]);

        // Calculate Project Progress
        $project->recalculateProgress();

        // 8. Create Activity Logs
        ActivityLog::log($project->id, $elias->id, 'إنشاء المشروع', 'قام الطالب إلياس الساده بإنشاء وتأسيس مشروع التخرج.');
        ActivityLog::log($project->id, $supervisor->id, 'اعتماد خطة المراحل', 'قام المهندس ساهر الهمداني باعتماد الأوزان وتواريخ الاستحقاق.');
        ActivityLog::log($project->id, $elias->id, 'رفع تسليم المرحلة الأولى', 'تم تسليم وثيقة الـ Mini-SRS بنجاح.');
        ActivityLog::log($project->id, $supervisor->id, 'تقييم المرحلة الأولى', 'تم رصد 98 درجة واعتماد المخرجات.');
        ActivityLog::log($project->id, $supervisor->id, 'طلب تعديل المرحلة الثالثة', 'طلب المهندس ساهر إضافة اختبار الـ Edge Case قبل الاعتماد النهائي.');
    }
}
