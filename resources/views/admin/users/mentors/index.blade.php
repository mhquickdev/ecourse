@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mentors / Authors</h1>
        {{-- Add Mentor button if applicable --}}
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search form --}}
    <div class="mb-6">
        <form action="{{ route('admin.users.mentors.index') }}" method="GET">
            <div class="flex items-center border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search mentors/authors..." class="flex-1 px-4 py-2 focus:outline-none focus:ring-0 border-none">
                <button type="submit" class="bg-gray-50 px-4 py-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- Mentors/Authors Section --}}
    <div class="mb-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                             <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Joined Date
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Actions --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mentors as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right space-x-3">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">View</a>
                                    {{-- Add Edit/Delete links if applicable --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600">
                                    No mentors/authors found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mentors Pagination --}}
            <div class="p-5">
                {{ $mentors->links() }}
            </div>
        </div>
    </div>

</div>
@endsection 