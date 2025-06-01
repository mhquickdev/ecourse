@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">My Wishlist</h2>

            @if($wishlistItems->isEmpty())
                <div class="alert alert-info">
                    Your wishlist is empty. 
                    <a href="{{ route('courses.index') }}" class="alert-link text-blue-500 hover:underline">Browse available courses</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlistItems as $wishlistItem)
                        @include('components.course-card', [
                            'image' => $wishlistItem->course->preview_image ? (Str::startsWith($wishlistItem->course->preview_image, 'http') ? $wishlistItem->course->preview_image : asset('storage/'.$wishlistItem->course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                            'discount' => $wishlistItem->course->is_free ? 'Free' : null,
                            'instructor_avatar' => $wishlistItem->course->user && $wishlistItem->course->user->profile_image ? Storage::url($wishlistItem->course->user->profile_image) : 'https://i.pravatar.cc/120',
                            'instructor' => $wishlistItem->course->user->name ?? 'Instructor',
                            'category' => $wishlistItem->course->category->name ?? 'General',
                            'title' => $wishlistItem->course->title,
                            'rating' => 'N/A', // Assuming no specific rating on wishlist item
                            'reviews' => 'N/A', // Assuming no specific reviews on wishlist item
                            'price' => $wishlistItem->course->is_free ? 'Free' : number_format($wishlistItem->course->price, 2),
                            'url' => route('courses.show', $wishlistItem->course),
                            'isWishlisted' => true, // Indicate that it is wishlisted
                        ])
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 