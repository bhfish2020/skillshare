<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Edit Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-semibold text-gray-900 hover:text-gray-700">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">
                            Welcome, {{ Auth::user()->name }}!
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-500">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="mt-2 text-gray-600">
                        Manage your profile information, skills, and current project.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-8">
                    <!-- Basic Profile Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                            
                            @if ($errors->any())
                                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name
                                        </label>
                                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address
                                        </label>
                                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Job Title
                                        </label>
                                        <input id="job_title" type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="e.g., Software Developer, Marketing Manager">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                            Bio
                                        </label>
                                        <textarea id="bio" name="bio" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md">
                                        Update Basic Info
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Skills Management -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Skills Management</h2>
                            
                            <form method="POST" action="{{ route('profile.skills.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Skills I Can Share -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Skills I Can Share <span class="text-sm text-gray-500">(Max 5)</span></h3>
                                        
                                        @foreach($skillCategories as $category)
                                            <div class="mb-6">
                                                <h4 class="text-md font-medium text-gray-800 mb-3">{{ $category->name }}</h4>
                                                <div class="grid grid-cols-1 gap-2">
                                                    @foreach($category->skills as $skill)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                name="skills_can_share[]" 
                                                                value="{{ $skill->id }}"
                                                                {{ in_array($skill->id, $userSkillsCanShare) ? 'checked' : '' }}
                                                                class="skills-can-share h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $skill->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Skills I Need -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Skills I Need <span class="text-sm text-gray-500">(Max 5)</span></h3>
                                        
                                        @foreach($skillCategories as $category)
                                            <div class="mb-6">
                                                <h4 class="text-md font-medium text-gray-800 mb-3">{{ $category->name }}</h4>
                                                <div class="grid grid-cols-1 gap-2">
                                                    @foreach($category->skills as $skill)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                name="skills_need[]" 
                                                                value="{{ $skill->id }}"
                                                                {{ in_array($skill->id, $userSkillsNeed) ? 'checked' : '' }}
                                                                class="skills-need h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $skill->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md">
                                        Update Skills
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Project Management -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Current Project</h2>
                                @if($activeProject)
                                    <form method="POST" action="{{ route('profile.project.archive') }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                            onclick="return confirm('Are you sure you want to archive this project?')"
                                            class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                            Archive Project
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <form method="POST" action="{{ route('profile.project.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 gap-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                                Project Title
                                            </label>
                                            <input id="title" type="text" name="title" 
                                                value="{{ old('title', $activeProject->title ?? '') }}" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="My Awesome Project">
                                        </div>

                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                                Status
                                            </label>
                                            <select id="status" name="status" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select Status</option>
                                                <option value="idea" {{ old('status', $activeProject->status ?? '') == 'idea' ? 'selected' : '' }}>Idea</option>
                                                <option value="designing_mvp" {{ old('status', $activeProject->status ?? '') == 'designing_mvp' ? 'selected' : '' }}>Designing MVP</option>
                                                <option value="building_mvp" {{ old('status', $activeProject->status ?? '') == 'building_mvp' ? 'selected' : '' }}>Building MVP</option>
                                                <option value="running_mvp" {{ old('status', $activeProject->status ?? '') == 'running_mvp' ? 'selected' : '' }}>Running MVP</option>
                                                <option value="scaling_mvp" {{ old('status', $activeProject->status ?? '') == 'scaling_mvp' ? 'selected' : '' }}>Scaling MVP</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Project Description
                                        </label>
                                        <textarea id="description" name="description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Describe your project...">{{ old('description', $activeProject->description ?? '') }}</textarea>
                                    </div>

                                    <div>
                                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                            Project URL (Optional)
                                        </label>
                                        <input id="url" type="url" name="url" 
                                            value="{{ old('url', $activeProject->url ?? '') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="https://myproject.com">
                                    </div>

                                    <!-- Contributor Settings -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h4 class="text-md font-medium text-blue-900 mb-3">Looking for Contributors?</h4>
                                        
                                        <div class="space-y-3">
                                            <div class="flex items-center">
                                                <input id="open_for_contributors" type="checkbox" name="open_for_contributors" 
                                                    value="1" {{ old('open_for_contributors', $activeProject->open_for_contributors ?? false) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="open_for_contributors" class="ml-2 block text-sm text-gray-900">
                                                    My project is open for contributors
                                                </label>
                                            </div>
                                            
                                            <div>
                                                <label for="contributors_needed" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Contributors Needed
                                                </label>
                                                <input id="contributors_needed" type="number" name="contributors_needed" min="1" max="10"
                                                    value="{{ old('contributors_needed', $activeProject->contributors_needed ?? 1) }}"
                                                    class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div>
                                                <label for="contributor_requirements" class="block text-sm font-medium text-gray-700 mb-1">
                                                    What skills are you looking for?
                                                </label>
                                                <textarea id="contributor_requirements" name="contributor_requirements" rows="2"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="e.g., React developers, UI/UX designers, marketing help...">{{ old('contributor_requirements', $activeProject->contributor_requirements ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="weekly_update" class="block text-sm font-medium text-gray-700 mb-2">
                                            Weekly Update (Optional)
                                        </label>
                                        <textarea id="weekly_update" name="weekly_update" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="What did you work on this week?">{{ old('weekly_update') }}</textarea>
                                        <p class="mt-1 text-sm text-gray-500">This will be added as a new update for this week.</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md">
                                        {{ $activeProject ? 'Update Project' : 'Create Project' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Past Project Archives (Read-only) -->
                    @if($archivedProjects->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-6">Past Project Archives</h2>
                                <div class="space-y-4">
                                    @foreach($archivedProjects as $project)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $project->title }}</h3>
                                                <span class="text-sm text-gray-500">Completed {{ $project->updated_at->format('M Y') }}</span>
                                            </div>
                                            @if($project->description)
                                                <p class="text-gray-600 mb-2">{{ $project->description }}</p>
                                            @endif
                                            @if($project->url)
                                                <a href="{{ $project->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 text-sm">
                                                    View Project →
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <div class="flex justify-center">
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Limit skills selection to 5 each
        document.addEventListener('DOMContentLoaded', function() {
            const canShareCheckboxes = document.querySelectorAll('.skills-can-share');
            const needCheckboxes = document.querySelectorAll('.skills-need');

            function limitCheckboxes(checkboxes, limit = 5) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedBoxes = document.querySelectorAll(`input[name="${this.name}"]:checked`);
                        if (checkedBoxes.length > limit) {
                            this.checked = false;
                            alert(`You can only select up to ${limit} skills.`);
                        }
                    });
                });
            }

            limitCheckboxes(canShareCheckboxes, 5);
            limitCheckboxes(needCheckboxes, 5);
        });
    </script>
</body>
</html>