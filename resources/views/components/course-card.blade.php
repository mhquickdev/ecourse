<div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
    <div class="relative">
        <img src="{{ $image }}" class="w-full h-48 object-cover" alt="{{ $title }}">
        @if($discount)
            <span class="absolute top-4 left-4 bg-[#F85A7E] text-white font-bold px-3 py-1 rounded-full text-xs">{{ $discount }}</span>
        @endif
        <span class="absolute top-4 right-4 bg-white/80 rounded-full p-2 shadow hover:bg-[#F85A7E] hover:text-white transition cursor-pointer"><i class="fa-regular fa-heart"></i></span>
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <div class="flex items-center gap-2 mb-2">
            <img src="{{ $instructor_avatar }}" class="w-7 h-7 rounded-full object-cover border-2 border-white shadow" alt="Instructor">
            <span class="text-sm font-semibold text-gray-700">{{ $instructor }}</span>
            <span class="ml-auto px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">{{ $category }}</span>
        </div>
        <div class="font-bold text-[#181818] text-base mb-1 line-clamp-2">{{ $title }}</div>
        <div class="flex items-center gap-2 text-yellow-500 text-sm mb-2">
            <i class="fa-solid fa-star"></i>
            <span>{{ $rating }}</span>
            <span class="text-gray-400">({{ $reviews }} Reviews)</span>
        </div>
        <div class="flex items-center justify-between mt-auto">
            <span class="text-lg font-bold text-[#F85A7E]">${{ $price }}</span>
            <a href="{{ $url }}" class="bg-black text-white font-semibold rounded-full px-5 py-2 text-xs shadow hover:bg-[#F85A7E] transition">View Course</a>
        </div>
    </div>
</div> 