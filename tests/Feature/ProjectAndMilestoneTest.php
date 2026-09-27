<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAndMilestoneTest extends TestCase
{
    use RefreshDatabase;

    private User $supervisor;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supervisor = User::create([
            'name' => 'المهندس ساهر الهمداني',
            'email' => 'saher@university.edu',
            'role' => 'supervisor',
            'password' => bcrypt('password123'),
        ]);

        $this->student = User::create([
            'name' => 'إلياس جمال الساده',
            'email' => 'elias@university.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_supervisor_can_create_new_project(): void
    {
        $response = $this->actingAs($this->supervisor)
            ->post('/projects', [
                'title' => 'مشروع نظام العيادات الطبية المتقدم',
                'description' => 'نظام تتبع وحجز المواعيد الطبية وفق أفضل الممارسات الهندسية.',
                'academic_year' => '2026/2027',
                'github_repository' => 'https://github.com/saher/clinic-system',
                'leader_student_id' => $this->student->id,
            ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'مشروع نظام العيادات الطبية المتقدم',
            'supervisor_id' => $this->supervisor->id,
        ]);

        $project = Project::where('title', 'مشروع نظام العيادات الطبية المتقدم')->first();
        $response->assertRedirect("/projects/{$project->slug}");
        $this->assertTrue($project->isMember($this->student->id));
    }

    public function test_student_cannot_create_project(): void
    {
        $response = $this->actingAs($this->student)
            ->post('/projects', [
                'title' => 'محاولة طالب لإنشاء مشروع',
                'description' => 'وصف غير مصرح به',
                'academic_year' => '2026/2027',
                'leader_student_id' => $this->student->id,
            ]);

        $response->assertStatus(403);
    }

    public function test_supervisor_can_add_milestone_to_project(): void
    {
        $project = Project::create([
            'title' => 'مشروع هندسي',
            'slug' => 'test-project',
            'description' => 'وصف المشروع',
            'supervisor_id' => $this->supervisor->id,
        ]);

        $response = $this->actingAs($this->supervisor)
            ->post("/projects/{$project->slug}/milestones", [
                'title' => 'المرحلة الأولى: تحليل النظام',
                'description' => 'كتابة مواصفات المتطلبات والمخططات.',
                'due_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'weight_percentage' => 25,
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('milestones', [
            'project_id' => $project->id,
            'title' => 'المرحلة الأولى: تحليل النظام',
            'weight_percentage' => 25,
        ]);
    }
}
