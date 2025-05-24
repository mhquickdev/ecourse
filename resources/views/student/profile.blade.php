@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Student Profile Settings
    </h2>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-8" x-data="studentProfileForm()" x-init="initImagePreview()">
    <div class="max-w-3xl w-full">
        <p class="text-gray-500 mb-6">Update your student profile information</p>
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
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 rounded-2xl shadow-xl">
            @csrf
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    <img :src="imagePreview || '{{ $student->profile_image ? Storage::url($student->profile_image) : 'https://i.pravatar.cc/120' }}'" class="w-24 h-24 rounded-full border-4 border-blue-200 object-cover shadow" alt="Profile Image">
                    <label class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer shadow-lg hover:bg-blue-700 transition" title="Change profile image">
                        <i class="fa-solid fa-camera"></i>
                        <input type="file" name="profile_image" class="hidden" accept="image/*" @change="previewImage($event)">
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">User Name *</label>
                    <input type="text" name="username" value="{{ old('username', $student->username) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Date of Birth *</label>
                    <input type="date" name="dob" value="{{ old('dob', $student->dob) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Gender *</label>
                    <select name="gender" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                        <option value="male" @if(old('gender', $student->gender) == 'male') selected @endif>Male</option>
                        <option value="female" @if(old('gender', $student->gender) == 'female') selected @endif>Female</option>
                        <option value="other" @if(old('gender', $student->gender) == 'other') selected @endif>Other</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1">Bio *</label>
                <textarea name="bio" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required>{{ old('bio', $student->bio) }}</textarea>
            </div>
            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-semibold text-lg shadow">Update Profile</button>
            </div>
        </form>
    </div>
</div>
<script>
    function studentProfileForm() {
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