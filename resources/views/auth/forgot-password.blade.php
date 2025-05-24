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
    <!-- Right Section (Forgot Password Form) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                
                <h1 class="text-3xl font-bold text-[#2D2363]">Forgot Password</h1>
            </div>
            <div class="mb-4 text-sm text-gray-600 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email *')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="w-full bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#e13a5e] transition">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>
            <div class="text-center mt-6 text-sm text-gray-600">
                Remember your password? <a href="{{ route('login') }}" class="underline text-[#F85A7E] hover:text-[#e13a5e]">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
