<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

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
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ config('app.name', 'Laravel') }}
                        </h1>
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="#feed" 
                               class="tab-link border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active"
                               data-tab="feed">
                                Feed
                            </a>    
                        
                            <!-- space between tabs -->
                            <div class="w-4"></div>
                            <a href="#user-info" 
                               class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                               data-tab="user-info">
                                My Profile
                            </a>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- My Profile Tab -->
                        <div id="user-info" class="tab-content hidden">
                            @php
                                $user = Auth::user();
                                $skillsCanShare = $user->skillsCanShare()->get();
                                $skillsNeed = $user->skillsNeed()->get();
                                $activeProject = $user->activeProject;
                                $archivedProjects = $user->archivedProjects()->take(5)->get();
                            @endphp
                            
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">My Profile</h2>
                                <a href="{{ route('profile.edit') }}" 
                                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit Profile
                                </a>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Profile Header -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center space-x-6">
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 bg-indigo-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold text-2xl">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                                            @if($user->job_title)
                                                <p class="text-gray-600">{{ $user->job_title }}</p>
                                            @endif
                                            @if($user->bio)
                                                <p class="text-gray-700 mt-2">{{ $user->bio }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Skills Section -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Skills</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="text-md font-medium text-gray-800 mb-3">Skills I Can Share</h4>
                                            @if($skillsCanShare->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($skillsCanShare as $userSkill)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                            {{ $userSkill->skill->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 text-sm">No skills added yet.</p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-md font-medium text-gray-800 mb-3">Skills I Need</h4>
                                            @if($skillsNeed->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($skillsNeed as $userSkill)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            {{ $userSkill->skill->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 text-sm">No skills added yet.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Project -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Project</h3>
                                    @if($activeProject)
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-start">
                                                <h4 class="text-md font-medium text-gray-800">{{ $activeProject->title }}</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $activeProject->status_display }}
                                                </span>
                                            </div>
                                            @if($activeProject->description)
                                                <p class="text-gray-600 text-sm">{{ $activeProject->description }}</p>
                                            @endif
                                            @if($activeProject->url)
                                                <p class="text-sm">
                                                    <a href="{{ $activeProject->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                                        View Project →
                                                    </a>
                                                </p>
                                            @endif
                                            
                                            <!-- Recent Updates -->
                                            @php
                                                $recentUpdates = $activeProject->updates()->orderBy('created_at', 'desc')->take(3)->get();
                                            @endphp
                                            @if($recentUpdates->count() > 0)
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Recent Updates</h5>
                                                    <div class="space-y-2">
                                                        @foreach($recentUpdates as $update)
                                                            <div class="text-sm">
                                                                <span class="text-gray-500">{{ $update->week_start_date->format('M j') }}:</span>
                                                                <span class="text-gray-700">{{ Str::limit($update->content, 100) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm">No active project. Start one in your profile settings!</p>
                                    @endif
                                </div>

                                <!-- Past Projects Archives -->
                                @if($archivedProjects->count() > 0)
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Past Project Archives</h3>
                                        <div class="space-y-4">
                                            @foreach($archivedProjects as $project)
                                                <div class="border-l-4 border-gray-300 pl-4">
                                                    <div class="flex justify-between items-start">
                                                        <h4 class="text-md font-medium text-gray-800">{{ $project->title }}</h4>
                                                        <span class="text-xs text-gray-500">{{ $project->updated_at->format('M Y') }}</span>
                                                    </div>
                                                    @if($project->description)
                                                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($project->description, 150) }}</p>
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
                                @endif
                            </div>
                        </div>

                        <!-- Feed Tab -->
                        <div id="feed" class="tab-content">
                            @php
                                $currentUser = Auth::user();
                                $skillMatches = $currentUser->findSkillMatches(5);
                                $openProjects = \App\Models\Project::getOpenOpportunities(10);
                            @endphp

                            <div class="mb-6">
                                <h2 class="text-2xl font-bold">Discover</h2>
                                <p class="text-gray-600">Find people with skills you need and join exciting projects</p>
                            </div>
                            
                            <!-- Success/Error Messages -->
                            @if (session('success'))
                                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if (session('error'))
                                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Browse People Section -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">People with Skills You Need</h3>
                                        <p class="text-sm text-gray-600">Connect with people who can help you learn and grow</p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        🔄 New matches weekly
                                    </div>
                                </div>
                                
                                @if($skillMatches->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($skillMatches as $match)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex items-center space-x-3 mb-3">
                                                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white font-semibold">
                                                            {{ strtoupper(substr($match->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">{{ $match->name }}</h4>
                                                        @if($match->job_title)
                                                            <p class="text-sm text-gray-600">{{ $match->job_title }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Skills they can share -->
                                                <div class="mb-3">
                                                    <p class="text-xs font-medium text-gray-700 mb-2">Can share:</p>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($match->userSkills->where('type', 'can_share')->take(3) as $userSkill)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                {{ $userSkill->skill->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Current project if any -->
                                                @if($match->activeProject)
                                                    <div class="mb-3">
                                                        <p class="text-xs font-medium text-gray-700 mb-1">Working on:</p>
                                                        <p class="text-sm text-gray-600">{{ Str::limit($match->activeProject->title, 40) }}</p>
                                                    </div>
                                                @endif
                                                
                                                <!-- Invite Button -->
                                                <form method="POST" action="{{ route('invitations.send') }}" class="mt-4">
                                                    @csrf
                                                    <input type="hidden" name="receiver_id" value="{{ $match->id }}">
                                                    <button type="submit" 
                                                        class="w-full px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        💬 Invite to Connect
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="text-gray-400 mb-2">🔍</div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">No skill matches found</h4>
                                        <p class="text-sm text-gray-500">Add skills you need in your profile to find people who can help!</p>
                                        <a href="{{ route('profile.edit') }}" 
                                           class="mt-3 inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                            Edit Profile →
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Project Opportunities Section -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Open Project Opportunities</h3>
                                        <p class="text-sm text-gray-600">Join projects as a contributor and build together</p>
                                    </div>
                                </div>
                                
                                @if($openProjects->count() > 0)
                                    <div class="space-y-6">
                                        @foreach($openProjects as $project)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">{{ $project->title }}</h4>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                {{ $project->status_display }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                                            <div class="flex items-center space-x-1">
                                                                <div class="w-6 h-6 bg-gray-500 rounded-full flex items-center justify-center">
                                                                    <span class="text-white text-xs font-semibold">
                                                                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                                <span>{{ $project->user->name }}</span>
                                                            </div>
                                                            <span>•</span>
                                                            <span>{{ $project->contributors_needed }} contributor{{ $project->contributors_needed > 1 ? 's' : '' }} needed</span>
                                                        </div>
                                                        
                                                        @if($project->description)
                                                            <p class="text-gray-700 text-sm mb-3">{{ Str::limit($project->description, 200) }}</p>
                                                        @endif
                                                        
                                                        @if($project->contributor_requirements)
                                                            <div class="mb-3">
                                                                <p class="text-xs font-medium text-gray-700 mb-1">Looking for:</p>
                                                                <p class="text-sm text-gray-600">{{ $project->contributor_requirements }}</p>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($project->updates->count() > 0)
                                                            <div class="bg-gray-50 rounded p-3 mb-3">
                                                                <p class="text-xs font-medium text-gray-700 mb-1">Latest Update:</p>
                                                                <p class="text-sm text-gray-600">{{ Str::limit($project->updates->first()->content, 150) }}</p>
                                                                <p class="text-xs text-gray-500 mt-1">{{ $project->updates->first()->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="flex items-center space-x-3">
                                                            @if($project->url)
                                                                <a href="{{ $project->url }}" target="_blank" 
                                                                   class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                                                    View Project →
                                                                </a>
                                                            @endif
                                                            
                                                            <form method="POST" action="{{ route('invitations.send') }}" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="receiver_id" value="{{ $project->user->id }}">
                                                                <input type="hidden" name="message" value="Hi! I'm interested in contributing to your project '{{ $project->title }}'. Would love to discuss how I can help!">
                                                                <button type="submit" 
                                                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                    🚀 Request to Join
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="text-gray-400 mb-2">🚀</div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">No open projects yet</h4>
                                        <p class="text-sm text-gray-500">Check back later for new collaboration opportunities!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabLinks.forEach(l => {
                        l.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                        l.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                    
                    // Show corresponding content
                    const targetTab = this.getAttribute('data-tab');
                    document.getElementById(targetTab).classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html> 