<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\UserSkill;
use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $skillCategories = SkillCategory::with('skills')->get();
        $userSkillsCanShare = $user->skillsCanShare()->get()->pluck('skill.id')->toArray();
        $userSkillsNeed = $user->skillsNeed()->get()->pluck('skill.id')->toArray();
        $activeProject = $user->activeProject;
        $archivedProjects = $user->archivedProjects()->orderBy('created_at', 'desc')->get();
        
        return view('profile.edit', compact('user', 'skillCategories', 'userSkillsCanShare', 'userSkillsNeed', 'activeProject', 'archivedProjects'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'job_title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->job_title = $request->job_title;
        $user->bio = $request->bio;

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function updateSkills(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'skills_can_share' => ['array', 'max:5'],
            'skills_can_share.*' => ['exists:skills,id'],
            'skills_need' => ['array', 'max:5'],
            'skills_need.*' => ['exists:skills,id'],
        ]);

        // Update skills I can share
        $user->userSkills()->where('type', 'can_share')->delete();
        if ($request->has('skills_can_share')) {
            foreach ($request->skills_can_share as $skillId) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill_id' => $skillId,
                    'type' => 'can_share'
                ]);
            }
        }

        // Update skills I need
        $user->userSkills()->where('type', 'need')->delete();
        if ($request->has('skills_need')) {
            foreach ($request->skills_need as $skillId) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill_id' => $skillId,
                    'type' => 'need'
                ]);
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Skills updated successfully!');
    }

    public function updateProject(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:idea,designing_mvp,building_mvp,running_mvp,scaling_mvp'],
            'url' => ['nullable', 'url'],
            'weekly_update' => ['nullable', 'string'],
            'open_for_contributors' => ['boolean'],
            'contributors_needed' => ['nullable', 'integer', 'min:1', 'max:10'],
            'contributor_requirements' => ['nullable', 'string']
        ]);

        // Get or create active project
        $project = $user->activeProject;
        if (!$project) {
            $project = new Project();
            $project->user_id = $user->id;
            $project->is_active = true;
        }

        $project->fill($request->only(['title', 'description', 'status', 'url', 'contributor_requirements', 'contributors_needed']));
        $project->open_for_contributors = $request->has('open_for_contributors');
        $project->save();

        // Add weekly update if provided
        if ($request->filled('weekly_update')) {
            ProjectUpdate::create([
                'project_id' => $project->id,
                'content' => $request->weekly_update,
                'week_start_date' => now()->startOfWeek()
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Project updated successfully!');
    }

    public function archiveProject(Request $request)
    {
        $user = Auth::user();
        $project = $user->activeProject;

        if ($project) {
            $project->is_active = false;
            $project->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Project archived successfully!');
    }
}
