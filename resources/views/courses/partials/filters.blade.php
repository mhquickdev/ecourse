<h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fa-solid fa-filter"></i> Filters</h3>
<!-- Categories -->
<div class="mb-6" x-data="{ showAll: false }">
    <h4 class="font-semibold text-gray-800 mb-2">Categories</h4>
    <ul class="space-y-1">
        @foreach($categories as $i => $category)
            <li x-show="showAll || {{ $i }} < 8">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="category[]" value="{{ $category->id }}" class="form-checkbox rounded text-pink-500">
                    <span>{{ $category->name }} <span class="text-xs text-gray-400">({{ $category->courses_count }})</span></span>
                </label>
            </li>
        @endforeach
    </ul>
    @if(count($categories) > 8)
        <button type="button" class="text-xs text-pink-500 hover:underline mt-2 inline-block" @click="showAll = !showAll" x-text="showAll ? 'See Less' : 'See More'"></button>
    @endif
</div>
<!-- Instructors -->
<div class="mb-6" x-data="{ showAll: false }">
    <h4 class="font-semibold text-gray-800 mb-2">Instructors</h4>
    <ul class="space-y-1">
        @foreach($instructors as $i => $instructor)
            <li x-show="showAll || {{ $i }} < 8">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="instructor[]" value="{{ $instructor->id }}" class="form-checkbox rounded text-pink-500">
                    <span>{{ $instructor->name }} <span class="text-xs text-gray-400">({{ $instructor->courses_count }})</span></span>
                </label>
            </li>
        @endforeach
    </ul>
    @if(count($instructors) > 8)
        <button type="button" class="text-xs text-pink-500 hover:underline mt-2 inline-block" @click="showAll = !showAll" x-text="showAll ? 'See Less' : 'See More'"></button>
    @endif
</div>
<!-- Price -->
<div class="mb-6">
    <h4 class="font-semibold text-gray-800 mb-2">Price</h4>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="price" value="all" class="form-radio text-pink-500" checked>
        <span>All</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="price" value="free" class="form-radio text-pink-500">
        <span>Free</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="price" value="paid" class="form-radio text-pink-500">
        <span>Paid</span>
    </label>
</div>
<!-- Range -->
<div class="mb-6">
    <h4 class="font-semibold text-gray-800 mb-2">Range</h4>
    <input type="range" min="0" max="500" value="100" name="range" class="w-full accent-pink-500">
    <div class="flex justify-between text-xs text-gray-400 mt-1">
        <span>$0</span><span>$500</span>
    </div>
</div>
<!-- Reviews -->
<div class="mb-2">
    <h4 class="font-semibold text-gray-800 mb-2">Reviews</h4>
    <ul class="space-y-1">
        @for($i = 5; $i >= 1; $i--)
            <li>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="review[]" value="{{ $i }}" class="form-checkbox rounded text-pink-500">
                    <span>
                        @for($j = 1; $j <= 5; $j++)
                            <i class="fa-solid fa-star {{ $j <= $i ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </span>
                </label>
            </li>
        @endfor
    </ul>
</div> 