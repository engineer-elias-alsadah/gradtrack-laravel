<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('academic_year')->default('2026/2027');
            $table->string('status')->default('in_progress'); // proposed, approved, in_progress, completed
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
            $table->string('github_repository')->nullable();
            $table->unsignedInteger('progress_percentage')->default(0);
            $table->timestamps();
        });

        // 2. Project Members (Pivot)
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role_in_team')->default('member'); // leader, member
            $table->timestamps();

            $table->unique(['project_id', 'user_id']);
        });

        // 3. Milestones table
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('order_num')->default(1);
            $table->date('due_date');
            $table->unsignedInteger('weight_percentage')->default(20);
            $table->string('status')->default('pending'); // pending, submitted, under_review, needs_revision, approved
            $table->unsignedInteger('max_score')->default(100);
            $table->unsignedInteger('score')->nullable();
            $table->timestamps();
        });

        // 4. Submissions table
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('milestone_id')->constrained('milestones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('version')->default(1);
            $table->text('notes');
            $table->string('github_url');
            $table->string('attachment_path')->nullable();
            $table->boolean('is_late')->default(false);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });

        // 5. Reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('score');
            $table->text('feedback');
            $table->string('decision')->default('approved'); // approved, needs_revision, rejected
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();
        });

        // 6. Activity Logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
    }
};
