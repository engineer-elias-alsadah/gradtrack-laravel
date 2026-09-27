<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    public function show(Project $project, Milestone $milestone)
    {
        $user = Auth::user();

        // Edge Case Check: Milestone must belong to project
        if ($milestone->project_id !== $project->id) {
            abort(404, 'المرحلة المطلوبة لا تنتمي إلى هذا المشروع.');
        }

        // Authorization check for student
        if ($user->isStudent() && ! $project->isMember($user->id)) {
            abort(403, 'غير مصرح لك بالوصول إلى بيانات هذه المرحلة.');
        }

        $milestone->load([
            'submissions' => fn ($q) => $q->orderBy('version', 'desc')->with(['student', 'reviews.reviewer']),
            'project.supervisor',
            'project.members',
        ]);

        return view('milestones.show', compact('project', 'milestone', 'user'));
    }

    public function store(Request $request, Project $project)
    {
        $user = Auth::user();

        if (! $user->isSupervisor() || $project->supervisor_id !== $user->id) {
            abort(403, 'فقط مشرف المشروع يملك صلاحية إضافة مراحل جديدة.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'weight_percentage' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'title.required' => 'عنوان المرحلة مطلوب.',
            'description.required' => 'وصف مخرجات المرحلة مطلوب.',
            'due_date.required' => 'تاريخ الاستحقاق مطلوب.',
            'due_date.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون اليوم أو تاريخاً مستقبلياً.',
            'weight_percentage.required' => 'الوزن المئوي للمرحلة مطلوب.',
            'weight_percentage.min' => 'الوزن المئوي يجب أن يكون 1% على الأقل.',
            'weight_percentage.max' => 'الوزن المئوي لا يمكن أن يتجاوز 100%.',
        ]);

        $nextOrder = ($project->milestones()->max('order_num') ?? 0) + 1;

        $milestone = Milestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order_num' => $nextOrder,
            'due_date' => $validated['due_date'],
            'weight_percentage' => $validated['weight_percentage'],
            'status' => 'pending',
            'max_score' => 100,
            'score' => null,
        ]);

        ActivityLog::log(
            $project->id,
            $user->id,
            'إضافة مرحلة جديدة',
            "قام المشرف {$user->name} بإضافة المرحلة رقم {$nextOrder}: {$milestone->title} بوزن ({$milestone->weight_percentage}%)."
        );

        return back()->with('success', "تمت إضافة المرحلة ({$milestone->title}) بنجاح.");
    }
}
