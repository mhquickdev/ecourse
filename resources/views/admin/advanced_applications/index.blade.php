@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Advanced Course Applications</h2>

    @if($applications->isEmpty())
        <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-600">
            No advanced course applications found.
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied At</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($applications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->course->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->application_message ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($application->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($application->status === 'pending')
                                    {{-- Approve Button --}}
                                    <button type="button" class="text-green-600 hover:text-green-900 mr-4" onclick="showApproveModal({{ $application->id }})">Approve</button>
                                    {{-- Reject Button --}}
                                    <button type="button" class="text-red-600 hover:text-red-900" onclick="showRejectModal({{ $application->id }})">Reject</button>
                                    {{-- Review Link --}}
                                    <a href="{{ route('admin.advanced-applications.review', $application) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Review</a>
                                @else
                                    <span class="text-gray-500">{{ ucfirst($application->status) }}</span>
                                    {{-- Review Link for non-pending applications --}}
                                    <a href="{{ route('admin.advanced-applications.review', $application) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Review</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Include Modals for Approve/Reject --}}
@include('admin.advanced_applications.partials.approve_reject_modals')

@push('scripts')
<script>
function showApproveModal(applicationId) {
    const modal = document.getElementById('approve-modal');
    const form = document.getElementById('approve-form');
    const applicationIdInput = document.getElementById('approval-application-id');

    // Set the form action dynamically using Laravel route helper
    form.action = '{{ route('admin.advanced-applications.approve', ':applicationId') }}'.replace(':applicationId', applicationId);
    applicationIdInput.value = applicationId;
    modal.style.display = 'block';
}

function hideApproveModal() {
    const modal = document.getElementById('approve-modal');
    const form = document.getElementById('approve-form');
    // Clear fields and reset form action
    document.getElementById('course_link').value = '';
    document.getElementById('instruction').value = '';
    form.action = '';
     document.getElementById('approval-application-id').value = '';
    modal.style.display = 'none';
}

function showRejectModal(applicationId) {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    const applicationIdInput = document.getElementById('rejection-application-id');

    // Set the form action dynamically using Laravel route helper
    form.action = '{{ route('admin.advanced-applications.reject', ':applicationId') }}'.replace(':applicationId', applicationId);
    applicationIdInput.value = applicationId;
    modal.style.display = 'block';
}

function hideRejectModal() {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    // Clear the textarea and reset form action
    document.getElementById('rejection_note').value = '';
    form.action = ''; // Clear action to prevent accidental submission
    document.getElementById('rejection-application-id').value = '';
    modal.style.display = 'none';
}

// Optional: Close modal if clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reject-modal');
    const approveModal = document.getElementById('approve-modal');
    if (event.target == modal) {
        hideRejectModal();
    } else if (event.target == approveModal) {
        hideApproveModal();
    }
}
</script>
@endpush
@endsection 