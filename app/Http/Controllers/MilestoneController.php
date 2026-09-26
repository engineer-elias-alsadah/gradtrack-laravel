<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
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
}
