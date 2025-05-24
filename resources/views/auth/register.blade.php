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
    <!-- Right Section (Register Form) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-[#2D2363]">Student Sign Up</h1>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role_id" value="2">
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Full Name *')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email *')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-input-label for="password" :value="__('New Password *')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password *')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="agree_terms" name="agree_terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                    <label for="agree_terms" class="ms-2 text-sm text-gray-600">
                        I agree with <a href="#" class="underline text-[#F85A7E] hover:text-[#e13a5e]">Terms of Service</a> and <a href="#" class="underline text-[#F85A7E] hover:text-[#e13a5e]">Privacy Policy</a>
                    </label>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="w-full bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#e13a5e] transition">
                        {{ __('Sign Up >') }}
                    </button>
                </div>
            </form>
            <div class="flex flex-col items-center mt-6 gap-2">
                <a href="{{ route('mentor.register') }}" class="w-full bg-[#2D2363] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#1a153a] transition text-center">Become an Instructor</a>
                <div class="text-center text-sm text-gray-600">
                    Already you have an account? <a href="{{ route('login') }}" class="underline text-[#F85A7E] hover:text-[#e13a5e]">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
