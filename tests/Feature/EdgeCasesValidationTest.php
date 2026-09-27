<?php

namespace Tests\Feature;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EdgeCasesValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $supervisor;
    private User $enrolledStudent;
    private User $unrelatedStudent;
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

        $this->enrolledStudent = User::create([
            'name' => 'إلياس جمال الساده',
            'email' => 'elias@university.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
        ]);

        $this->unrelatedStudent = User::create([
            'name' => 'طالب غريب عن المشروع',
            'email' => 'stranger@university.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
        ]);

        $this->project = Project::create([
            'title' => 'مشروع هندسي لاختبار حالات الحافة',
            'slug' => 'edge-case-test-project',
            'description' => 'مشروع اختبار',
            'supervisor_id' => $this->supervisor->id,
            'progress_percentage' => 0,
        ]);

        $this->project->members()->attach($this->enrolledStudent->id, ['role_in_team' => 'leader']);

        $this->milestone = Milestone::create([
            'project_id' => $this->project->id,
            'title' => 'مرحلة اختبار القيود وحالات الفشل',
            'description' => 'المتطلبات',
            'order_num' => 1,
            'due_date' => Carbon::now()->addDays(3),
            'weight_percentage' => 25,
            'status' => 'pending',
        ]);
    }

    /**
     * Edge Case 1 (Authorization): Unrelated student cannot submit to another team's project
     */
    public function test_edge_case_unauthorized_student_cannot_submit(): void
    {
        $response = $this->actingAs($this->unrelatedStudent)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions", [
                'notes' => 'محاولة اختراق أو تسليم غير مصرح به.',
                'github_url' => 'https://github.com/hacker/fake-repo',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('submissions', ['user_id' => $this->unrelatedStudent->id]);
    }

    /**
     * Edge Case 2 (Data Validation): Invalid GitHub URL and short notes fail with 422
     */
    public function test_edge_case_data_validation_rejection(): void
    {
        $response = $this->actingAs($this->enrolledStudent)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions", [
                'notes' => 'قصير', // Min is 10 chars
                'github_url' => 'https://not-github.com/user/project', // Must be github.com
            ]);

        $response->assertSessionHasErrors(['notes', 'github_url']);
        $this->assertDatabaseCount('submissions', 0);
    }

    /**
     * Edge Case 3 (Immutability): Cannot submit to an approved milestone
     */
    public function test_edge_case_cannot_submit_to_already_approved_milestone(): void
    {
        $this->milestone->update(['status' => 'approved']);

        $response = $this->actingAs($this->enrolledStudent)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions", [
                'notes' => 'محاولة تسليم إضافي لمرحلة مغلقة ومعتمدة.',
                'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('submissions', 0);
    }

    /**
     * Edge Case 4 (Role Isolation): Student cannot submit reviews/grades
     */
    public function test_edge_case_student_cannot_evaluate_submissions(): void
    {
        $submission = Submission::create([
            'milestone_id' => $this->milestone->id,
            'user_id' => $this->enrolledStudent->id,
            'version' => 1,
            'notes' => 'تسليم أولي لاختبار محاولة التقييم الذاتي.',
            'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->enrolledStudent)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions/{$submission->id}/reviews", [
                'score' => 100,
                'decision' => 'approved',
                'feedback' => 'محاولة الطالب منح نفسه 100.',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('reviews', 0);
    }

    /**
     * Edge Case 5 (Data Range): Supervisor score must be between 0 and 100
     */
    public function test_edge_case_review_score_out_of_bounds(): void
    {
        $submission = Submission::create([
            'milestone_id' => $this->milestone->id,
            'user_id' => $this->enrolledStudent->id,
            'version' => 1,
            'notes' => 'تسليم أولي.',
            'github_url' => 'https://github.com/engineer-elias-alsadah/gradtrack-laravel',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->supervisor)
            ->post("/projects/{$this->project->slug}/milestones/{$this->milestone->id}/submissions/{$submission->id}/reviews", [
                'score' => 150, // Invalid: Max is 100
                'decision' => 'approved',
                'feedback' => 'درجة تفوق الحد الأقصى.',
            ]);

        $response->assertSessionHasErrors('score');
        $this->assertDatabaseCount('reviews', 0);
    }
}
