<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EdgeCaseController extends Controller
{
    public function index()
    {
        $project = Project::first();
        $milestone = Milestone::where('status', 'needs_revision')->first() ?? Milestone::first();
        $users = User::all();

        return view('edge-cases.index', compact('project', 'milestone', 'users'));
    }
}
