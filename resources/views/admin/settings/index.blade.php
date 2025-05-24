@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Settings form fields will go here --}}
            <p class="text-gray-600 mb-4">Settings form fields will be added here.</p>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 