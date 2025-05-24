<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-xl flex flex-col rounded-2xl m-4 md:relative md:translate-x-0 md:flex md:w-64 transition-transform duration-200 ease-in-out">
            <div class="h-16 flex items-center px-6 border-b">
                <span class="font-bold text-2xl text-green-600 tracking-wide">Quick LMS</span>
                <button class="ml-auto md:hidden" @click="sidebarOpen = false">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl font-semibold gap-3 transition shadow-sm mb-1 {{ request()->routeIs('student.dashboard') ? 'bg-[#22D3EE] text-white shadow-lg' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fa-solid fa-home"></i> Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl font-semibold gap-3 transition shadow-sm mb-1 hover:bg-gray-100 text-gray-700">
                    <i class="fa-solid fa-book"></i> My Courses
                </a>
                <a href="{{ route('student.profile') }}" class="flex items-center px-4 py-3 rounded-xl font-semibold gap-3 transition shadow-sm mb-1 {{ request()->routeIs('student.profile') ? 'bg-[#34D399] text-white shadow-lg' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fa-solid fa-user"></i> Profile
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl font-semibold gap-3 transition shadow-sm mb-1 hover:bg-gray-100 text-gray-700">
                    <i class="fa-solid fa-certificate"></i> Certificates
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl font-semibold gap-3 transition shadow-sm mb-1 hover:bg-gray-100 text-gray-700">
                    <i class="fa-solid fa-cog"></i> Settings
                </a>
            </nav>
            <div class="p-4 border-t text-xs text-gray-400">MHQuickDEV &copy; 2025</div>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen bg-gray-50">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white rounded-xl shadow px-8 py-4 mt-6 mx-6 mb-8">
                <button class="md:hidden mr-4" @click="sidebarOpen = true">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
                <a class="flex items-center gap-2 text-green-600 font-semibold text-lg border border-green-200 bg-white rounded-xl px-4 py-2" href="{{ route('home') }}">
                    <i class="fa-solid fa-house"></i>
                    Home</a>
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-green-200 bg-white shadow hover:bg-green-50 transition focus:outline-none">
                            <i class="fa fa-user-circle text-2xl text-green-400"></i>
                            <span class="font-semibold text-gray-700">Hi, {{ Auth::user()->name ?? 'Student' }}!</span>
                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50"><i class="fa-solid fa-user mr-2"></i>Profile Settings</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50"><i class="fa-solid fa-sign-out-alt mr-2"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </header>
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html> 