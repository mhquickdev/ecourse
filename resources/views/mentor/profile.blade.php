@extends('layouts.mentor')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Mentor Profile Settings
    </h2>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-8 px-8">
    <div class="max-w-3xl w-full">
       
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('mentor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 rounded-2xl shadow-xl" x-data="mentorProfileForm()" x-init="initImagePreview()">
            @csrf
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    <img :src="imagePreview || '{{ $mentor->profile_image ? Storage::url($mentor->profile_image) : 'https://i.pravatar.cc/120' }}'" class="w-24 h-24 rounded-full border-4 border-blue-200 object-cover shadow" alt="Profile Image">
                    <label class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer shadow-lg hover:bg-blue-700 transition" title="Change profile image">
                        <i class="fa-solid fa-camera"></i>
                        <input type="file" name="profile_image" class="hidden" accept="image/*" @change="previewImage($event)">
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $mentor->first_name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $mentor->last_name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">User Name *</label>
                    <input type="text" name="username" value="{{ old('username', $mentor->username) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $mentor->phone) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1">Bio *</label>
                <textarea name="bio" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>{{ old('bio', $mentor->bio) }}</textarea>
            </div>
            <!-- Education Section -->
            <div x-data="{ education: {{ json_encode(old('education', $mentor->education ?? [])) }} }" class="mb-6">
                <label class="block font-semibold mb-1">Education</label>
                <template x-for="(item, index) in education" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'education['+index+'][degree]'" x-model="item.degree" placeholder="Degree" class="border rounded px-2 py-1 w-1/4">
                        <input type="text" :name="'education['+index+'][institution]'" x-model="item.institution" placeholder="Institution" class="border rounded px-2 py-1 w-1/4">
                        <input type="text" :name="'education['+index+'][from]'" x-model="item.from" placeholder="From (Year)" class="border rounded px-2 py-1 w-1/6">
                        <input type="text" :name="'education['+index+'][to]'" x-model="item.to" placeholder="To (Year)" class="border rounded px-2 py-1 w-1/6">
                        <button type="button" @click="education.splice(index, 1)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="education.push({degree: '', institution: '', from: '', to: ''})" class="mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"><i class="fa-solid fa-plus"></i> Add Education</button>
            </div>
            <!-- Experience Section -->
            <div x-data="{ experience: {{ json_encode(old('experience', $mentor->experience ?? [])) }} }" class="mb-6">
                <label class="block font-semibold mb-1">Experience</label>
                <template x-for="(item, index) in experience" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'experience['+index+'][title]'" x-model="item.title" placeholder="Title" class="border rounded px-2 py-1 w-1/4">
                        <input type="text" :name="'experience['+index+'][company]'" x-model="item.company" placeholder="Company" class="border rounded px-2 py-1 w-1/4">
                        <input type="text" :name="'experience['+index+'][from]'" x-model="item.from" placeholder="From (Year)" class="border rounded px-2 py-1 w-1/6">
                        <input type="text" :name="'experience['+index+'][to]'" x-model="item.to" placeholder="To (Year)" class="border rounded px-2 py-1 w-1/6">
                        <button type="button" @click="experience.splice(index, 1)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="experience.push({title: '', company: '', from: '', to: ''})" class="mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"><i class="fa-solid fa-plus"></i> Add Experience</button>
            </div>
            <!-- Skills Section -->
            <div x-data="{ skills: {{ json_encode(old('skills', $mentor->skills ?? [])) }} }" class="mb-6">
                <label class="block font-semibold mb-1">Skills</label>
                <template x-for="(skill, index) in skills" :key="index">
                    <div class="flex gap-2 mb-2 items-center">
                        <input type="text" :name="'skills['+index+'][name]'" x-model="skill.name" placeholder="Skill" class="border rounded px-2 py-1 w-1/2">
                        <div class="flex items-center">
                            <template x-for="star in 5">
                                <button type="button" :class="{'text-yellow-400': star <= skill.rating, 'text-gray-300': star > skill.rating}" @click="skill.rating = star" class="focus:outline-none">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            </template>
                            <input type="hidden" :name="'skills['+index+'][rating]'" x-model="skill.rating">
                        </div>
                        <button type="button" @click="skills.splice(index, 1)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="skills.push({name: '', rating: 0})" class="mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"><i class="fa-solid fa-plus"></i> Add Skill</button>
            </div>
            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-semibold text-lg shadow">Update Profile</button>
            </div>
        </form>
        <!-- Password Reset Section -->
        <form action="{{ route('mentor.profile.update') }}" method="POST" class="mt-10 bg-white p-8 rounded-2xl shadow-xl space-y-6">
            @csrf
            <h3 class="text-lg font-bold text-gray-800 mb-4">Reset Password</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Current Password</label>
                    <input type="password" name="current_password" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">New Password</label>
                    <input type="password" name="new_password" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-semibold text-lg shadow">Reset Password</button>
            </div>
        </form>
    </div>
</div>
<script>
    function mentorProfileForm() {
        return {
            imagePreview: '',
            previewImage(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            initImagePreview() {
                this.imagePreview = '';
            }
        }
    }
</script>
@endsection 