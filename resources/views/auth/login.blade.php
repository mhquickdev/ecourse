@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Left Section (Image and Welcome Text) -->
    <div class="w-1/2 bg-[#fff7f7] flex items-center justify-center p-10 hidden lg:flex">
        <div class="text-center">
            <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/auth/auth-1.svg" alt="Welcome Illustration" class="mx-auto mb-6 max-w-sm">
            <h2 class="text-3xl font-bold text-[#2D2363] mb-3">Welcome to QuickLMS Courses.</h2>
            <p class="text-gray-600 max-w-md mx-auto">Platform designed to help organizations, educators, and learners manage, deliver, and track learning and training activities.</p>
        </div>
    </div>
    <!-- Right Section (Login Form) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                
                <h1 class="text-3xl font-bold text-[#2D2363]">Sign In</h1>
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email *')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password *')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <label for="remember_me" class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="w-full bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#e13a5e] transition">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
            <div class="text-center mt-6 text-sm text-gray-600">
                Don't have an account? <a href="{{ route('register') }}" class="underline text-[#F85A7E] hover:text-[#e13a5e]">Sign up</a>
            </div>
        </div>
    </div>
</div>
@endsection
