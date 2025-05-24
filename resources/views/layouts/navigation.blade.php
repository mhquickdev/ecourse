<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#fff7f7] to-[#f3f8fd] w-full shadow-none border-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 flex items-center justify-between h-20">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 flex-shrink-0">
            <span class="inline-block w-10 h-10">
                <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" rx="24" fill="#2D2363"/>
                    <path d="M24 12L38 18L24 24L10 18L24 12Z" fill="#F85A7E"/>
                    <circle cx="24" cy="28" r="4" fill="#2D2363"/>
                </svg>
            </span>
            <span class="font-extrabold text-2xl text-[#2D2363] tracking-tight">Dreams <span class="text-[#F85A7E] text-xs align-top font-bold ml-1">LMS</span></span>
        </a>
        <!-- Desktop Nav Links -->
        <div class="hidden md:flex flex-1 items-center justify-center">
            <ul class="flex gap-6 items-center">
                <li>
                    <a href="/" class="text-[#F85A7E] font-semibold flex items-center gap-1">Home</a>
                </li>
            </ul>
        </div>
        <!-- Right Side (Desktop) -->
        <div class="hidden md:flex items-center gap-3">
            <!-- Language -->
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1 cursor-pointer text-sm font-medium"><img src="https://flagcdn.com/us.svg" class="w-5 h-5 rounded-full" alt="ENG"> ENG <i class="fa-solid fa-chevron-down text-xs"></i></span>
            </div>
            @guest
                <a href="{{ route('login') }}" class="ml-4 px-6 py-2 rounded-full bg-[#2D2363] text-white font-semibold hover:bg-[#1a153a] transition">Sign In</a>
                <a href="{{ route('register') }}" class="ml-2 px-6 py-2 rounded-full bg-[#F85A7E] text-white font-semibold hover:bg-[#e13a5e] transition">Register</a>
            @else
                @if(Auth::user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" class="ml-4 px-6 py-2 rounded-full bg-[#2D2363] text-white font-semibold hover:bg-[#1a153a] transition">Dashboard</a>
                @endif
            @endguest
        </div>
        <!-- Hamburger (Mobile) -->
        <button @click="open = !open" class="md:hidden flex items-center justify-center w-10 h-10 rounded focus:outline-none" aria-label="Open Menu">
            <svg :class="{'hidden': open, 'block': !open}" class="block h-6 w-6 text-[#2D2363]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg :class="{'block': open, 'hidden': !open}" class="hidden h-6 w-6 text-[#2D2363]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-200 px-4 pb-4">
        <ul class="flex flex-col gap-2 py-2">
            <li>
                <a href="/" class="block text-[#F85A7E] font-semibold py-2">Home</a>
            </li>
        </ul>
        <div class="flex items-center gap-2 mb-2">
            <span class="flex items-center gap-1 cursor-pointer text-sm font-medium"><img src="https://flagcdn.com/us.svg" class="w-5 h-5 rounded-full" alt="ENG"> ENG <i class="fa-solid fa-chevron-down text-xs"></i></span>
        </div>
        <div class="flex flex-col gap-2">
            @guest
                <a href="{{ route('login') }}" class="px-6 py-2 rounded-full bg-[#2D2363] text-white font-semibold hover:bg-[#1a153a] transition text-center">Sign In</a>
                <a href="{{ route('register') }}" class="px-6 py-2 rounded-full bg-[#F85A7E] text-white font-semibold hover:bg-[#e13a5e] transition text-center">Register</a>
            @else
                @if(Auth::user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" class="px-6 py-2 rounded-full bg-[#2D2363] text-white font-semibold hover:bg-[#1a153a] transition text-center">Dashboard</a>
                @endif
            @endguest
        </div>
    </div>
</nav>
