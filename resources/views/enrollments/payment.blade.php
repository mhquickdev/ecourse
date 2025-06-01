@extends('layouts.app')

@section('content')
<!-- Enrollment Payment Section with Welcome Page Hero Style -->
<div class="bg-gradient-to-br from-[#fff7f7] via-[#f3f8fd] to-[#e6eaff] min-h-screen flex items-center justify-center py-10">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-[#181818] mb-6 text-center">Enroll in {{ $course->title }}</h2>
            
            <div class="course-details mb-6 border-b pb-4">
                <h5 class="font-semibold text-gray-700 mb-2">Course Details</h5>
                <p class="text-gray-600"><strong>Price:</strong> <span class="text-[#F85A7E] font-bold">${{ number_format($course->price, 2) }}</span></p>
                @if($course->duration)
                 <p class="text-gray-600"><strong>Duration:</strong> {{ $course->duration }}</p>
                @endif
            </div>

            <form action="{{ route('courses.payment', $course) }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="payment_method" class="block text-gray-700 font-semibold mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F85A7E]" required>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="transaction_id" class="block text-gray-700 font-semibold mb-2">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F85A7E]" required>
                    <small class="block mt-2 text-gray-500">Enter the transaction ID from your bank transfer</small>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-blue-800">
                    <h5 class="font-bold mb-2 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Bank Account Details</h5>
                    <p class="text-sm">Please transfer the amount to the following bank account:</p>
                    <p class="text-sm mt-2"><strong>Bank:</strong> Your Bank Name<br>
                    <strong>Account Number:</strong> XXXX-XXXX-XXXX-XXXX<br>
                    <strong>Account Name:</strong> Your Company Name</p>
                </div>

                <button type="submit" class="w-full bg-[#392C7D] text-white font-bold rounded-lg py-3 text-lg shadow hover:bg-[#2D2363] transition">Submit Payment</button>
            </form>
        </div>
    </div>
</div>
@endsection 