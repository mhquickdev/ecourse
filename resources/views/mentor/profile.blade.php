@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Mentor Profile Settings
    </h2>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-8" x-data="mentorProfileForm()" x-init="initImagePreview()">
    <div class="max-w-3xl w-full">
        <p class="text-gray-500 mb-6">Update your mentor profile information</p>
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
        <form action="{{ route('mentor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 rounded-2xl shadow-xl">
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
                    <label class="block font-semibold mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $mentor->name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">User Name *</label>
                    <input type="text" name="username" value="{{ old('username', $mentor->username) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $mentor->phone) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Date of Birth *</label>
                    <input type="date" name="dob" value="{{ old('dob', $mentor->dob) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Gender *</label>
                    <select name="gender" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                        <option value="male" @if(old('gender', $mentor->gender) == 'male') selected @endif>Male</option>
                        <option value="female" @if(old('gender', $mentor->gender) == 'female') selected @endif>Female</option>
                        <option value="other" @if(old('gender', $mentor->gender) == 'other') selected @endif>Other</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1">Bio *</label>
                <textarea name="bio" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>{{ old('bio', $mentor->bio) }}</textarea>
            </div>
            <!-- Education Section -->
            <div x-data="{ educations: {{ old('educations', json_encode($mentor->educations ?? [])) }} }" class="mb-6">
                <label class="block font-semibold mb-1">Education</label>
                <template x-for="(education, index) in educations" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'educations['+index+'][degree]'" x-model="education.degree" placeholder="Degree" class="border rounded px-2 py-1 w-1/3">
                        <input type="text" :name="'educations['+index+'][institution]'" x-model="education.institution" placeholder="Institution" class="border rounded px-2 py-1 w-1/3">
                        <input type="text" :name="'educations['+index+'][year]'" x-model="education.year" placeholder="Year" class="border rounded px-2 py-1 w-1/4">
                        <button type="button" @click="educations.splice(index, 1)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="educations.push({degree: '', institution: '', year: ''})" class="mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"><i class="fa-solid fa-plus"></i> Add Education</button>
            </div>
            <!-- Experience Section -->
            <div x-data="{ experiences: {{ old('experiences', json_encode($mentor->experiences ?? [])) }} }" class="mb-6">
                <label class="block font-semibold mb-1">Experience</label>
                <template x-for="(experience, index) in experiences" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'experiences['+index+'][title]'" x-model="experience.title" placeholder="Title" class="border rounded px-2 py-1 w-1/3">
                        <input type="text" :name="'experiences['+index+'][company]'" x-model="experience.company" placeholder="Company" class="border rounded px-2 py-1 w-1/3">
                        <input type="text" :name="'experiences['+index+'][years]'" x-model="experience.years" placeholder="Years" class="border rounded px-2 py-1 w-1/4">
                        <button type="button" @click="experiences.splice(index, 1)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="experiences.push({title: '', company: '', years: ''})" class="mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"><i class="fa-solid fa-plus"></i> Add Experience</button>
            </div>
            <!-- Skills Section -->
            <div x-data="{ skills: {{ old('skills', json_encode($mentor->skills ?? [])) }} }" class="mb-6">
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