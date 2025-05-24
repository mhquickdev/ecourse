@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Courses</h1>
        {{-- Add Course button if applicable --}}
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search and Filter form --}}
    <div class="mb-6">
        <form id="course-filter-form" action="{{ route('admin.courses.index') }}" method="GET" class="flex items-center space-x-4">
            <div class="flex items-center border border-gray-300 rounded-lg shadow-sm overflow-hidden flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..." class="flex-1 px-4 py-2 focus:outline-none focus:ring-0 border-none">
                <button type="submit" class="bg-gray-50 px-4 py-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            {{-- Status Filter --}}
            <div>
                <select name="status" id="status-filter" class="form-select mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-3">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            {{-- Category Filter --}}
            <div>
                <select name="category" id="category-filter" class="form-select mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-3">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Author
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Created At
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Actions --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $course->title }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $course->status === 'published' ? 'bg-green-200 text-green-900' : ($course->status === 'draft' ? 'bg-yellow-200 text-yellow-900' : 'bg-red-200 text-red-900') }}"></span>
                                    <span class="relative">{{ ucfirst($course->status) }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $course->category->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $course->user->name ?? 'N/A' }}</p>
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $course->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right space-x-3">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Edit</a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600">
                                No courses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-5">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('status-filter').addEventListener('change', function() {
    document.getElementById('course-filter-form').submit();
});

document.getElementById('category-filter').addEventListener('change', function() {
    document.getElementById('course-filter-form').submit();
});

// Optional: Auto-submit on search input keyup with a delay
let searchTimeout = null;
document.querySelector('input[name="search"]').addEventListener('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        document.getElementById('course-filter-form').submit();
    }, 500); // 500ms delay
});
</script>
@endpush 