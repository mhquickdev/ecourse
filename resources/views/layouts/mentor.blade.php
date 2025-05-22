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
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <div class="h-16 flex items-center px-6 border-b">
                <span class="font-bold text-2xl text-blue-600 tracking-wide">Luma</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <div class="text-xs text-gray-400 uppercase mb-2 tracking-wider">Mentor Panel</div>
                <a href="{{ route('mentor.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold gap-2">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('mentor.courses.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 gap-2">
                    <i class="fa-solid fa-book"></i> Manage Courses
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
            @yield('content')
        </div>
    </div>
</body>
</html> 