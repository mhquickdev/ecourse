<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div x-data="{ open: false }" class="flex min-h-screen">
    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed z-40 inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:z-10 h-[100vh]">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <span class="text-2xl font-extrabold text-[#392C7D]">Admin</span>
            <button class="md:hidden text-gray-500" @click="open = false"><i class="fa fa-times"></i></button>
        </div>
        <nav class="mt-6 space-y-2 px-4 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.dashboard') ? 'bg-[#392C7D] text-white' : '' }}">
                <i class="fa fa-home"></i> Dashboard
            </a>
            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.courses.*') ? 'bg-[#392C7D] text-white' : '' }}">
                <i class="fa fa-book"></i> Courses
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.categories.*') ? 'bg-[#392C7D] text-white' : '' }}">
                <i class="fa fa-tags"></i> Categories
            </a>

            {{-- Users with Sub-items --}}
            <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6]">
                    <span class="flex items-center gap-3"><i class="fa fa-users"></i> Users</span>
                    <i class="fa transition-transform duration-200" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.users.index') && !request()->routeIs('admin.users.students.index') && !request()->routeIs('admin.users.mentors.index') ? 'bg-[#392C7D] text-white' : '' }}">
                        All Users
                    </a>
                    <a href="{{ route('admin.users.students.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.users.students.index') ? 'bg-[#392C7D] text-white' : '' }}">
                        Students
                    </a>
                    <a href="{{ route('admin.users.mentors.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.users.mentors.index') ? 'bg-[#392C7D] text-white' : '' }}">
                        Mentors
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-[#F3F4F6] {{ request()->routeIs('admin.settings.*') ? 'bg-[#392C7D] text-white' : '' }}">
                <i class="fa fa-cog"></i> Settings
            </a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="mt-auto px-4 pb-6">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg font-semibold text-red-600 hover:bg-red-50">
                <i class="fa fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </aside>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen md:ml-6 transition-all duration-200">
        <!-- Topbar for mobile -->
        <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white shadow">
            <span class="text-xl font-bold text-[#392C7D]">Admin Panel</span>
            <button @click="open = true" class="text-gray-500"><i class="fa fa-bars"></i></button>
        </div>
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html> 