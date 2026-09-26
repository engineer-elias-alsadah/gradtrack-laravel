<?php

namespace Tests\Feature;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionAndReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $supervisor;
    private User $student;
    private Project $project;
    private Milestone $milestone;

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

        $this->project = Project::create([
            'title' => 'منصة GradTrack',
            'slug' => 'gradtrack-project',
            'description' => 'مشروع هندسة برمجيات',
            'supervisor_id' => $this->supervisor->id,
            'progress_percentage' => 0,
        ]);

        $this->project->members()->attach($this->student->id, ['role_in_team' => 'leader']);

        $this->milestone = Milestone::create([
            'project_id' => $this->project->id,
            'title' => 'المرحلة 1: وثيقة Mini-SRS',
            'description' => 'المتطلبات وقصص المستخدمين',
            'order_num' => 1,
            'due_date' => Carbon::now()->addDays(7),
            'weight_percentage' => 30,
            'status' => 'pending',
        ]);
    }

    public function test_authorized_student_can_submit_milestone(): void
    {
        $response = $this->actingAs($this->student)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions", [
                'notes' => 'تم استكمال وثيقة الـ SRS وحصر كافة حالات الحافة وقصص المستخدمين.',
                'github_url' => 'https://github.com/elias-alsada/gradtrack-laravel',
            ]);

        $response->assertRedirect("/projects/{$this->project->slug}/milestones/{$this->milestone->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('submissions', [
            'milestone_id' => $this->milestone->id,
            'user_id' => $this->student->id,
            'version' => 1,
            'is_late' => false,
        ]);

        $this->assertEquals('submitted', $this->milestone->fresh()->status);
    }

    public function test_supervisor_can_review_and_approve_submission(): void
    {
        // First, create submission
        $submission = Submission::create([
            'milestone_id' => $this->milestone->id,
            'user_id' => $this->student->id,
            'version' => 1,
            'notes' => 'ملاحظات التسليم الأولى',
            'github_url' => 'https://github.com/elias-alsada/gradtrack-laravel',
            'submitted_at' => now(),
        ]);
        $this->milestone->update(['status' => 'submitted']);

        // Supervisor reviews and approves
        $response = $this->actingAs($this->supervisor)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions/{$submission->id}/reviews", [
                'score' => 96,
                'decision' => 'approved',
                'feedback' => 'عمل متقن جداً، تم اعتماد المخرجات وإغلاق المرحلة.',
            ]);

        $response->assertRedirect("/projects/{$this->project->slug}/milestones/{$this->milestone->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $this->supervisor->id,
            'score' => 96,
            'decision' => 'approved',
        ]);

        // Check milestone updated
        $freshMilestone = $this->milestone->fresh();
        $this->assertEquals('approved', $freshMilestone->status);
        $this->assertEquals(96, $freshMilestone->score);

        // Check project progress recalculated
        $this->assertEquals(30, $this->project->fresh()->progress_percentage);
    }
}
