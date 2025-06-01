@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-[#fff7f7] via-[#f3f8fd] to-[#e6eaff] min-h-screen flex items-center justify-center py-10">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100 text-center">
            <div class="flex justify-center mb-6">
                <i class="fa-solid fa-check-circle text-green-500 text-6xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Enrollment Successful!</h2>
            <p class="text-gray-700 mb-6">Congratulations! You have successfully enrolled in the course.</p>

            <div class="mb-6 text-left">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Enrollment Details</h3>
                <p class="text-gray-700"><strong>Course:</strong> {{ $enrollment->course->title ?? 'N/A' }}</p>
                <p class="text-gray-700"><strong>Enrolled At:</strong> {{ $enrollment->enrolled_at->format('M d, Y H:i A') ?? 'N/A' }}</p>
                <p class="text-gray-700"><strong>Payment Status:</strong> {{ ucfirst($enrollment->payment_status) ?? 'N/A' }}</p>
                @if($enrollment->amount_paid > 0)
                    <p class="text-gray-700"><strong>Amount Paid:</strong> ${{ number_format($enrollment->amount_paid, 2) }}</p>
                    <p class="text-gray-700"><strong>Payment Method:</strong> {{ $enrollment->payment_method ?? 'N/A' }}</p>
                    <p class="text-gray-700"><strong>Transaction ID:</strong> {{ $enrollment->transaction_id ?? 'N/A' }}</p>
                @else
                     <p class="text-gray-700"><strong>Price:</strong> Free</p>
                @endif
            </div>

            <a href="{{ route('student.course-content', $enrollment->course) }}" class="w-full inline-block bg-[#392C7D] text-white font-bold rounded-lg py-3 text-lg shadow hover:bg-[#2D2363] transition">Start Learning</a>
             <a href="{{ route('student.my-courses') }}" class="w-full inline-block mt-4 bg-gray-200 text-gray-700 font-bold rounded-lg py-3 text-lg shadow hover:bg-gray-300 transition">Go to My Courses</a>
        </div>
    </div>
</div>
@endsection 