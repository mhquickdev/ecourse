<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CSS & JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col transform transition-transform duration-200 ease-in-out md:relative md:translate-x-0 md:flex md:w-64">
            <div class="h-16 flex items-center px-6 border-b">
                <span class="font-bold text-2xl text-blue-600 tracking-wide">Luma</span>
                <button class="ml-auto md:hidden" @click="sidebarOpen = false">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <div class="text-xs text-gray-400 uppercase mb-2 tracking-wider">Mentor Panel</div>
                <a href="{{ route('mentor.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold gap-2">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('mentor.courses.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-book"></i> Manage Courses
                </a>
                <a href="{{ route('mentor.profile') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-user"></i> Profile Settings
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-pen"></i> Manage Quizzes
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-dollar-sign"></i> Earnings
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-file-invoice"></i> Statement
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-edit"></i> Edit Course
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-puzzle-piece"></i> Edit Quiz
                </a>
            </nav>
            <div class="p-4 border-t text-xs text-gray-400">Luma &copy; 2024</div>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen bg-gray-50">
            <!-- Header -->
            <header class="h-16 bg-white flex items-center px-4 md:px-8 shadow-sm sticky top-0 z-10 w-full">
                <button class="md:hidden mr-4" @click="sidebarOpen = true">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
                <div class="flex-1"></div>
                <div class="flex items-center gap-4 ml-auto">
                    <button class="relative">
                        <i class="fa-regular fa-bell text-xl text-gray-400"></i>
                        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="relative" x-data="{ open: false }">
                        @php $user = auth()->user(); @endphp
                        @if($user && $user->profile_image)
                            <img src="{{ Storage::url($user->profile_image) }}" class="rounded-full w-8 h-8 border-2 border-blue-500 cursor-pointer" alt="User" @click="open = !open">
                        @else
                            <span class="inline-block w-8 h-8 rounded-full bg-gray-200 border-2 border-blue-500 cursor-pointer flex items-center justify-center" @click="open = !open">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </span>
                        @endif
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-2 z-50">
                            <a href="{{ route('mentor.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50"><i class="fa-solid fa-user mr-2"></i>Profile Settings</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50"><i class="fa-solid fa-sign-out-alt mr-2"></i>Logout</a>
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