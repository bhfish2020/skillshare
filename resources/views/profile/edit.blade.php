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
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Edit Profile</h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Update your personal information and account settings.
                            </p>
                        </div>

                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
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

                            <div class="space-y-6">
                                <!-- Basic Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                                    </div>
                                </div>

                                <!-- Professional Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">
                                                Job Title
                                            </label>
                                            <input id="job_title" type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="e.g., Software Developer, Marketing Manager">
                                        </div>

                                        <div>
                                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                                Bio
                                            </label>
                                            <textarea id="bio" name="bio" rows="4"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Change -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                                    <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password.</p>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                                Current Password
                                            </label>
                                            <input id="current_password" type="password" name="current_password"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                                    New Password
                                                </label>
                                                <input id="new_password" type="password" name="new_password"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>

                                            <div>
                                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Confirm New Password
                                                </label>
                                                <input id="new_password_confirmation" type="password" name="new_password_confirmation"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('dashboard') }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 