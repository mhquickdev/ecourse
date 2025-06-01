@extends('layouts.student')

@section('content')
<div class="container mx-auto p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Transactions</h2>

    @if($enrollments->isEmpty())
        <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-600">
            No transactions found.
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($enrollments as $enrollment)
                        @php
                            $paymentDetails = json_decode($enrollment->payment_details, true);
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->course->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->amount_paid ?? 'N/A' }}</td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $paymentMethod = $paymentDetails['payment_method'] ?? 'N/A';
                                @endphp
                                @if($paymentMethod === 'N/A')
                                    Free
                                @elseif($paymentMethod === 'bank_transfer')
                                    Bank Transfer
                                @else
                                    {{ $paymentMethod }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->transaction_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($enrollment->payment_status ?? 'N/A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 