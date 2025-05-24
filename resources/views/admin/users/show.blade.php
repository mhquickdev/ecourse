@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">User Details: {{ $user->name }}</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <p><span class="font-bold text-gray-700">Name:</span> {{ $user->name }}</p>
        </div>
        <div class="mb-4">
            <p><span class="font-bold text-gray-700">Email:</span> {{ $user->email }}</p>
        </div>
        <div class="mb-4">
            <p><span class="font-bold text-gray-700">Joined:</span> {{ $user->created_at->format('M d, Y H:i A') }}</p>
        </div>
        {{-- Add more user details as needed --}}

        <div class="mt-6">
            <a href="{{ route('admin.users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Back to Users List
            </a>
        </div>
    </div>
</div>
@endsection 