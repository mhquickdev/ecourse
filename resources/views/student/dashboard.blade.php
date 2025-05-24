@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Student Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Enrolled Courses -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-600">No courses enrolled yet</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Course Progress</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-blue-600 mt-2">0% Complete</p>
                    </div>

                    <div class="bg-green-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">Certificates</h3>
                        <p class="text-3xl font-bold text-green-600">0</p>
                        <p class="text-sm text-green-600">Certificates earned</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 