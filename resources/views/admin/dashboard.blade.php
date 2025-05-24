@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
            <p class="text-gray-500">Overview of your platform's key metrics and recent activity.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <!-- Users Card -->
            <div class="relative bg-blue-100 rounded-2xl shadow-lg p-6 flex items-center min-w-[260px]">
                <div class="bg-blue-200 rounded-xl p-4 mr-4 flex items-center justify-center">
                    <i class="fa fa-users text-3xl text-blue-500"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="font-bold text-blue-700 text-lg">Total Users</span>
                        <span class="ml-2 px-2 py-0.5 rounded-full bg-blue-500 text-white text-xs font-semibold">Total</span>
                    </div>
                    <div class="text-2xl font-bold text-blue-700">{{ $totalUsers ?? 0 }}</div>
                </div>
            </div>
            <!-- Courses Card -->
            <div class="relative bg-green-100 rounded-2xl shadow-lg p-6 flex items-center min-w-[260px]">
                <div class="bg-green-200 rounded-xl p-4 mr-4 flex items-center justify-center">
                    <i class="fa fa-book text-3xl text-green-500"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="font-bold text-green-700 text-lg">Active Courses</span>
                        <span class="ml-2 px-2 py-0.5 rounded-full bg-green-600 text-white text-xs font-semibold">Active</span>
                    </div>
                    <div class="text-2xl font-bold text-green-700">{{ $totalCourses ?? 0 }}</div>
                </div>
            </div>
            <!-- Categories Card -->
            <div class="relative bg-yellow-100 rounded-2xl shadow-lg p-6 flex items-center min-w-[260px]">
                <div class="bg-yellow-200 rounded-xl p-4 mr-4 flex items-center justify-center">
                    <i class="fa fa-tags text-3xl text-yellow-500"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="font-bold text-yellow-700 text-lg">Total Categories</span>
                        <span class="ml-2 px-2 py-0.5 rounded-full bg-yellow-500 text-white text-xs font-semibold">Total</span>
                    </div>
                    <div class="text-2xl font-bold text-yellow-700">{{ $totalCategories ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Recent Users</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
                </div>
                <ul>
                    @forelse($recentUsers as $user)
                        <li class="flex items-center gap-3 py-2 border-b last:border-b-0">
                            <i class="fa fa-user-circle text-blue-400 text-xl"></i>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                            <span class="ml-auto text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">No recent users.</li>
                    @endforelse
                </ul>
            </div>
            <!-- Recent Courses -->
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Recent Courses</h2>
                    <a href="{{ route('admin.courses.index') }}" class="text-sm font-semibold text-green-600 hover:underline">View All</a>
                </div>
                <ul>
                    @forelse($recentCourses as $course)
                        <li class="flex items-center gap-3 py-2 border-b last:border-b-0">
                            <i class="fa fa-book text-green-400 text-xl"></i>
                            <span class="font-semibold text-gray-800">{{ $course->title }}</span>
                            <span class="ml-auto text-xs text-gray-400">{{ $course->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">No recent courses.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
