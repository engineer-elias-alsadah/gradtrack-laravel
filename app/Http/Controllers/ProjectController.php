<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $user = Auth::user();

        // Check view permissions
        if ($user->isStudent() && ! $project->isMember($user->id)) {
            abort(403, 'غير مصرح لك بالاطلاع على تفاصيل هذا المشروع الأكاديمي.');
        }

        $project->load(['supervisor', 'members', 'milestones.submissions.latestReview', 'activityLogs.user']);

        return view('projects.show', compact('project', 'user'));
    }
}
