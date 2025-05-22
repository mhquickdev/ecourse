@php
    // Dummy data for demonstration
    $earnings = '1,569.00';
    $balance = '3,917.80';
    $sales = '10,211.50';
@endphp

@extends('layouts.mentor')

@section('content')
    <!-- Top Bar -->
    <header class="h-16 bg-white flex items-center px-8 shadow-sm justify-between sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-500">
                <i class="fa-solid fa-dollar-sign text-blue-500"></i>
                <span class="font-semibold">Earnings</span>
                <span class="font-bold text-lg text-gray-800">$12.3K</span>
            </div>
            <div class="flex items-center gap-2 text-gray-500">
                <i class="fa-solid fa-chart-line text-green-500"></i>
                <span class="font-semibold">Sales</span>
                <span class="font-bold text-lg text-gray-800">264</span>
            </div>
            <div class="relative">
                <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50" />
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="relative">
                <i class="fa-regular fa-bell text-xl text-gray-400"></i>
                <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <img src="https://i.pravatar.cc/32" class="rounded-full w-8 h-8 border-2 border-blue-500" alt="User" />
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="flex-1 p-6 md:p-10 bg-gray-50">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-100 to-blue-50 p-6 rounded-xl shadow text-center">
                <div class="text-xs text-blue-700 font-semibold uppercase tracking-wider">Earnings this month</div>
                <div class="text-3xl font-bold mt-2 text-blue-900">${{ $earnings }}</div>
            </div>
            <div class="bg-gradient-to-r from-green-100 to-green-50 p-6 rounded-xl shadow text-center">
                <div class="text-xs text-green-700 font-semibold uppercase tracking-wider">Account Balance</div>
                <div class="text-3xl font-bold mt-2 text-green-900">${{ $balance }}</div>
            </div>
            <div class="bg-gradient-to-r from-purple-100 to-purple-50 p-6 rounded-xl shadow text-center">
                <div class="text-xs text-purple-700 font-semibold uppercase tracking-wider">Total Sales</div>
                <div class="text-3xl font-bold mt-2 text-purple-900">${{ $sales }}</div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chart & Earnings -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="font-semibold text-lg text-gray-800">Earnings</div>
                        <div class="space-x-2">
                            <button class="text-xs px-3 py-1 rounded bg-blue-100 text-blue-700 font-semibold">Previous Week</button>
                            <button class="text-xs px-3 py-1 rounded bg-gray-100 text-gray-700 font-semibold">Last Week</button>
                        </div>
                    </div>
                    <div class="h-48 flex items-center justify-center text-gray-400">
                        <!-- Placeholder for chart -->
                        <span>Generated Income Chart</span>
                    </div>
                </div>
            </div>
            <!-- Transactions & Comments -->
            <div class="flex flex-col gap-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="font-semibold text-lg text-gray-800 mb-4">Transactions</div>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">Learn Angular Fundamentals</span>
                                <span class="block text-xs text-gray-400">Invoice #8737 - $89 USD</span>
                            </div>
                            <span class="text-xs text-gray-400">15 NOV 2018</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">Introduction to TypeScript</span>
                                <span class="block text-xs text-gray-400">Invoice #8736 - $89 USD</span>
                            </div>
                            <span class="text-xs text-gray-400">14 NOV 2018</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">Angular Unit Testing</span>
                                <span class="block text-xs text-gray-400">Invoice #8735 - $89 USD</span>
                            </div>
                            <span class="text-xs text-gray-400">13 NOV 2018</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">Angular Routing In-Depth</span>
                                <span class="block text-xs text-gray-400">Invoice #8734 - $89 USD</span>
                            </div>
                            <span class="text-xs text-gray-400">12 NOV 2018</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="font-semibold text-lg text-gray-800 mb-4">Comments</div>
                    <ul class="space-y-4">
                        <li>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Laza Bogdan</span>
                                <span class="text-xs text-gray-400">27 min ago</span>
                            </div>
                            <div class="text-xs text-gray-600">How can I load Charts on a page?</div>
                        </li>
                        <li>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">FrontendMatter</span>
                                <span class="text-xs text-gray-400">just now</span>
                            </div>
                            <div class="text-xs text-gray-600">Thank you for purchasing our course! Please have a look at the charts library documentation <a href="#" class="text-blue-500 underline">here</a> and follow the instructions.</div>
                        </li>
                    </ul>
                    <div class="mt-4 flex">
                        <input type="text" class="flex-1 border rounded-l px-3 py-2 text-sm focus:outline-none" placeholder="Quick Reply">
                        <button class="bg-blue-500 text-white px-4 rounded-r">Send</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-xs text-gray-400">
            Luma is a beautifully crafted user interface for modern Education Platforms, including Courses & Tutorials, Video Lessons, Student and Teacher Dashboard, Curriculum Management, Earnings and Reporting, and more.<br>
            Terms. Privacy policy. Copyright 2024. All rights reserved.
        </div>
    </main>
@endsection 