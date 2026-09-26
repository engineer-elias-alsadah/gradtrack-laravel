<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function store(StoreSubmissionRequest $request, Project $project, Milestone $milestone)
    {
        // Edge Case Check 1: Milestone must belong to project
        if ($milestone->project_id !== $project->id) {
            abort(404, 'المرحلة لا تنتمي لهذا المشروع.');
        }

        // Edge Case Check 2: Cannot submit if already approved
        if ($milestone->isApproved()) {
            return back()->with('error', 'عذراً، هذه المرحلة تم اعتمادها نهائياً ولا يمكن إعادة رفع تسليم جديد لها.');
        }

        // Edge Case Check 3: Late Submission flag
        $isLate = false;
        if ($milestone->due_date && Carbon::now()->isAfter($milestone->due_date->endOfDay())) {
            $isLate = true;
        }

        // Versioning: increment version if previous submission exists
        $nextVersion = ($milestone->submissions()->max('version') ?? 0) + 1;

        // Attachment handling
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('submissions', 'public');
        }

        DB::transaction(function () use ($milestone, $request, $nextVersion, $isLate, $attachmentPath, $project) {
            $submission = Submission::create([
                'milestone_id' => $milestone->id,
                'user_id' => Auth::id(),
                'version' => $nextVersion,
                'notes' => $request->validated('notes'),
                'github_url' => $request->validated('github_url'),
                'attachment_path' => $attachmentPath,
                'is_late' => $isLate,
                'submitted_at' => now(),
            ]);

            // Update milestone status
            $milestone->update([
                'status' => 'submitted',
            ]);

            // Log activity
            $lateText = $isLate ? ' (تسليم متأخر بعد الموعد)' : '';
            ActivityLog::log(
                $project->id,
                Auth::id(),
                'رفع تسليم للمرحلة',
                "قام الطالب " . Auth::user()->name . " برفع تسليم الإصدار (v{$nextVersion}) للمرحلة: {$milestone->title}{$lateText}."
            );
        });

        $message = "تم رفع تسليم المرحلة (الإصدار {$nextVersion}) بنجاح وإرساله للمشرف للمراجعة.";
        if ($isLate) {
            $message .= " تنبيه: تم تسجيل هذا التسليم بعد انتهاء الموعد النهائي المحدد.";
        }

        return redirect()->route('milestones.show', [$project, $milestone])
            ->with('success', $message);
    }
}
