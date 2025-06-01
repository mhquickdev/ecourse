@props(['image', 'discount', 'instructor_avatar', 'instructor', 'category', 'title', 'rating', 'reviews', 'price', 'url', 'course' => null, 'isWishlisted' => false, 'show_price' => true, 'show_rating' => true])

<div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
    <div class="relative">
        <img src="{{ $image }}" class="w-full h-48 object-cover" alt="{{ $title }}">
        @if($discount)
            <span class="absolute top-4 left-4 bg-[#F85A7E] text-white font-bold px-3 py-1 rounded-full text-xs">{{ $discount }}</span>
        @endif
        
        {{-- Wishlist Button --}}
        @if($course && Auth::check() && Auth::user()->isStudent())
            <form x-data="{ wishlisted: {{ json_encode($isWishlisted) }} }" @submit.prevent="" class="absolute top-4 right-4">
                <button @click="
                            fetch('{{ route('wishlist.toggle', $course->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'added') {
                                    wishlisted = true;
                                } else if (data.status === 'removed') {
                                    wishlisted = false;
                                }
                                // Optionally show a temporary success/error message
                                console.log(data.message);
                            })
                            .catch(error => {
                                console.error('Error toggling wishlist:', error);
                            });
                        "
                        type="button" 
                        class="rounded-full p-2 shadow transition "
                        :class="wishlisted ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-white/80 text-gray-700 hover:bg-[#F85A7E] hover:text-white'"
                        :title="wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'">
                    <i class="fa-heart" :class="wishlisted ? 'fa-solid' : 'fa-regular'"></i>
                </button>
            </form>
        @endif
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <div class="flex items-center gap-2 mb-2">
            <img src="{{ $instructor_avatar }}" class="w-7 h-7 rounded-full object-cover border-2 border-white shadow" alt="Instructor">
            <span class="text-sm font-semibold text-gray-700">{{ $instructor }}</span>
            <span class="ml-auto px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">{{ $category }}</span>
        </div>
        <div class="font-bold text-[#181818] text-base mb-1 line-clamp-2">{{ $title }}</div>
        @if($show_rating)
        <div class="flex items-center gap-2 text-yellow-500 text-sm mb-2">
            <i class="fa-solid fa-star"></i>
            <span>{{ $rating }}</span>
            <span class="text-gray-400">({{ $reviews }} Reviews)</span>
        </div>
        @endif
        <div class="flex items-center justify-between mt-auto">
            @if($show_price)
            <span class="text-lg font-bold text-[#F85A7E]">${{ $price }}</span>
            @endif
            <a href="{{ $url }}" class="bg-black text-white font-semibold rounded-full px-5 py-2 text-xs shadow hover:bg-[#F85A7D] transition">View Course</a>
        </div>
    </div>
</div> 