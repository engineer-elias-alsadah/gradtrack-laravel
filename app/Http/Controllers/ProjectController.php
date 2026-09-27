<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        // Available students to add (if supervisor)
        $availableStudents = [];
        if ($user->isSupervisor() && $project->supervisor_id === $user->id) {
            $currentMemberIds = $project->members->pluck('id')->toArray();
            $availableStudents = User::where('role', 'student')
                ->whereNotIn('id', $currentMemberIds)
                ->get();
        }

        return view('projects.show', compact('project', 'user', 'availableStudents'));
    }

    public function create()
    {
        $user = Auth::user();

        if (! $user->isSupervisor()) {
            abort(403, 'فقط المشرفون الأكاديميون يملكون صلاحية إنشاء مشاريع تخرج جديدة.');
        }

        $students = User::where('role', 'student')->get();
        return view('projects.create', compact('students'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->isSupervisor()) {
            abort(403, 'فقط المشرفون الأكاديميون يملكون صلاحية إنشاء مشاريع تخرج جديدة.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:15', 'max:2000'],
            'academic_year' => ['required', 'string', 'max:20'],
            'github_repository' => ['nullable', 'url', 'regex:/^https:\/\/(www\.)?github\.com\/.+/i'],
            'leader_student_id' => ['required', 'exists:users,id'],
        ], [
            'title.required' => 'عنوان المشروع مطلوب.',
            'description.required' => 'الوصف المعماري للمشروع مطلوب.',
            'academic_year.required' => 'العام الجامعي مطلوب.',
            'leader_student_id.required' => 'يجب اختيار قائد الفريق من الطلاب المسجلين.',
        ]);

        $slug = Str::slug($validated['title']) . '-' . rand(100, 999);

        $project = Project::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'academic_year' => $validated['academic_year'],
            'github_repository' => $validated['github_repository'] ?? null,
            'supervisor_id' => $user->id,
            'status' => 'in_progress',
            'progress_percentage' => 0,
        ]);

        // Attach leader student
        $project->members()->attach($validated['leader_student_id'], ['role_in_team' => 'leader']);

        ActivityLog::log(
            $project->id,
            $user->id,
            'إنشاء مشروع جديد',
            "قام المشرف {$user->name} بإنشاء مشروع ({$project->title}) وتعيين قائد الفريق."
        );

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم إنشاء وتأسيس مشروع التخرج الجديد بنجاح.');
    }

    public function addMember(Request $request, Project $project)
    {
        $user = Auth::user();

        if (! $user->isSupervisor() || $project->supervisor_id !== $user->id) {
            abort(403, 'غير مصرح لك بإدارة أعضاء هذا المشروع.');
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'role_in_team' => ['required', 'in:leader,member'],
        ]);

        if ($project->members()->where('users.id', $validated['student_id'])->exists()) {
            return back()->with('error', 'هذا الطالب مسجل بالفعل كعضو في هذا المشروع.');
        }

        $project->members()->attach($validated['student_id'], ['role_in_team' => $validated['role_in_team']]);

        $addedStudent = User::find($validated['student_id']);
        ActivityLog::log(
            $project->id,
            $user->id,
            'إضافة عضو للمشروع',
            "تمت إضافة الطالب {$addedStudent->name} إلى فريق العمل."
        );

        return back()->with('success', "تمت إضافة الطالب {$addedStudent->name} إلى المشروع بنجاح.");
    }
}
