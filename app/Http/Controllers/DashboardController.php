<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSupervisor()) {
            $projects = Project::where('supervisor_id', $user->id)
                ->with(['members', 'milestones'])
                ->get();

            $pendingReviewsCount = Milestone::whereIn('project_id', $projects->pluck('id'))
                ->where('status', 'submitted')
                ->count();

            $totalStudentsCount = $projects->flatMap->members->unique('id')->count();

            $recentSubmissions = Submission::whereHas('milestone', function ($q) use ($projects) {
                $q->whereIn('project_id', $projects->pluck('id'));
            })->with(['milestone.project', 'student'])->latest()->take(5)->get();

        } elseif ($user->isAdmin()) {
            $projects = Project::with(['supervisor', 'members', 'milestones'])->get();
            $pendingReviewsCount = Milestone::where('status', 'submitted')->count();
            $totalStudentsCount = $projects->flatMap->members->unique('id')->count();
            $recentSubmissions = Submission::with(['milestone.project', 'student'])->latest()->take(5)->get();
        } else {
            // Student
            $projects = $user->projects()->with(['supervisor', 'members', 'milestones'])->get();
            $pendingReviewsCount = 0;
            $totalStudentsCount = 0;
            $recentSubmissions = Submission::where('user_id', $user->id)
                ->with(['milestone.project', 'latestReview'])
                ->latest()
                ->take(5)
                ->get();
        }

        $activityLogs = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'projects',
            'pendingReviewsCount',
            'totalStudentsCount',
            'recentSubmissions',
            'activityLogs'
        ));
    }
}
