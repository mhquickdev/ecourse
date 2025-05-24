@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">Add Category</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search form --}}
    <div class="mb-6">
        <form action="{{ route('admin.categories.index') }}" method="GET">
            <div class="flex items-center border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="flex-1 px-4 py-2 focus:outline-none focus:ring-0 border-none">
                <button type="submit" class="bg-gray-50 px-4 py-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Created At
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Actions --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $category->name }}</p>
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $category->slug }}</p>
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $category->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right space-x-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-5">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection 