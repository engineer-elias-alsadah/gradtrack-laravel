<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Review;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Project $project, Milestone $milestone, Submission $submission)
    {
        $user = Auth::user();

        // Edge Case Check 1: Milestone and submission integrity
        if ($milestone->project_id !== $project->id || $submission->milestone_id !== $milestone->id) {
            abort(404, 'بيانات المراجعة غير متطابقة.');
        }

        // Edge Case Check 2: Only designated supervisor
        if ($project->supervisor_id !== $user->id) {
            abort(403, 'فقط المشرف الأكاديمي المعين لهذا المشروع يملك صلاحية التقييم.');
        }

        DB::transaction(function () use ($request, $submission, $milestone, $project, $user) {
            Review::create([
                'submission_id' => $submission->id,
                'reviewer_id' => $user->id,
                'score' => $request->validated('score'),
                'decision' => $request->validated('decision'),
                'feedback' => $request->validated('feedback'),
                'reviewed_at' => now(),
            ]);

            // Update milestone status and score
            $milestone->update([
                'status' => $request->validated('decision'),
                'score' => $request->validated('score'),
            ]);

            // Recalculate project overall progress
            $project->recalculateProgress();

            // Activity Log
            $decisionArabic = [
                'approved' => 'اعتماد المرحلة',
                'needs_revision' => 'طلب إعادة تعديل',
                'rejected' => 'رفض التسليم',
            ][$request->validated('decision')] ?? 'تقييم التسليم';

            ActivityLog::log(
                $project->id,
                $user->id,
                "تقييم المشرف: {$decisionArabic}",
                "قام المشرف {$user->name} برصد درجة ({$request->validated('score')}/100) مع القرار ({$decisionArabic}) للمرحلة: {$milestone->title}."
            );
        });

        return redirect()->route('milestones.show', [$project, $milestone])
            ->with('success', 'تم رصد التقييم وحفظ الملاحظات الأكاديمية وتحديث حالة المرحلة بنجاح.');
    }
}
