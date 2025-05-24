@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8 mb-8">
            <!-- Stat Card: Enrolled Courses -->
            <div class="flex-1 bg-blue-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-200 rounded-xl mr-4">
                    <i class="fa-solid fa-book-open text-3xl text-blue-500"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-blue-600">Enrolled Courses</div>
                    <div class="text-2xl font-extrabold text-blue-800">3</div>
                </div>
                <span class="absolute top-4 right-4 bg-blue-500 text-white text-xs font-bold px-4 py-1 rounded-full">Total</span>
            </div>
            <!-- Stat Card: Active Courses -->
            <div class="flex-1 bg-green-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-green-200 rounded-xl mr-4">
                    <i class="fa-solid fa-bolt text-3xl text-green-600"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-green-600">Active Courses</div>
                    <div class="text-2xl font-extrabold text-green-800">2</div>
                </div>
                <span class="absolute top-4 right-4 bg-green-600 text-white text-xs font-bold px-4 py-1 rounded-full">Active</span>
            </div>
            <!-- Stat Card: Completed Courses -->
            <div class="flex-1 bg-yellow-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-200 rounded-xl mr-4">
                    <i class="fa-solid fa-check-circle text-3xl text-yellow-500"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-yellow-600">Completed Courses</div>
                    <div class="text-2xl font-extrabold text-yellow-800">1</div>
                </div>
                <span class="absolute top-4 right-4 bg-yellow-500 text-white text-xs font-bold px-4 py-1 rounded-full">Completed</span>
            </div>
        </div>
        <!-- Enrolled Courses List -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Enrolled Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('components.course-card', [
                    'image' => 'https://img-c.udemycdn.com/course/480x270/1565838_e54e_16.jpg',
                    'discount' => '15% off',
                    'instructor_avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                    'instructor' => 'Brenda Slaton',
                    'category' => 'Design',
                    'title' => 'Information About UI/UX Design Degree',
                    'rating' => '4.9',
                    'reviews' => '200',
                    'price' => '120',
                    'url' => '#',
                ])
                @include('components.course-card', [
                    'image' => 'https://img-c.udemycdn.com/course/480x270/246154_d8b0_3.jpg',
                    'discount' => null,
                    'instructor_avatar' => 'https://randomuser.me/api/portraits/women/45.jpg',
                    'instructor' => 'Ana Reyes',
                    'category' => 'Wordpress',
                    'title' => 'Wordpress for Beginners - Master Wordpress Quickly',
                    'rating' => '4.4',
                    'reviews' => '160',
                    'price' => '140',
                    'url' => '#',
                ])
                @include('components.course-card', [
                    'image' => 'https://img-c.udemycdn.com/course/480x270/1565838_e54e_16.jpg',
                    'discount' => null,
                    'instructor_avatar' => 'https://randomuser.me/api/portraits/men/46.jpg',
                    'instructor' => 'Andrew Pirtle',
                    'category' => 'Design',
                    'title' => 'Sketch from A to Z (2024): Become an app designer',
                    'rating' => '4.4',
                    'reviews' => '160',
                    'price' => '140',
                    'url' => '#',
                ])
            </div>
        </div>
    </div>
</div>
@endsection 