@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Review Advanced Course Application</h2>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h5 class="font-bold text-gray-800 mb-3">Application Details</h5>
        <p class="mb-2"><strong>Student Name:</strong> {{ $advancedApplication->user->name ?? 'N/A' }}</p>
        <p class="mb-2"><strong>Course Title:</strong> {{ $advancedApplication->course->title ?? 'N/A' }}</p>
        <p class="mb-2"><strong>Applied At:</strong> {{ $advancedApplication->created_at->format('Y-m-d H:i') }}</p>
        <p class="mb-2"><strong>Status:</strong> {{ ucfirst($advancedApplication->status) }}</p>
        
        @if($advancedApplication->application_message)
            <p class="mb-2"><strong>Student Message:</strong> {{ $advancedApplication->application_message }}</p>
        @endif

        @if($advancedApplication->status === 'approved')
            <h6 class="font-bold text-gray-800 mt-4 mb-2">Approval Details:</h6>
            <p class="mb-2"><strong>Course Link:</strong> <a href="{{ $advancedApplication->course_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $advancedApplication->course_link ?? 'N/A' }}</a></p>
            <p class="mb-2"><strong>Instructions:</strong> {{ $advancedApplication->instruction ?? 'N/A' }}</p>
        @elseif($advancedApplication->status === 'rejected')
             <h6 class="font-bold text-gray-800 mt-4 mb-2">Rejection Details:</h6>
             <p class="mb-2"><strong>Rejection Note:</strong> {{ $advancedApplication->rejection_note ?? 'N/A' }}</p>
        @endif

        {{-- Add Approve/Reject buttons here as well if needed for this view --}}
         @if($advancedApplication->status === 'pending')
            <div class="mt-6">
                 <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 mr-4" onclick="showApproveModal({{ $advancedApplication->id }})">Approve</button>
                 <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200" onclick="showRejectModal({{ $advancedApplication->id }})">Reject</button>
            </div>
         @endif
    </div>

    {{-- Include Modals for Approve/Reject --}}
     @include('admin.advanced_applications.partials.approve_reject_modals', ['applicationId' => $advancedApplication->id])

</div>
@endsection

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
    const rejectModal = document.getElementById('reject-modal');
    const approveModal = document.getElementById('approve-modal');
    if (event.target == rejectModal) {
        hideRejectModal();
    } else if (event.target == approveModal) {
        hideApproveModal();
    }
}
</script>
@endpush 