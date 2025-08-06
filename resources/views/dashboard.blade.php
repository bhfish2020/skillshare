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
                                User Info
                            </a>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- User Info Tab -->
                        <div id="user-info" class="tab-content hidden">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">User Information</h2>
                                <a href="{{ route('profile.edit') }}" 
                                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit Profile
                                </a>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                        <dl class="space-y-3">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                                <dd class="text-sm text-gray-900">{{ Auth::user()->name }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                                <dd class="text-sm text-gray-900">{{ Auth::user()->email }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                                                <dd class="text-sm text-gray-900">{{ Auth::user()->job_title ?: 'Not specified' }}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bio</h3>
                                        <div class="text-sm text-gray-900">
                                            @if(Auth::user()->bio)
                                                {{ Auth::user()->bio }}
                                            @else
                                                <span class="text-gray-500 italic">No bio added yet.</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                            <dd class="text-sm text-gray-900">{{ Auth::user()->created_at->format('F j, Y') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                            <dd class="text-sm text-gray-900">
                                                @if(Auth::user()->email_verified_at)
                                                    <span class="text-green-600">✓ Verified</span>
                                                @else
                                                    <span class="text-red-600">✗ Not verified</span>
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Feed Tab -->
                        <div id="feed" class="tab-content">
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold">Feed</h2>
                                <p class="text-gray-600">See what everyone is sharing</p>
                            </div>
                            
                            <!-- Create Post Form -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Create a Post</h3>
                                
                                @if (session('success'))
                                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('posts.store') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea 
                                            name="text" 
                                            rows="3" 
                                            placeholder="What's on your mind?"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            required
                                        >{{ old('text') }}</textarea>
                                        @error('text')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" 
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Post
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            @php
                                $posts = \App\Models\Post::with('user')->orderBy('created_at', 'desc')->get();
                            @endphp
                            
                            @if($posts->count() > 0)
                                <div class="space-y-6">
                                    @foreach($posts as $post)
                                        <div class="bg-gray-50 rounded-lg p-6">
                                            <div class="flex items-start space-x-4">
                                                <!-- User Avatar -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white font-semibold text-sm">
                                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <h3 class="text-lg font-semibold text-gray-900">
                                                            {{ $post->user->name }}
                                                        </h3>
                                                        @if($post->user->job_title)
                                                            <span class="text-sm text-gray-500">•</span>
                                                            <span class="text-sm text-gray-500">{{ $post->user->job_title }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <p class="text-gray-800 text-base leading-relaxed mb-3">
                                                        {{ $post->text }}
                                                    </p>
                                                    
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $post->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No posts yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Be the first to share something!</p>
                                </div>
                            @endif
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