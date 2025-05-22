<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Users Card -->
                        <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Users</h3>
                            <p class="text-3xl font-bold text-blue-600">0</p>
                        </div>

                        <!-- Courses Card -->
                        <div class="bg-green-100 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">Total Courses</h3>
                            <p class="text-3xl font-bold text-green-600">0</p>
                        </div>

                        <!-- Revenue Card -->
                        <div class="bg-purple-100 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-purple-800 mb-2">Total Revenue</h3>
                            <p class="text-3xl font-bold text-purple-600">$0</p>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-600">No recent activity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>